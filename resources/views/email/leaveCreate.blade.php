<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
@php
    $editUrl = route('leave.edit', ['leave' => $leave->id]);
@endphp
<body>
    <h2>{{ $userName }} has created a leave request</h2>
    <p>Start date: <b>{{ $leave->start_date }}</b></p>
    <p>End date: <b>{{ $leave->end_date }}</b></p>
    <a href="{{ $editUrl }}" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Edit</a>
</body>
</html>
