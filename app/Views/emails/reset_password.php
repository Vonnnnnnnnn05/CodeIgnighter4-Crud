<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<p>Hello,</p>
<p>You requested a password reset. Click the link below to set a new password. The link will expire in <?= esc($expires) ?> minutes.</p>
<p><a href="<?= esc($link) ?>"><?= esc($link) ?></a></p>
<p>If you did not request this, ignore this email.</p>
    
    
</body>
</html>