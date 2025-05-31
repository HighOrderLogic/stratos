<?php

// Set the content type to HTML
header('Content-Type: text/html; charset=utf-8');

// Output a simple HTML page
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Page</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>This is a simple HTML page served by a PHP script.</p>
        <h2>Our Team</h2>
        <div>
            <h3>John Doe</h3>
            <p>Role: Lead Developer</p>
            <p>Bio: John is a passionate developer with 10 years of experience.</p>
        </div>
        <div>
            <h3>Jane Smith</h3>
            <p>Role: Project Manager</p>
            <p>Bio: Jane keeps our projects on track and ensures client satisfaction.</p>
        </div>
        <div>
            <h3>Mike Brown</h3>
            <p>Role: UI/UX Designer</p>
            <p>Bio: Mike creates stunning and user-friendly interfaces.</p>
        </div>
    </div>
</body>
</html>
HTML;

?>