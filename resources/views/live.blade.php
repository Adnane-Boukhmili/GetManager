<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lives</title>
</head>
<body>
    <body>
        <h1>Join Meeting :</h1>
        <form method="POST" action="{{ url('/auth/zoom') }}">
            @csrf
            <button type="submit" class="button-5">Connect With Zoom</button>
        </form>
    
    <Style>
    body {
        font-family: 'Inter', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    
    .center-container {
        display: flex;
        align-items: center;
    }
    
    .button-5 {
        align-items: center;
        background-clip: padding-box;
        background-color: #0241ff;
        border: 1px solid transparent;
        border-radius: .25rem;
        box-shadow: rgba(0, 0, 0, 0.02) 0 1px 3px 0;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        font-family: system-ui, -apple-system, system-ui, "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 16px;
        font-weight: 600;
        justify-content: center;
        line-height: 1.25;
        margin: 0;
        min-height: 3rem;
        padding: calc(.875rem - 1px) calc(1.5rem - 1px);
        position: relative;
        text-decoration: none;
        transition: all 250ms;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: baseline;
        width: auto;
    }
    
    .button-5:hover,
    .button-5:focus {
        background-color: #092e9d;
        box-shadow: rgba(0, 0, 0, 0.1) 0 4px 12px;
    }
    
    .button-5:hover {
        transform: translateY(-1px);
    }
    
    .button-5:active {
        background-color: #0241ff;
        box-shadow: rgba(0, 0, 0, .06) 0 2px 4px;
        transform: translateY(0);
    }
    
        </style>
</body>
</html>