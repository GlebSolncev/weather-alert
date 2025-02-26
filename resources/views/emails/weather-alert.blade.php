<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background: #007bff;
            color: #ffffff;
            padding: 15px;
            font-size: 24px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            background: #007bff;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">Alert notification</div>
    <div class="content">
        <p><b>Warning! {{ $location }}</b></p>
        <p>{{ $weatherData['uv'] }}</p>
        <p>{{ $weatherData['precipitation'] }}</p>
        <a href="{{ url('/') }}" class="button" style="color:white">Show</a>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Weather alert!
    </div>
</div>
</body>
</html>

