<!doctype html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .certificate-container {
            background-image: url('{{ storage_path("app/public/{$event->cert_template}") }}');
            background-size: cover;
            width: 100%;
            height: 100%;
            position: relative;
            margin: 0;
        }
        .content {
            position: absolute;
            top: 50%;  /* Start vertically centered */
            left: 50%;  /* Start horizontally centered */
            transform: translate(-50%, -50%);  /* Ensure it's fully centered */
            text-align: center;
            line-height: normal;
            color: #333;
            margin: 0;
            display: flex;
            flex-direction: column;  /* Stack elements vertically */
        }
        h1 {
            font-family: 'Alex Brush', cursive; /* Updated font to Alex Brush */
            font-size: 3.5em;
            line-height: 1;
            margin: 0;
            text-overflow: ellipsis; 
            overflow: hidden; 
            white-space: nowrap;
        }
        p.certifying {
            font-family: 'Montserrat', sans-serif;
        }
        h2.student-name {
            font-family: 'Glacial Indifference', sans-serif;
        }
        p.success {
            font-family: 'Montserrat', sans-serif;
        }
        h2.event-name {
            font-family: 'Gotham', sans-serif;
        }
        p.issue-date {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="content">
            <h1>Certificate of Attendance</h1>
            <p class="certifying">This is to certify that</p>
            <h2 class="student-name">{{ $student_name }}</h2>
            <p class="success">has successfully attended the event</p>
            <h2 class="event-name">{{ $event_name }}</h2>
            <p class="issue-date">on {{ $issue_date }}</p>
            <p>Unique Code: {{ $unique_code }}</p>
        </div>
    </div>
</body>
</html>
