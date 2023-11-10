<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoices</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        #container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 95%; /* Set the desired width here */
            margin: 0 auto; /* Center the container horizontally */
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td a {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        td a:hover {
            background-color: #2980b9;
        }

        h2 {
            color: #333;
            margin-top: 30px;
        }

        .no-invoices {
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Invoices</h1>
        @if ($invoices->count() > 0)
        <table>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Employee Count</th>
                <th>Total Price</th>
                <th>Payment Status</th>
                <th>Invoice</th>
            </tr>
            @foreach($invoices as $i)
            <tr>
                <td>{{ $i->created_at->format('Y-m-d') }}</td>
                <td>{{ $i->type }}</td>
                <td>{{ $i->employee_count }}</td>
                <td>${{ $i->total_price }}</td>
                <td>{{ $i->payment_status }}</td>
                <td><a href="{{ $i->invoice }}" target="_blank">Download</a></td>
            </tr>
            @endforeach
        </table>
        @else
        <h2 class="no-invoices">You have no invoices!</h2>
        @endif
    </div>
</body>
</html>
