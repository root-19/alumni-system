<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Training;
use App\Models\TrainingFile;
use App\Models\UserTrainingProgress;
use App\Models\UserModuleProgress;
use App\Services\SimpleCertService;
use App\Services\SimpleCertServiceV2;
use App\Services\SimpleCertServiceFixed;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['files', 'quizzes', 'finalAssessments'])->get();
        $user = Auth::user();
        
        // Get all quiz attempts for all trainings (get latest attempt for each quiz)
        $allQuizIds = $trainings->flatMap->quizzes->pluck('id');
        $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $allQuizIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('quiz_id')
            ->keyBy('quiz_id');
        
        // Get all final assessment attempts for all trainings
        $allFinalAssessmentIds = $trainings->flatMap->finalAssessments->pluck('id');
        $finalAssessmentAttempts = \App\Models\UserFinalAssessmentAttempt::where('user_id', $user->id)
            ->whereIn('final_assessment_id', $allFinalAssessmentIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('final_assessment_id')
            ->keyBy('final_assessment_id');
        
        return view('training', compact('trainings', 'quizAttempts', 'finalAssessmentAttempts'));
    }

    public function take($id)
{
    $training = \App\Models\Training::with(['files', 'quizzes', 'finalAssessments'])->findOrFail($id);
    $user = Auth::user();

    // Get user's existing progress
    $total = $training->files->where('type', 'module')->count();
    $read = $user->reads()
                ->whereIn('training_file_id', $training->files->pluck('id'))
                ->count();
    
    // Get stored progress from user_training_progress table
    $userProgress = UserTrainingProgress::where('user_id', $user->id)
        ->where('training_id', $training->id)
        ->first();
    
    // Get detailed module progress
    $moduleProgresses = UserModuleProgress::where('user_id', $user->id)
        ->whereIn('training_file_id', $training->files->pluck('id'))
        ->get();
    
    // Calculate current progress from detailed module data
    if ($moduleProgresses->count() > 0) {
        $averageProgress = round($moduleProgresses->avg('completion_percentage'));
    } else {
        $averageProgress = $total > 0 ? round(($read / $total) * 100) : 0;
    }
    
    // Update user-specific progress with calculated value
    UserTrainingProgress::updateOrCreate(
        ['user_id' => $user->id, 'training_id' => $training->id],
        ['progress' => $averageProgress]
    );

    // Get quiz and final assessment attempts for this user (get latest attempt for each)
    $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
        ->whereIn('quiz_id', $training->quizzes->pluck('id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('quiz_id')
        ->keyBy('quiz_id');
    
    $finalAssessmentAttempts = \App\Models\UserFinalAssessmentAttempt::where('user_id', $user->id)
        ->whereIn('final_assessment_id', $training->finalAssessments->pluck('id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('final_assessment_id')
        ->keyBy('final_assessment_id');

    return view('training.take', compact('training', 'total', 'read', 'moduleProgresses', 'averageProgress', 'quizAttempts', 'finalAssessmentAttempts'));
}

 public function markAsRead(Training $training, TrainingFile $file)
{
    $user = Auth::user();
    
    // Create the read record if it doesn't exist
    $user->reads()->firstOrCreate([
        'training_file_id' => $file->id
    ]);

    // Calculate new progress
    $totalModules = $training->files()->where('type', 'module')->count();
    $readModules = $user->reads()
        ->whereIn('training_file_id', $training->files()->pluck('id'))
        ->count();
    
    // Calculate new progress
    $newProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
    
    // Update user-specific progress
    UserTrainingProgress::updateOrCreate(
        ['user_id' => $user->id, 'training_id' => $training->id],
        ['progress' => $newProgress]
    );

    // Return updated progress info
    return response()->json([
        'success' => true,
        'progress' => $newProgress,
        'hasAllCertificates' => $newProgress === 100,
        'readCount' => $readModules,
        'totalCount' => $totalModules
    ]);
}

    /**
     * Update stored progress percentage for a training.
     * Accepts numeric progress 0-100; when reaches 100 certificate unlocks automatically (front-end already checks).
     */
    public function updateProgress(Request $request, Training $training)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        // For security ensure user has at least one read record or has accessed take page; minimal gate can be expanded.
        $training->progress = $request->progress;
        $training->save();

        return response()->json([
            'status' => 'ok',
            'progress' => $training->progress
        ]);
    }

    public function downloadCertificate(Training $training)
    {
        $user = Auth::user();
        
        // Check if user has completed the training
        $totalModules = $training->files->where('type', 'module')->count();
        $readModules = $user->reads()
            ->whereIn('training_file_id', $training->files->pluck('id'))
            ->count();
        $calculatedProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
        
        // Get user's stored progress
        $userProgress = UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();
        $storedProgress = $userProgress ? $userProgress->progress : 0;
        
        // Check if all quizzes are passed (if exists)
        $hasQuiz = $training->quizzes()->where('is_active', true)->count() > 0;
        $allQuizzesPassed = true;
        
        if ($hasQuiz) {
            $quizIds = $training->quizzes()->where('is_active', true)->pluck('id');
            $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
                ->whereIn('quiz_id', $quizIds)
                ->whereNotNull('completed_at')
                ->get()
                ->unique('quiz_id')
                ->keyBy('quiz_id');
            
            // Check if all quizzes are passed
            foreach ($quizIds as $quizId) {
                $quizAttempt = $quizAttempts[$quizId] ?? null;
                if (!$quizAttempt || !$quizAttempt->passed) {
                    $allQuizzesPassed = false;
                    break;
                }
            }
        }
        
        // Certificate download logic:
        // If quizzes exist: allow download if ALL quizzes are PASSED (regardless of module completion)
        // If no quizzes: allow download if modules are 100% complete
        if ($hasQuiz) {
            $canDownload = $allQuizzesPassed && $training->certificate_path;
        } else {
            $canDownload = ($calculatedProgress >= 100 || $storedProgress >= 100) && $training->certificate_path;
        }
        
        if ($canDownload) {
            // Try SimpleCert service with multiple approaches - Fixed version first
            $simpleCertFixed = new SimpleCertServiceFixed();
            $simpleCertService = new SimpleCertService();
            $simpleCertV2 = new SimpleCertServiceV2();
            
            // Build full name properly
            $firstName = trim($user->name ?? '');
            $lastName = trim($user->last_name ?? '');
            $middleName = trim($user->middle_name ?? '');
            
            $fullName = $firstName;
            if ($middleName) {
                $fullName .= ' ' . $middleName;
            }
            if ($lastName) {
                $fullName .= ' ' . $lastName;
            }
            
            // If still empty, use email as fallback
            if (empty($fullName)) {
                $fullName = $user->email;
            }

            $certificateData = [
                'studentName' => $fullName,
                'trainingTitle' => $training->title,
                'completionDate' => now()->format('F d, Y'),
                'schoolName' => config('app.name', 'Alumni Training Portal'),
                'certificateId' => 'CERT-' . $training->id . '-' . $user->id . '-' . time(),
            ];

            $simpleCertResult = null;
            $workingMethod = null;

            // Try Fixed method 1: X-API-Key header
            $simpleCertResult = $simpleCertFixed->generateCertificate($certificateData);
            if ($simpleCertResult['success']) {
                $workingMethod = 'fixed_x_api_key';
            }
            
            // Try Fixed method 2: API key in query parameter
            if (!$simpleCertResult || !$simpleCertResult['success']) {
                $simpleCertResult = $simpleCertFixed->generateCertificateAlt($certificateData);
                if ($simpleCertResult['success']) {
                    $workingMethod = 'fixed_query_param';
                }
            }
            
            // Try original methods if fixed ones don't work
            if (!$simpleCertResult || !$simpleCertResult['success']) {
                $email = config('services.simplecert.email');
                $password = config('services.simplecert.password');
                
                if ($email && $password) {
                    $validation = $simpleCertService->validateApiKey();
                    if ($validation['success']) {
                        $simpleCertResult = $simpleCertService->generateCertificate($certificateData);
                        if ($simpleCertResult['success']) {
                            $workingMethod = 'original_auth';
                        }
                    }
                }
            }
            
            // Try direct API key method
            if (!$simpleCertResult || !$simpleCertResult['success']) {
                $simpleCertResult = $simpleCertV2->generateCertificateDirect($certificateData);
                if ($simpleCertResult['success']) {
                    $workingMethod = 'direct_api_key';
                }
            }
            
            // Try alternative data structure
            if (!$simpleCertResult || !$simpleCertResult['success']) {
                $simpleCertResult = $simpleCertV2->generateCertificateAlternative($certificateData);
                if ($simpleCertResult['success']) {
                    $workingMethod = 'alternative_data';
                }
            }
            
            if ($simpleCertResult && $simpleCertResult['success']) {
                // Download and store the certificate from SimpleCert
                $downloadResult = null;
                
                if (strpos($workingMethod, 'fixed_') === 0) {
                    $downloadResult = $simpleCertFixed->downloadCertificate(
                        $simpleCertResult['certificate_id'], 
                        Str::slug($training->title) . '-certificate-' . $user->id . '.pdf'
                    );
                } elseif ($workingMethod === 'original_auth') {
                    $downloadResult = $simpleCertService->downloadCertificate(
                        $simpleCertResult['certificate_id'], 
                        Str::slug($training->title) . '-certificate-' . $user->id . '.pdf'
                    );
                } else {
                    $downloadResult = $simpleCertV2->downloadCertificate(
                        $simpleCertResult['certificate_id'], 
                        Str::slug($training->title) . '-certificate-' . $user->id . '.pdf'
                    );
                }
                
                if ($downloadResult && $downloadResult['success']) {
                    $path = $downloadResult['path'];
                    $fileName = $downloadResult['filename'];
                    
                    Log::info('SimpleCert certificate generated and downloaded successfully', [
                        'method' => $workingMethod,
                        'path' => $path,
                        'filename' => $fileName,
                        'user_id' => $user->id,
                        'training_id' => $training->id
                    ]);
                    
                    return response()->download(storage_path('app/public/' . $path), $fileName);
                }
            }
            
            // Fallback to local PNG generation if SimpleCert fails
            Log::warning('SimpleCert failed, falling back to local PNG generation', [
                'simplecert_error' => $simpleCertResult['error'] ?? 'Unknown error',
                'working_method' => $workingMethod,
                'fallback' => true
            ]);

            // Otherwise, generate a fresh PNG on-the-fly
            try {
                $data = [
                    'schoolName' => config('app.name', 'Alumni Training Portal'),
                    'trainingTitle' => $training->title,
                    'studentName' => $fullName,
                    'completionDate' => now()->format('F d, Y'),
                    'percentage' => max($calculatedProgress, $storedProgress),
                    'attempt' => (object)['id' => $user->id],
                    'logoPath' => public_path('image/logo.png'),
                    'hasLogo' => file_exists(public_path('image/logo.png')),
                ];

                Log::info('Certificate data prepared for fallback PNG generation', [
                    'studentName' => $data['studentName'],
                    'trainingTitle' => $data['trainingTitle'],
                    'completionDate' => $data['completionDate']
                ]);

                $fileName = Str::slug($training->title) . '-certificate-' . $user->id . '.png';
                $pngResult = $this->generatePngCertificate($data, $fileName);
                
                if ($pngResult['success']) {
                    return response()->download(storage_path('app/public/' . $pngResult['path']), $pngResult['filename']);
                } else {
                    throw new \Exception('PNG generation failed: ' . $pngResult['error']);
                }
            } catch (\Exception $e) {
                Log::error('Certificate generation failed: ' . $e->getMessage(), [
                    'training_id' => $training->id,
                    'user_id' => Auth::id(),
                    'exception' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Unable to generate certificate. Please contact support.');
            }
        }
        
        return back()->with('error', 'Certificate not available. Complete all modules first.');
    }

    /**
     * Update detailed module progress (scroll position, time spent, etc.)
     */
    public function updateModuleProgress(Request $request, Training $training, TrainingFile $file)
    {
        $request->validate([
            'scroll_progress' => 'required|integer|min:0|max:100',
            'time_spent' => 'required|integer|min:0',
            'completion_percentage' => 'required|integer|min:0|max:100'
        ]);

        $user = Auth::user();
        
        // Update or create module progress
        $moduleProgress = UserModuleProgress::updateOrCreate(
            ['user_id' => $user->id, 'training_file_id' => $file->id],
            [
                'scroll_progress' => $request->scroll_progress,
                'time_spent' => $request->time_spent,
                'completion_percentage' => $request->completion_percentage,
                'is_completed' => $request->completion_percentage >= 100,
                'last_accessed_at' => now()
            ]
        );

        // Calculate overall training progress
        $this->updateTrainingProgress($user, $training);

        return response()->json([
            'success' => true,
            'module_progress' => $moduleProgress,
            'message' => 'Module progress updated successfully'
        ]);
    }

    /**
     * Update overall training progress based on module progress
     */
    private function updateTrainingProgress($user, $training)
    {
        $totalModules = $training->files()->where('type', 'module')->count();
        
        if ($totalModules === 0) {
            return 0;
        }

        // Get all module progress for this training
        $moduleProgresses = UserModuleProgress::where('user_id', $user->id)
            ->whereIn('training_file_id', $training->files()->pluck('id'))
            ->get();

        // Calculate average completion percentage
        $totalCompletion = $moduleProgresses->sum('completion_percentage');
        $averageProgress = $moduleProgresses->count() > 0 ? $totalCompletion / $moduleProgresses->count() : 0;

        // Update user training progress
        UserTrainingProgress::updateOrCreate(
            ['user_id' => $user->id, 'training_id' => $training->id],
            ['progress' => round($averageProgress)]
        );

        return round($averageProgress);
    }

    /**
     * Generate PNG certificate with professional green theme using PHP GD
     */
    private function generatePngCertificate($data, $filename)
    {
        try {
            // Create image from HTML using PHP GD
            $certificatePath = storage_path('app/public/certificates/' . $filename);
            
            // Ensure directory exists
            $directory = dirname($certificatePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Create certificate image with professional dimensions
            $width = 1200;
            $height = 900;
            $image = imagecreatetruecolor($width, $height);
            
            // Professional green color palette
            $darkGreen = imagecolorallocate($image, 26, 95, 63);      // #1a5f3f
            $medGreen = imagecolorallocate($image, 45, 122, 87);       // #2d7a57
            $lightGreen = imagecolorallocate($image, 74, 157, 111);     // #4a9d6f
            $paleGreen = imagecolorallocate($image, 232, 245, 233);     // #e8f5e9
            $gold = imagecolorallocate($image, 212, 175, 55);           // #d4af37
            $silver = imagecolorallocate($image, 192, 192, 192);         // #c0c0c0
            $bronze = imagecolorallocate($image, 205, 127, 50);         // #cd7f32
            $white = imagecolorallocate($image, 255, 255, 255);          // #ffffff
            $black = imagecolorallocate($image, 26, 26, 26);             // #1a1a1a
            $gray = imagecolorallocate($image, 102, 102, 102);           // #666666
            
            // Fill background with gradient effect
            imagefill($image, 0, 0, $darkGreen);
            
            // Create main white content area
            imagefilledrectangle($image, 60, 60, $width - 60, $height - 60, $white);
            
            // Add metallic borders
            imagerectangle($image, 40, 40, $width - 40, $height - 40, $gold);
            imagerectangle($image, 50, 50, $width - 50, $height - 50, $silver);
            imagerectangle($image, 60, 60, $width - 60, $height - 60, $bronze);
            
            // Add corner decorations
            $this->drawGDCornerOrnament($image, 70, 70, $gold, $lightGreen);
            $this->drawGDCornerOrnament($image, $width - 150, 70, $gold, $lightGreen);
            $this->drawGDCornerOrnament($image, 70, $height - 150, $gold, $lightGreen);
            $this->drawGDCornerOrnament($image, $width - 150, $height - 150, $gold, $lightGreen);
            
            // Add header section
            $this->drawGDHeader($image, $data, $width, $darkGreen, $gold, $silver);
            
            // Add recipient section
            $this->drawGDRecipient($image, $data, $width, $medGreen, $gold);
            
            // Add achievement section
            $this->drawGDAchievement($image, $data, $width, $lightGreen, $gray);
            
            // Add training section
            $this->drawGDTraining($image, $data, $width, $darkGreen, $black);
            
            // Add details section
            $this->drawGDDetails($image, $data, $width, $medGreen, $gold);
            
            // Add footer section
            $this->drawGDFooter($image, $data, $width, $darkGreen, $silver);
            
            // Add professional seal
            $this->drawGDSeal($image, $width, $height, $gold, $bronze, $darkGreen);
            
            // Add watermark
            $this->drawGDWatermark($image, $width, $height, $lightGreen);
            
            // Save the high-quality image
            imagepng($image, $certificatePath, 9);
            imagedestroy($image);
            
            return [
                'success' => true,
                'path' => 'certificates/' . $filename,
                'filename' => $filename
            ];
            
        } catch (\Exception $e) {
            Log::error('PNG certificate generation failed: ' . $e->getMessage(), [
                'filename' => $filename,
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Draw corner ornament using GD
     */
    private function drawGDCornerOrnament($image, $x, $y, $gold, $lightGreen)
    {
        $size = 80;
        // Outer gold circle
        imageellipse($image, $x + $size/2, $y + $size/2, $size, $size, $gold);
        // Inner green circle
        imageellipse($image, $x + $size/2, $y + $size/2, $size - 30, $size - 30, $lightGreen);
        
        // Add star symbol
        $star = '★';
        imagestring($image, 5, $x + $size/2 - 8, $y + $size/2 - 8, $star, $gold);
    }

    /**
     * Draw header section using GD
     */
    private function drawGDHeader($image, $data, $width, $darkGreen, $gold, $silver)
    {
        // Header background
        imagefilledrectangle($image, 100, 100, $width - 100, 180, $darkGreen);
        
        // Gold accent lines
        imageline($image, 100, 100, $width - 100, 100, $gold);
        imageline($image, 100, 180, $width - 100, 180, $silver);
        
        // Main title
        $title = $data['schoolName'] . ' Certificate of Completion';
        $titleWidth = imagefontwidth(5) * strlen($title);
        imagestring($image, 5, $width/2 - $titleWidth/2, 120, $title, $gold);
        
        // Subtitle
        $subtitle = 'Excellence in Learning • Achievement in Practice';
        $subtitleWidth = imagefontwidth(2) * strlen($subtitle);
        imagestring($image, 2, $width/2 - $subtitleWidth/2, 155, $subtitle, $silver);
    }

    /**
     * Draw recipient section using GD
     */
    private function drawGDRecipient($image, $data, $width, $medGreen, $gold)
    {
        // "Awarded to" text
        $awardedText = 'Awarded to';
        $awardedWidth = imagefontwidth(3) * strlen($awardedText);
        imagestring($image, 3, $width/2 - $awardedWidth/2, 240, $awardedText, $medGreen);
        
        // Student name
        $studentName = $data['studentName'];
        $nameWidth = imagefontwidth(5) * strlen($studentName);
        imagestring($image, 5, $width/2 - $nameWidth/2, 280, $studentName, $gold);
        
        // Metallic underline
        imageline($image, $width/2 - $nameWidth/2 - 20, 310, $width/2 + $nameWidth/2 + 20, 310, $gold);
    }

    /**
     * Draw achievement section using GD
     */
    private function drawGDAchievement($image, $data, $width, $lightGreen, $gray)
    {
        // Achievement text
        $achievementText = 'has successfully completed the training program';
        $achievementWidth = imagefontwidth(3) * strlen($achievementText);
        imagestring($image, 3, $width/2 - $achievementWidth/2, 360, $achievementText, $lightGreen);
        
        // Quote
        $quote = '"Dedication • Knowledge • Success"';
        $quoteWidth = imagefontwidth(2) * strlen($quote);
        imagestring($image, 2, $width/2 - $quoteWidth/2, 390, $quote, $gray);
        
        // Recognition text
        $recognitionText = 'This achievement reflects commitment to professional growth';
        $recognitionWidth = imagefontwidth(1) * strlen($recognitionText);
        imagestring($image, 1, $width/2 - $recognitionWidth/2, 420, $recognitionText, $gray);
    }

    /**
     * Draw training section using GD
     */
    private function drawGDTraining($image, $data, $width, $darkGreen, $black)
    {
        // Training title background
        imagefilledrectangle($image, 150, 460, $width - 150, 520, $paleGreen ?? imagecolorallocate($image, 240, 248, 240));
        imagerectangle($image, 150, 460, $width - 150, 520, $darkGreen);
        
        // Training title
        $trainingTitle = $data['trainingTitle'];
        $titleWidth = imagefontwidth(4) * strlen($trainingTitle);
        if ($titleWidth > $width - 300) {
            // Wrap long titles
            $trainingTitle = wordwrap($trainingTitle, 40, "\n", true);
            $lines = explode("\n", $trainingTitle);
            $y = 480;
            foreach ($lines as $line) {
                $lineWidth = imagefontwidth(4) * strlen($line);
                imagestring($image, 4, $width/2 - $lineWidth/2, $y, $line, $darkGreen);
                $y += 20;
            }
        } else {
            imagestring($image, 4, $width/2 - $titleWidth/2, 480, $trainingTitle, $darkGreen);
        }
    }

    /**
     * Draw details section using GD
     */
    private function drawGDDetails($image, $data, $width, $medGreen, $gold)
    {
        // Details box
        imagefilledrectangle($image, $width/2 - 150, 560, $width/2 + 150, 620, $paleGreen ?? imagecolorallocate($image, 248, 255, 248));
        imagerectangle($image, $width/2 - 150, 560, $width/2 + 150, 620, $gold);
        
        // Certificate ID
        $certId = 'Certificate ID: #' . $data['attempt']->id;
        $idWidth = imagefontwidth(2) * strlen($certId);
        imagestring($image, 2, $width/2 - $idWidth/2, 575, $certId, $medGreen);
        
        // Validation text
        $validationText = 'Verified on ' . $data['completionDate'];
        $validationWidth = imagefontwidth(1) * strlen($validationText);
        imagestring($image, 1, $width/2 - $validationWidth/2, 600, $validationText, $gold);
    }

    /**
     * Draw footer section using GD
     */
    private function drawGDFooter($image, $data, $width, $darkGreen, $silver)
    {
        $footerY = 680;
        
        // Left signature area
        $schoolWidth = imagefontwidth(2) * strlen($data['schoolName']);
        imagestring($image, 2, $width/4 - $schoolWidth/2, $footerY, $data['schoolName'], $darkGreen);
        
        // Silver signature line (left)
        imageline($image, $width/4 - 100, $footerY + 20, $width/4 + 100, $footerY + 20, $silver);
        
        $adminText = 'Training Administrator';
        $adminWidth = imagefontwidth(1) * strlen($adminText);
        imagestring($image, 1, $width/4 - $adminWidth/2, $footerY + 35, $adminText, $silver);
        
        // Right signature area
        $dateLabel = 'Date Completed';
        $dateLabelWidth = imagefontwidth(2) * strlen($dateLabel);
        imagestring($image, 2, 3*$width/4 - $dateLabelWidth/2, $footerY, $dateLabel, $darkGreen);
        
        // Silver signature line (right)
        imageline($image, 3*$width/4 - 100, $footerY + 20, 3*$width/4 + 100, $footerY + 20, $silver);
        
        $dateWidth = imagefontwidth(1) * strlen($data['completionDate']);
        imagestring($image, 1, 3*$width/4 - $dateWidth/2, $footerY + 35, $data['completionDate'], $silver);
    }

    /**
     * Draw professional seal using GD
     */
    private function drawGDSeal($image, $width, $height, $gold, $bronze, $darkGreen)
    {
        $sealX = $width - 180;
        $sealY = 120;
        $sealSize = 120;
        
        // Gold circle
        imagefilledellipse($image, $sealX + $sealSize/2, $sealY + $sealSize/2, $sealSize - 10, $sealSize - 10, $gold);
        imageellipse($image, $sealX + $sealSize/2, $sealY + $sealSize/2, $sealSize - 10, $sealSize - 10, $bronze);
        
        // Inner green circle
        imageellipse($image, $sealX + $sealSize/2, $sealY + $sealSize/2, $sealSize - 40, $sealSize - 40, $darkGreen);
        
        // Star
        imagestring($image, 5, $sealX + $sealSize/2 - 8, $sealY + $sealSize/2 - 20, '★', $darkGreen);
        
        // "CERTIFIED" text
        $certifiedWidth = imagefontwidth(1) * strlen('CERTIFIED');
        imagestring($image, 1, $sealX + $sealSize/2 - $certifiedWidth/2, $sealY + $sealSize/2 + 10, 'CERTIFIED', $darkGreen);
    }

    /**
     * Draw watermark using GD
     */
    private function drawGDWatermark($image, $width, $height, $lightGreen)
    {
        $watermarkText = 'Alumni Training Portal • Excellence in Professional Development';
        $watermarkWidth = imagefontwidth(1) * strlen($watermarkText);
        imagestring($image, 1, $width/2 - $watermarkWidth/2, $height - 30, $watermarkText, $lightGreen);
    }
}
