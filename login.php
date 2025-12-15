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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light d-flex justify-content-center pt-5">
    <div class="text-center">
        <img src="images/chat.png" alt="Chat App Logo" class="rounded-circle mb-5" width="175">
        <div class="card">
            <div class="card-body p-5">
                <h2 class="h4 mb-3 text-center">Please sign in</h2>
                <form id="signIn" action="login.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                </form>
        
                <div class="btn-group w-100">
                    <a href="register.php" class="btn btn-secondary btn-lg">Register</a>
                    <button class="btn btn-primary btn-lg" form="signIn">Login</button>
                </div>
            </div>
        </div> 

        <?php if (isset($error)) { ?>
                <div class="error"> <?= $error ?> </div>
        <?php } ?>
    </div>
</body>

</html>