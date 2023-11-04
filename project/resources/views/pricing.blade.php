<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pricing</title>
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .range-label {
            font-weight: bold;
        }

        .range-slider {
            width: 100%;
        }

        .total {
            margin-top: 10px;
        }

        .buy-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <div class="container">
        <form action="{{ url('/set_pricing') }}" method="post">
            @csrf
            <p class="range-label">Select the number of employees:</p>
            <input type="range" class="range-slider" id="employeeRange" name="employeeRange" min="0" max="100" step="1" value="0" oninput="this.nextElementSibling.value = this.value">
            <output>0</output> employees
            <div class="total">
                Total: $<span id="totalCost">0</span>
            </div>
            <input type="submit" class="buy-button" value="Buy">
        </form>
    </div>
    <script>
        
        const employeeRange = document.getElementById('employeeRange');
        const totalCost = document.getElementById('totalCost');
    
        employeeRange.addEventListener('input', () => {
            const numberOfEmployees = parseInt(employeeRange.value, 10);
            const costPerEmployee = 2;
            const total = numberOfEmployees * costPerEmployee;
            totalCost.innerText = total;
        });
    </script>
</body>
</html>