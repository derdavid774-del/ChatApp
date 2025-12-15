<?php
require("start.php");
session_unset();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light d-flex justify-content-center pt-5">
    <div class="text-center w-50">
            <img src="images/logout.png" alt="Chat App Logo" class="rounded-circle mb-5" width="175">
        <div class="card">
            <div class="card-body p-5">
                <h1 class="h4 mb-3 text-center">Logged out...</h1>
                <p class="text-center">See u!</p>
                <a href="login.php" class="btn btn-secondary w-100">Login again</a>
            </div>
        </div>
    </div>
</body>

</html>