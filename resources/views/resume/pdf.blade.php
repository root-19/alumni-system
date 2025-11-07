<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - {{ $resume->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000000;
            background: #fff;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: #fff;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 10pt;
            color: #333333;
            margin-top: 10px;
        }
        
        .contact-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
            border-bottom: 2px solid #000000;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-content {
            font-size: 11pt;
            line-height: 1.8;
            color: #000000;
            white-space: pre-line;
            text-align: justify;
        }
        
        .objective {
            font-style: italic;
            color: #333333;
        }
        
        .education-item,
        .training-item,
        .experience-item {
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 3px solid #e5e7eb;
            padding-left: 15px;
        }
        
        .education-item:last-child,
        .training-item:last-child,
        .experience-item:last-child {
            margin-bottom: 0;
        }
        
        @media print {
            .container {
                padding: 15mm;
            }
            
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>{{ $resume->full_name }}</h1>
            <div class="contact-info">
                @if($resume->contact_number)
                <span>
                    <strong>Phone:</strong> {{ $resume->contact_number }}
                </span>
                @endif
                @if($resume->email)
                <span>
                    <strong>Email:</strong> {{ $resume->email }}
                </span>
                @endif
            </div>
        </div>

        <!-- Objective Section -->
        @if($resume->objective)
        <div class="section">
            <div class="section-title">Objective</div>
            <div class="section-content objective">
                {{ $resume->objective }}
            </div>
        </div>
        @endif

        <!-- Educational Attainment Section -->
        @if($resume->educational_attainment)
        <div class="section">
            <div class="section-title">Educational Attainment</div>
            <div class="section-content">
                @php
                    $educationItems = explode("\n", $resume->educational_attainment);
                @endphp
                @foreach($educationItems as $item)
                    @if(trim($item))
                    <div class="education-item">{{ trim($item) }}</div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Training Seminars Section -->
        @if($resume->training_seminars)
        <div class="section">
            <div class="section-title">Training & Seminars</div>
            <div class="section-content">
                @php
                    $trainingItems = explode("\n", $resume->training_seminars);
                @endphp
                @foreach($trainingItems as $item)
                    @if(trim($item))
                    <div class="training-item">{{ trim($item) }}</div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Work Experience Section -->
        @if($resume->work_experience)
        <div class="section">
            <div class="section-title">Work Experience</div>
            <div class="section-content">
                @php
                    $experienceItems = explode("\n", $resume->work_experience);
                @endphp
                @foreach($experienceItems as $item)
                    @if(trim($item))
                    <div class="experience-item">{{ trim($item) }}</div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>
