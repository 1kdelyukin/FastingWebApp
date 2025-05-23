<!DOCTYPE html>
<html>
<head>
    <title>Fasting Reminder</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hello, {{ $userName }}!</h2>
    <p>This is a reminder that your fasting duration will end at <strong>{{ $endTime }}</strong>.</p>
    <p>If you wish to pause the timer, please do so now.</p>
    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>
