<!DOCTYPE html>
<html>
<head>
    <title>Reply from BookVerse</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .reply-box {
            background: #dbeafe;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #2563eb;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>BookVerse Customer Support</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $message->name }}</strong>,</p>
            <p>Thank you for contacting BookVerse. Here is our response:</p>
            <div class="reply-box">
                <strong>📝 Admin Reply:</strong>
                <p>{{ nl2br(e($reply)) }}</p>
            </div>
            <div class="message-box">
                <strong>📧 Your Message:</strong>
                <p><strong>Subject:</strong> {{ $message->subject }}</p>
                <p>{{ nl2br(e($message->message)) }}</p>
            </div>
            <p>Best regards,<br><strong>BookVerse Team</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} BookVerse. All rights reserved.</p>
        </div>
    </div>
</body>
</html>