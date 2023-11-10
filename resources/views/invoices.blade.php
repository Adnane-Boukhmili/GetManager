<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoices</title>
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
        <h1>Invoices :</h1>
        @if ($invoices->count() > 0)
        <table>
            <tr>
                <th>Date</th>
                <th>Employee Count</th>
                <th>Total Price</th>
                <th>Payment Status</th>
                <th>Invoice</th>
            </tr>
            @foreach($invoices as $i)
            <tr>
                <td>{{ $i->created_at }}</td>
                <td>{{ $i->employee_count }}</td>
                <td>${{ $i->total_price }}</td>
                <td>{{ $i->payment_status }}</td>
                <td><a href="{{ $i->invoice }}">Download</a></td>
            </tr>
            @endforeach
        </table>
        @else
        <h1>You have no Invoices !</h1>
        @endif
    </div>
</body>
</html>