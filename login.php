<?php
require("start.php");

if(isset($_SESSION['user'])){
    header("Location: friends.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($service->login($username, $password)) {
        $_SESSION['user'] = $username; 
        header("Location: friends.php");
        exit(); 
    } else {
        $error = 'Invalid username or password! Please try again.';
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <img src="images/chat.png" alt="Chat App Logo">

        <h1>Please sign in</h1>
        <form id="signIn" action="login.php" method="POST">
            <fieldset class="data">
                <legend>Login</legend>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </fieldset>
        </form>

        <?php if (isset($error)) { ?>
                <div class="error"> <?= $error ?> </div>
        <?php } ?>

        <div class="container-btn">
            <form action="register.php" method="get">
                <button>Register</button>
            </form>
            <button class="btn-blue" form="signIn">Login</button>
        </div>
    </div>
</body>

</html>