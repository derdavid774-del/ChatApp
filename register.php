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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light d-flex justify-content-center pt-5">
    <div class="text-center">
        <img src="images/user.png" alt="Chat App Logo" class="rounded-circle text-center mb-5" width="175">

        <div class="card">
            <div class="card-body p-5">
                <h1 class="h4 mb-3 text-center">Register yourself</h1>
                <form id="register" method="post">
                    <fieldset class="data">
                        <div class="form-floating mb-3">
                            <label for="username">Username</label>
                            <input type="text" id="username" class="form-control form-control-lg" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-floating mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-floating mb-3">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" id="confirm-password" class="form-control form-control-lg" name="confirm-password" placeholder="Confirm Password"
                            required>
                        </div>
                    </fieldset>
                </form>
                
                <div class="btn-group w-100">
                    <a class="btn btn-secondary btn-lg" href="login.php">Cancel</a>
                    <button class="btn btn-primary btn-lg" form="register">Create Account</button>
                </div>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="error"> <?= $error ?> </div>
        <?php } ?>
    </div>
</body>

</html>