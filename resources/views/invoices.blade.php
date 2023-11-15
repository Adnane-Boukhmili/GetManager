<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <style>
                    
                    .card {
                        
                        background-color: black;
                        color: white;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        padding: 20px;
                        width: 45%; 
                        margin: 10px;
                        display: inline-block;
                        text-align: center;
                        text-decoration: none;
                    }
            
                    .card:hover {
                        cursor: pointer; 
                    }
            
                    .card h2 {
                        font-size: 24px;
                    }
                </style>
            <a href="{{ route('stripe.invoices') }}" class="card">
                <h2>Stripe's</h2>
            </a>
            
            <a href="{{ route('paypal.invoices') }}" class="card">
                <h2>Paypal's</h2>
            </a>
            
            </div>
        </div>
    </div>
</body>
</html>