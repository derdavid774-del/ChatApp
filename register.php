<?php
require("start.php");
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <img src="images/user.png" alt="Chat App Logo">

        <h1>Register yourself</h1>
        <form id="register" action="friends.php" method="get">
            <fieldset class="data">
                <legend>Register</legend>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password"
                    required>
            </fieldset>
        </form>

        <div class="container-btn">
            <form action="login.php" method="get">
                <button>Cancel</button>
            </form>
            <button class="btn-blue" form="register">Create Account</button>
        </div>
    </div>
</body>

</html>