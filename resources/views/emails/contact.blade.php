<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Nuevo mensaje desde luissoler.dev</h2>
    <p><strong>Nombre:</strong> {{ $fromName }}</p>
    <p><strong>Email:</strong> {{ $fromEmail }}</p>
    <p><strong>Asunto:</strong> {{ $mailSubject }}</p>
    <hr>
    <p>{{ $body }}</p>
</body>
</html>