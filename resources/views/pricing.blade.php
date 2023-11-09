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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 300px;
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
    <form action="" method="post">
        @csrf
        @if ($e === 0)
            <p class="range-label">Select the number of employees:</p>
            Each Employer = $2 
            <input type="range" class="range-slider" id="employeeRange" name="employeeRange" min="0" max="300" step="1" value="0" oninput="this.nextElementSibling.value = this.value">
            <output id="employeeCount">0</output> employees
            <div class="total">
                Total: $<span id="totalCost">0</span>
            </div><br>
            <a href="" class="buy-button" id="buyLink">Buy Plan</a>
        @else
        <h2>Next bill: {{ $user->subscription_end_date }}</h2>
        (DailyRate * RemainingDays * AdditionalEmployees)
            <p class="range-label">Select the number of employees:</p>
            <input type="range" class="range-slider" id="employeeRange" name="employeeRange" min="{{ $e }}" max="300" step="1" value="{{ $e }}" oninput="">
            <output id="employeeCount">{{ $e }}</output> employees
            <div class="total">
                Total Price For Next Month: $<span id="totalCost">0</span>
            </div><br>
            
            <a href="" class="buy-button" id="buyLink">Upgrade</a>
        @endif
    </form>
</div>

<script>
    const employeeRange = document.getElementById('employeeRange');
    const totalCost = document.getElementById('totalCost');
    const buyLink = document.getElementById('buyLink');
    const employeeCount = document.getElementById('employeeCount');
    const numberOfEmployees = parseInt(employeeRange.value, 10);
    const costPerEmployee = 2;
    const total = numberOfEmployees * costPerEmployee;
    totalCost.innerText = total;
    employeeCount.innerText = numberOfEmployees;
    const updateBuyLink = (numberOfEmployees) => {
        if (numberOfEmployees !== parseInt("{{ $e }}", 10) && parseInt("{{ $e }}", 10) != 0) {

            buyLink.href = `addemp/${numberOfEmployees}`;
            buyLink.textContent = "Add Employee";
        } else if (numberOfEmployees == parseInt("{{ $e }}", 10) && parseInt("{{ $e }}", 10) != 0) {
            buyLink.href = 'stripe/checkout/upgrade';
            buyLink.textContent = "Upgrade";
        }else {
            buyLink.href =  `payment/${numberOfEmployees}`; 
            buyLink.textContent = "Buy Plan";
}
    };

    employeeRange.addEventListener('input', () => {
        const numberOfEmployees = parseInt(employeeRange.value, 10);
        const costPerEmployee = 2;
        const total = numberOfEmployees * costPerEmployee;
        totalCost.innerText = total;
        employeeCount.innerText = numberOfEmployees;

        updateBuyLink(numberOfEmployees);
    });
</script>

</body>
</html>