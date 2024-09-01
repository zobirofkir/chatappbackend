<!DOCTYPE html>
<html>
<head>
    <title>New Message Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            font-size: 18px;
            color: #25d366; /* WhatsApp green */
            font-weight: bold;
            margin-bottom: 20px;
        }
        .message-content {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">New Message Notification</div>
        <div class="message-content">
            <p>You have a new message:</p>
            <p>{{ $messageContent }}</p>
        </div>
    </div>
</body>
</html>
