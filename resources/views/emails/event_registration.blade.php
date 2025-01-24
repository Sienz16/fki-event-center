<!DOCTYPE html>
<html>
<head>
    <title>Event Registration Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f5f7;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
        h1 {
            background: linear-gradient(120deg, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 32px;
            margin: 0;
            padding: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .success-badge {
            background: linear-gradient(120deg, #059669, #10B981);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            display: inline-block;
            margin: 25px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .event-details {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .event-name {
            color: #6366f1;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .highlight {
            color: #6366f1;
            font-weight: 600;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, #6366f1, #818cf8);
            margin: 30px 0;
            border-radius: 2px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            color: #6366f1;
            text-decoration: none;
            margin: 0 10px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(to right, #6366f1, #818cf8);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .header-text {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding: 15px 0;
        }
        .header-text span {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 32px;
            font-weight: 800;
            text-transform: uppercase;
            background: linear-gradient(120deg, #4F46E5, #7C3AED, #EC4899);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
            padding: 0 10px;
        }
        .header-text span::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #4F46E5, #7C3AED, #EC4899);
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-text">
                <span>FKI EVENT CENTER</span>
            </div>
            <div class="success-badge">✨ Registration Successful</div>
            <h1>Welcome to {{ $eventName }}!</h1>
        </div>

        <div class="event-details">
            <div class="event-name">{{ $eventName }}</div>
            <p>Dear <span class="highlight">{{ $studentName ?? 'Participant' }}</span>,</p>
            <p>🎉 Congratulations! Your registration has been confirmed. We're thrilled to have you join us for this exciting event!</p>
        </div>

        <div class="divider"></div>

        <p>What's Next?</p>
        <ul>
            <li>Save the date in your calendar</li>
            <li>Check your email for updates</li>
            <li>Prepare any necessary materials</li>
            <li>Get ready for an amazing experience!</li>
        </ul>

        <p>We look forward for your attendance at the event ! ✨</p>

        <div class="footer">
            <div class="social-links">
                <a href="https://www.facebook.com/PMFKI.KK/?locale=ms_MY">Facebook</a> |
                <a href="https://www.instagram.com/pmfki/">Instagram</a>
            </div>
            <p>Faculty of Computing and Informatics (FKI)<br>
            Universiti Malaysia Sabah</p>
            <p>&copy; {{ date('Y') }} FKI Event Center. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

