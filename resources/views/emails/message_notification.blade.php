<!DOCTYPE html>
<html>
<head>
    <title>Message Notification</title>
</head>
<body>
    <h1>New Message Notification</h1>
    <p>{{ $messageContent }}</p>

    @isset($attachmentPath)
        <p>Attachment: <a href="{{ asset('storage/' . $attachmentPath) }}">Download</a></p>
    @endisset
</body>
</html>
