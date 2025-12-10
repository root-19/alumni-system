<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Certificate</title>
    <style>
        @page { margin: 0; size: A4; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #0d1f18;
            color: #0f3d2e;
        }
        .page {
            width: 210mm;
            height: 297mm;
            padding: 24mm;
            position: relative;
            background: #f8fffb;
            margin: 0 auto;
            overflow: hidden;
            margin-left: -56px;
        }
        .ribbon,
        .frame {
            display: none;
        }
        .wave {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(15, 61, 46, 0.04) 0, transparent 35%),
                              radial-gradient(circle at 80% 10%, rgba(11, 45, 34, 0.06) 0, transparent 30%),
                              radial-gradient(circle at 60% 70%, rgba(15, 61, 46, 0.05) 0, transparent 32%);
            opacity: 0.85;
        }
        .content {
            position: relative;
            z-index: 1;
            max-width: 160mm;
            margin: 0 auto;
            padding: 0;
            text-align: center;
            color: #0f3d2e;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .badge {
            width: 120px;
            height: 120px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            position: relative;
            overflow: hidden;
        }
        .badge::after {
            display: none;
        }
        .badge-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .badge-text {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
        }
        .title {
            font-size: 34px;
            letter-spacing: 4px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 8px 0 6px;
        }
        .subtitle {
            font-size: 12px;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: #2c6b52;
            margin: 0 0 28px;
        }
        .recipient-label {
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #2c6b52;
            font-weight: 600;
            margin: 0 0 6px;
        }
        .recipient-name {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 2px;
            margin: 0 0 16px;
        }
        .body-text {
            max-width: 720px;
            margin: 0 auto 30px;
            color: #1d3b2f;
            line-height: 1.5;
            font-size: 14px;
        }
        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 60px;
            margin: 26px auto 32px;
            max-width: 720px;
            text-align: left;
        }
        .detail-box {
            padding: 4px 0;
        }
        .detail-label {
            font-size: 11px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #2c6b52;
            margin-bottom: 2px;
        }
        .detail-value {
            font-weight: 800;
            font-size: 14px;
            color: #0f3d2e;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 26px;
            padding: 0 6px;
            width: 100%;
        }
        .sign-block {
            text-align: left;
        }
        .sign-line {
            width: 200px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #0f3d2e, transparent);
            margin-bottom: 6px;
        }
        .sign-name {
            font-weight: 800;
            color: #0f3d2e;
            margin: 0;
        }
        .sign-title {
            color: #2c6b52;
            margin: 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="ribbon left"></div>
        <div class="ribbon right"></div>
        <div class="frame"></div>
        <div class="wave"></div>

        <div class="content">
            <div class="badge">
                @if($hasLogo)
                    <img class="badge-logo" src="{{ $logoPath }}" alt="Logo">
                @else
                    <span class="badge-text">{{ strtoupper(substr($schoolName, 0, 2)) }}</span>
                @endif
            </div>
            <div class="title">Certificate</div>
            <div class="subtitle">This certificate is proudly presented for honorable achievement</div>

            <div class="recipient-label">Awarded to</div>
            <div class="recipient-name">{{ $studentName }}</div>

            <p class="body-text">
                In recognition of the successful completion of the training titled
                <strong>{{ $trainingTitle }}</strong>. The participant demonstrated the required proficiency
                and passed the final assessment.
            </p>

            <div class="details">
                <div class="detail-box">
                    <div class="detail-label">Issued by</div>
                    <div class="detail-value">{{ $schoolName }}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label">Completion date</div>
                    <div class="detail-value">{{ $completionDate }}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label">Assessment score</div>
                    <div class="detail-value">{{ $percentage }}% (Passed)</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label">Certificate ID</div>
                    <div class="detail-value">#{{ $attempt->id }}</div>
                </div>
            </div>

            <div class="footer">
                <div class="sign-block">
                    <div class="sign-line"></div>
                    <p class="sign-name">{{ $schoolName }}</p>
                    <p class="sign-title">Issued By</p>
                </div>
                <div class="sign-block" style="text-align:right">
                    <div class="sign-line"></div>
                    <p class="sign-name">{{ $studentName }}</p>
                    <p class="sign-title">Training Administrator</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
