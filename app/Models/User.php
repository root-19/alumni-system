<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\TrainingRead;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the training reads for the user.
     */
    public function reads()
    {
        return $this->hasMany(TrainingRead::class);
    }

    /**
     * Get the training progress for the user.
     */
    public function trainingProgress()
    {
        return $this->hasMany(UserTrainingProgress::class);
    }

    /**
     * Get the module progress for the user.
     */
    public function moduleProgress()
    {
        return $this->hasMany(UserModuleProgress::class);
    }

    /**
     * Document requests submitted by the user.
     */
    public function documentRequests()
    {
        return $this->hasMany(\App\Models\DocumentRequest::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'middle_name',
        'suffix',
        'year_graduated',
        'program',
        'gender',
        'status',
        'contact_number',
        'address',
        'profile_image_path',
        'email',
        'password',
        'role',
        'is_alumni',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'year_graduated' => 'integer',
        ];
    }

    /**
     * Get the user's initials (first letters of first two words in name).
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'Admin', 'administrator', 'Administrator']);
    }

    /**
     * Check if the user is a regular user.
     */
    public function isUser(): bool
    {
        return in_array($this->role, ['user', 'User']) || empty($this->role);
    }

    /**
     * Check if the user is an assistant.
     */
    public function isAssistant(): bool
    {
        return in_array($this->role, ['assistant', 'Assistant']);
    }

    /**
     * Get all admin users.
     */
    public static function getAdmins()
    {
        return static::whereIn('role', ['admin', 'Admin', 'administrator', 'Administrator'])->get();
    }

    /**
     * Get all regular users.
     */
    public static function getRegularUsers()
    {
        return static::where('role', 'user')->orWhereNull('role')->get();
    }

    /**
     * Check if the user is an alumni.
     */
    public function isAlumni(): bool
    {
        return $this->is_alumni === true;
    }

    /**
     * Get all alumni users.
     */
    public static function getAlumni()
    {
        return static::where('is_alumni', true)->get();
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    /**
     * Get the event registrations for the user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(\App\Models\EventRegistration::class);
    }

    /**
     * Get the donations made by the user.
     */
    public function donations()
    {
        return $this->hasMany(\App\Models\Donation::class);
    }
//     return $this->hasMany(TrainingRead::class);
// }
}
