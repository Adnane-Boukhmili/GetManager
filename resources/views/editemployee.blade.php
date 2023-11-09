<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>editing_employer</title>
</head>
<body>
    <h1>Update Employeer : </h1>
    <form method="POST" action="{{ url('/update_employee',$e->id) }}">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" id="name" name="name" value="{{ $e->name }}" required>

        <label>Email:</label>
        <input type="email" id="email" name="email" value="{{ $e->email }}" required>

        <label>Password:</label>
        <input type="password" id="password" name="password" value="{{ $e->password }}" required>

        <input type="submit" value="Update Employee">
    </form>
</body>
</html>