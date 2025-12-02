<?php
    require("start.php");

    if (isset($_SESSION['user'])) {
        if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['friend'])) {
            $friendToRemove = $_GET['friend'];
            $service->removeFriend($friendToRemove);

            header("Location: friends.php");
            exit();
        }

        if (isset($_POST['acceptBtn'])) {
            $service->friendAccept($_POST['requestee']);
        } else if (isset($_POST['rejectBtn'])) {
            $service->friendDismiss($_POST['requestee']);
        } else if (isset($_POST['addFriendBtn']) && $_POST['friendRequestName'] !== '') {
            $service->friendRequest($_POST['friendRequestName']);
        }
    } else {
        header("Location: login.php");
    }
?> 

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="style.css">
</head>

<body id="friends-page">
    <h1>Friends</h1>
    <nav class="page-navigation">
        <a href="logout.php">&lt; Logout</a> |
        <a href="settings.php">Settings</a>
    </nav>
    <hr>

    <ul class="container-wide">
        <li class="content">
            <span class="friend-user"><a href="chat.php">You have no friends.</a></span>
        </li>
    </ul>
    <hr>

    <h2>New Requests</h2>
    <div class="friend-request">
        <form method="post">
            <ol></ol>
        </form>
    </div>
    <hr>

    <form>
        <div class="container-txt-btn">
            <form method="post">    
                <input id="friend-request-name" name="friendRequestName" type="text" placeholder="Add Friend to List" list="friend-selector">
                <button id="add-friend-btn" name="addFriendBtn" type="button" class="btn-small" >Add</button>
            </form>
 
        </div>
    </form>

    <datalist id="friend-selector"></datalist>
    <script src="friends.js"></script>
</body>

</html>