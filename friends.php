<?php
    require("start.php");

    $allUsers = $service->loadUsers();
    $friends = $service->loadFriends();
    $existingFriendNames = [];
    
    foreach ($friends as $friend) {
        $existingFriendNames[] = $friend->getUsername();
    }

    $availableUsers = array_filter($allUsers, function($user) use ($existingFriendNames) {
        return $user !== $_SESSION['user'] && !in_array($user, $existingFriendNames);
    });

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['action']) && $data['action'] === 'accept') {
            $service->friendAccept($data['username']);
        } 
        elseif (isset($data['action']) && $data['action'] === 'reject') {
            $service->friendDismiss($data['username']);
        }
        else {
            $service->friendRequest($data['username']);
        }
        http_response_code(204);
        exit();
    }

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
            <span class="friend-user"><p>You have no friends.</p></span>
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

    <datalist id="friend-selector">
        <?php foreach ($availableUsers as $user): ?>
            <option value="<?= htmlspecialchars($user) ?>">
        <?php endforeach; ?>
    </datalist>

    <script src="friends.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>