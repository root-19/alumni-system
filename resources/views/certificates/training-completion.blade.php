<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');

        .cursive {
          font-family: 'Pinyon Script', cursive;
        }

        .sans {
          font-family: 'Open Sans', sans-serif;
        }

        .bold {
          font-weight: bold;
        }

        .block {
          display: block;
        }

        .underline {
          border-bottom: 1px solid #777;
          padding: 5px;
          margin-bottom: 15px;
        }

        .margin-0 {
          margin: 0;
        }

        .padding-0 {
          padding: 0;
        }

        .pm-empty-space {
          height: 40px;
          width: 100%;
        }

        body {
          padding: 20px 0;
          background: #ccc;
        }

        .pm-certificate-container {
          position: relative;
          width: 800px;
          height: 600px;
          background-color: #618597;
          padding: 30px;
          color: #333;
          font-family: 'Open Sans', sans-serif;
          box-shadow: 0 0 5px rgba(0, 0, 0, .5);
        }

        .outer-border {
          width: 794px;
          height: 594px;
          position: absolute;
          left: 50%;
          margin-left: -397px;
          top: 50%;
          margin-top:-297px;
          border: 2px solid #fff;
        }
        
        .inner-border {
          width: 730px;
          height: 530px;
          position: absolute;
          left: 50%;
          margin-left: -365px;
          top: 50%;
          margin-top:-265px;
          border: 2px solid #fff;
        }

        .pm-certificate-border {
          position: relative;
          width: 720px;
          height: 520px;
          padding: 0;
          border: 1px solid #E1E5F0;
          background-color: rgba(255, 255, 255, 1);
          background-image: none;
          left: 50%;
          margin-left: -360px;
          top: 50%;
          margin-top: -260px;
        }

        .pm-certificate-block {
          width: 650px;
          height: 200px;
          position: relative;
          left: 50%;
          margin-left: -325px;
          top: 70px;
          margin-top: 0;
        }

        .pm-certificate-header {
          margin-bottom: 10px;
        }

        .pm-certificate-title {
          position: relative;
          top: 40px;
        }

        .pm-certificate-title h2 {
          font-size: 34px !important;
        }

        .pm-certificate-body {
          padding: 20px;
        }

        .pm-name-text {
          font-size: 20px;
        }

        .pm-earned {
          margin: 15px 0 20px;
        }

        .pm-earned-text {
          font-size: 20px;
        }

        .pm-credits-text {
          font-size: 15px;
        }

        .pm-course-title .pm-earned-text {
          font-size: 20px;
        }

        .pm-course-title .pm-credits-text {
          font-size: 15px;
        }

        .pm-certified {
          font-size: 12px;
        }

        .pm-certified .underline {
          margin-bottom: 5px;
        }

        .pm-certificate-footer {
          width: 650px;
          height: 100px;
          position: relative;
          left: 50%;
          margin-left: -325px;
          bottom: -105px;
        }

        /* New decorative text styles */
        .certificate-motto {
          font-size: 12px;
          color: #2c6b52;
          margin: 5px 0 0;
          font-style: italic;
          letter-spacing: 1px;
        }

        .achievement-quote {
          display: block;
          font-size: 11px;
          color: #d4af37;
          margin-top: 8px;
          font-weight: 600;
          letter-spacing: 1px;
        }

        .recognition-text {
          display: block;
          font-size: 10px;
          color: #5a6c7d;
          margin-top: 8px;
          font-style: italic;
          line-height: 1.4;
        }

        .validation-text {
          display: block;
          font-size: 9px;
          color: #7f8c8d;
          margin-top: 5px;
          font-style: italic;
        }

        /* Decorative Seal Styles */
        .certificate-seal {
          position: absolute;
          top: 20px;
          right: 20px;
          width: 60px;
          height: 60px;
          border: 2px solid #d4af37;
          border-radius: 50%;
          background: radial-gradient(circle, #f4e4c1 0%, #d4af37 50%, #b8941f 100%);
          display: flex;
          align-items: center;
          justify-content: center;
          box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .seal-inner {
          text-align: center;
          color: #2c3e50;
        }

        .seal-star {
          display: block;
          font-size: 16px;
          line-height: 1;
          margin-bottom: 2px;
        }

        .seal-text {
          font-size: 8px;
          font-weight: 900;
          letter-spacing: 1px;
          text-transform: uppercase;
        }

        /* Footer Watermark Styles */
        .footer-watermark {
          position: absolute;
          bottom: 10px;
          left: 0;
          right: 0;
          text-align: center;
          opacity: 0.4;
        }

        .watermark-text {
          font-size: 8px;
          color: #d4af37;
          letter-spacing: 2px;
          text-transform: uppercase;
          font-weight: 600;
        }
    </style>
</head>
<body>
  <div class="container pm-certificate-container">
    <div class="outer-border"></div>
    <div class="inner-border"></div>
    
    <div class="pm-certificate-border col-xs-12">
      <!-- Decorative Seal -->
      <div class="certificate-seal">
        <div class="seal-inner">
          <span class="seal-star">★</span>
          <span class="seal-text">CERTIFIED</span>
        </div>
      </div>
      
      <div class="row pm-certificate-header">
        <div class="pm-certificate-title cursive col-xs-12 text-center">
          <h2>{{ $schoolName }} Certificate of Completion</h2>
          <p class="certificate-motto">"Excellence in Learning • Achievement in Practice"</p>
        </div>
      </div>

      <div class="row pm-certificate-body">
        
        <div class="pm-certificate-block">
            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                <div class="pm-certificate-name underline margin-0 col-xs-8 text-center">
                  <span class="pm-name-text bold">{{ $studentName }}</span>
                </div>
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
              </div>
            </div>          

            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                <div class="pm-earned col-xs-8 text-center">
                  <span class="pm-earned-text padding-0 block cursive">has successfully completed</span>
                  <span class="pm-credits-text block bold sans">{{ $trainingTitle }}</span>
                  <span class="achievement-quote">"Dedication • Knowledge • Success"</span>
                </div>
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                <div class="col-xs-12"></div>
              </div>
            </div>
            
            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                <div class="pm-course-title col-xs-8 text-center">
                  <span class="pm-earned-text block cursive">and demonstrated excellence in all required competencies</span>
                  <span class="recognition-text">This achievement reflects commitment to professional growth and continuous improvement</span>
                </div>
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
              </div>
            </div>

            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
                <div class="pm-course-title underline col-xs-8 text-center">
                  <span class="pm-credits-text block bold sans">Certificate ID: #{{ $attempt->id }}</span>
                  <span class="validation-text">Verified on {{ $completionDate }}</span>
                </div>
                <div class="col-xs-2"><!-- LEAVE EMPTY --></div>
              </div>
            </div>
        </div>       
        
        <div class="col-xs-12">
          <div class="row">
            <div class="pm-certificate-footer">
                <div class="col-xs-4 pm-certified col-xs-4 text-center">
                  <span class="pm-credits-text block sans">{{ $schoolName }}</span>
                  <span class="pm-empty-space block underline"></span>
                  <span class="bold block">Training Administrator</span>
                </div>
                <div class="col-xs-4">
                  <!-- LEAVE EMPTY -->
                </div>
                <div class="col-xs-4 pm-certified col-xs-4 text-center">
                  <span class="pm-credits-text block sans">Date Completed</span>
                  <span class="pm-empty-space block underline"></span>
                  <span class="bold block">{{ $completionDate }}</span>
                </div>
            </div>
          </div>
        </div>

      </div>

    </div>
    
    <!-- Footer Watermark -->
    <div class="footer-watermark">
      <span class="watermark-text">Alumni Training Portal • Excellence in Professional Development</span>
    </div>
  </div>
</body>
</html>
