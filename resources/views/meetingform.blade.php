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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Create Meeting</div><br>
    
                        <div class="card-body">
                            <form method="POST" action="{{ url('/createMeeting') }}">
                                @csrf
    
                                <div class="form-group">
                                    <label for="topic">Meeting Topic</label>
                                    <input type="text" class="form-control" id="topic" name="topic" required>
                                </div><br>
    
                                <div class="form-group">
                                    <label for="start_time">Start Time</label>
                                    <input type="date" class="form-control" id="start_time" name="start_time" required>
                                </div><br>
                                <div class="form-group">
                                    <label for="duration">Duration in minutes :</label>
                                    <input type="text" class="form-control" id="duration" name="duration" required>
                                </div><br>
                                <div class="form-group">
                                    <label for="topic">Password :</label>
                                    <input type="text" class="form-control" id="password" name="password" required>
                                </div><br>
    
                                <button type="submit" class="btn btn-primary">Create Meeting</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
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