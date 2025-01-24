<!doctype html>
<html>
<head>
    <style>
        /* Define custom fonts */
        @font-face {
            font-family: 'Montserrat';
            src: url({{ public_path('fonts/Montserrat-Regular.ttf') }}) format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Montserrat';
            src: url({{ public_path('fonts/Montserrat-Bold.ttf') }}) format("truetype");
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'PlayfairDisplay';
            src: url({{ public_path('fonts/PlayfairDisplay-Regular.ttf') }}) format("truetype");
            font-weight: normal;
        }

        @page {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Common styles */
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .certificate-background {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Portrait styles */
        .portrait .certificate-container {
            background-image: url('{{ $template_path }}');
        }

        .portrait .student-name {
            position: absolute;
            top: 38%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            font-family: 'PlayfairDisplay', serif;
            text-transform: uppercase;
        }

        .portrait .matric-no {
            position: absolute;
            top: 44%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1px;
        }

        .portrait .event-name {
            position: absolute;
            top: 53%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
        }

        .portrait .event-date {
            position: absolute;
            top: 72%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.3em;
            color: #333;
        }

        .portrait .unique-code {
            position: absolute;
            bottom: 2%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.5em;
            color: #666;
        }

        /* Landscape styles */
        .landscape .certificate-container {
            background-image: url('{{ $template_path }}');
        }

        .landscape .student-name {
            position: absolute;
            top: 38%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2em;
            font-weight: bold;
            color: #333;
            font-family: 'PlayfairDisplay', serif;
            text-transform: uppercase;
        }

        .landscape .matric-no {
            position: absolute;
            top: 44%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1px;
        }

        .landscape .event-name {
            position: absolute;
            top: 53%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
        }

        .landscape .event-date {
            position: absolute;
            top: 74%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1em;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        .landscape .unique-code {
            position: absolute;
            bottom: 2%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.5em;
            color: #666;
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="{{ $event->cert_orientation }}">
    <div class="certificate-container">
        <!-- Background Image -->
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents($template_path)) }}" class="certificate-background">
        
        <div class="content">
            <!-- Student Name -->
            <div class="student-name">{{ $student_name }}</div>

            <!-- Matric Number -->
            <div class="matric-no">{{ $matric_no }}</div>

            <!-- Event Name -->
            <div class="event-name">{{ $event_name }}</div>

            <!-- Event Date -->
            <div class="event-date">{{ $event_date }}</div>

            <!-- Unique Code -->
            <div class="unique-code">Kod Unik: {{ $unique_code }}</div>
        </div>
    </div>
</body>
</html>
