<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Your account has been deleted successfully</h2>
    <p> Hi {{ $user->name }}, your account deleted</p>
    <p>Email : <b>{{ $user->email }}</b></p>
    <p>Thanks.</p>
</body>
</html>
