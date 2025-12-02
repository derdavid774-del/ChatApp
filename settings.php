<?php
require("start.php");

if (!isset($_SESSION["user"]) || empty($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["user"];
$user = $service->loadUser($username);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user->setFirstName($_POST["name"] ?? "");
    $user->setLastName($_POST["surname"] ?? "");
    $user->setCoffeeOrTea($_POST["preference"] ?? "Neither nor");
    $user->setDescription($_POST["description"] ?? "");
    $user->setChatLayout($_POST["layout"] ?? "");

    $service->saveUser($user); 

    header("Location: friends.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Profile Settings</h1>
    <form id="settings">
        <fieldset class="data">
            <legend>Base Data</legend>
            <div class="container">
                <label for="name">First Name</label>
                <input id="name" name="name" type="text" placeholder="Your name">

                <label for="surname">Last Name</label>
                <input id="surname" name="surname" type="text" placeholder="Your surname">

                <label for="preference">Coffee or Tea?</label>
                <select id="preference" name="preference">
                    <option value="Neither nor" <?php echo ($user->getCoffeeOrTea() == "Neither nor") ? "selected" : ""; ?>>Neither nor</option>
                    <option value="Coffee" <?php echo ($user->getCoffeeOrTea() == "Coffee") ? "selected" : ""; ?>>Coffee</option>
                    <option value="Tea" <?php echo ($user->getCoffeeOrTea() == "Tea") ? "selected" : ""; ?>>Tea</option>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend>Tell Something About You</legend>
            <textarea name="description" placeholder="Leave a comment here"><?php echo ($user->getDescription()); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Prefered Chat Layout</legend>
            <div class="container-radio">
                <input id="oneLine" name="layout" type="radio" value="oneLine">
                        <?php echo ($user->getChatLayout() == "oneLine") ? "checked" : ""; ?>
                <label for="oneLine">Username and message in one line</label><br>
            </div>

            <div class="container-radio">
                <input id="separatedLines" name="layout" type="radio" value="separatedLines">
                        <?php echo ($user->getChatLayout() == "separatedLines") ? "checked" : ""; ?>
                <label for="separatedLines">Username and message in separated lines</label>
            </div>
        </fieldset>
    </form>

    <div class="container-btn">
        <form action="friends.php" method="get">
            <button type="submit">Cancel</button>
        </form>
        <button class="btn-blue" form="settings" type="submit">Save</button>
    </div>
</body>

</html>