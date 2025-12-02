<?php
    require("start.php");

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['confirm-password'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($username && mb_strlen($username) >= 3 && $service->userExists($username) === false 
            && $password && mb_strlen($password) >= 8 && $password === $password2) {
            if ($service->register($username, $password)) {
                $service->login($username, $password);
                $_SESSION['user'] = $username;
                header("Location: friends.php");
                exit();
            } else {
                $error = 'Register failed due to server error. Please try again later.';
            }
        } else {
            $error = 'Register failed! Please check your input.';
        }
    }
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
        <form id="register" method="post">
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
        
        <?php if (isset($error)) { ?>
                <div class="error"> <?= $error ?> </div>
        <?php } ?>
        
        <div class="container-btn">
            <form action="login.php" method="get">
                <button>Cancel</button>
            </form>
            <button class="btn-blue" form="register">Create Account</button>
        </div>
    </div>
</body>

</html>