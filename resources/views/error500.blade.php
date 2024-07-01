<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Internal Server Error</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container img {
            width: 200px;
        }
        .container h1 {
            font-size: 100px;
            margin: 0;
            color: #333;
        }
        .container p {
            font-size: 18px;
            color: #666;
        }
        .container a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        .home-btn {
            background-color: #007bff;
        }
        .reload-btn {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>500</h1>
        <img src="{{ asset('images/broken-computer-500.png') }}" alt="500 Image">
        <p>The server encountered an unexpected condition that prevented it from fulfilling the request.</p>
        <a href="{{ url('/dashboard') }}" class="home-btn">Return to homepage</a>
        <a href="javascript:location.reload();" class="reload-btn">Try to load page again</a>
    </div>
</body>
</html>