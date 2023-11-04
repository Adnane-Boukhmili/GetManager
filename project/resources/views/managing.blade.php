<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>managing employers</title>
</head>
<body>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        #container {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button.delete {
            background-color: #e74c3c;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
        }

        form label, form input {
            display: block;
            margin: 10px auto;
        }

        input[type="submit"] {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
      <div id="container">
        <h1>Manage Your Employees :</h1>
        @if ($employees->count() > 0)
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            @foreach($employees as $e)
            <tr>
                <td>{{ $e->name }}</td>
                <td>{{ $e->email }}</td>
                <td>
                    <form method="POST" action="{{url('/delete_employee',$e->id)}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    <form method="GET" action="{{url('/edit_employee',$e->id)}}">
                        @csrf
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <h1>You have no employees !</h1>
        @endif
        <h1>Add an Employees :</h1>
        <form method="POST" action="{{ url('/create_employer') }}">
            @csrf
            <label>Name:</label>
            <input type="text" id="name" name="name" required>

            <label>Email:</label>
            <input type="email" id="email" name="email" required>

            <label>Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Add Employee">
        </form>
    </div>
</body>
</html>