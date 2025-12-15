<?php
    require("start.php");

    $chatPartner = $_GET['friend'] ?? null;

    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    } else if (!$chatPartner) {
        header("Location: friends.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body id="friends-page">
    <h1>Chat with <?=$chatPartner?></h1>
    <nav class="page-navigation">
        <a href="friends.php">&lt; Back</a> |
        <a href="profile.php?friend=<?=urlencode($chatPartner)?>">Profile</a> |
        <a class="link-special" href="friends.php?action=remove&friend=<?=urlencode($chatPartner)?>">Remove Friend</a>
    </nav>
    <hr>
    <ul id="chat" class="container-wide">
        <li class="content">
            <div class="msg">
                <span class="text">No messages received.</span>
            </div>
        </li>
    </ul>
    <hr>

    <form>
        <div class="container-txt-btn">
            <input id="send-msg" name="send-msg" type="text" placeholder="New Message">
            <button for="send-msg" type="submit" class="btn-small">Send</button>
        </div>
    </form>

    <script src="chat.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>