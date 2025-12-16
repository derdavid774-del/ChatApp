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
</head>

<body class="bg-light" id="friends-page">
<div class="container mt-4" style="max-width: 800px;">
        <div class="justify-content-between align-items-center mb-3">
        <h1>Friends</h1>
        <div class="btn-group">
            <a href="logout.php" class="btn btn-secondary">&lt; Logout</a>
            <a href="settings.php" class="btn btn-secondary">Edit profile</a>
        </div>
    </div>
    <hr>

    <div class="list-group mb-4" id="friend-list">
        <div class="list-group-item text-center text-muted">
            You have no friends.
        </div>
    </div>
    <hr>

    <h2 id="requests-header" class="h4 mb-3"></h2>
    <div class="list-group mb-4" id="request-list">
    </div>
    <hr>


    <div class="input-group mb-3">
        <input id="friend-request-name" type="text" class="form-control" placeholder="Add Friend to List" list="friend-selector">
        <button class="btn btn-primary" type="button" id="add-friend-btn">Add</button>
    </div>

    <datalist id="friend-selector">
        <?php foreach ($availableUsers as $user): ?>
            <option value="<?= htmlspecialchars($user) ?>">
        <?php endforeach; ?>
    </datalist>
</div>

<div class="modal" id="requestModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Request from <strong id="modal-requester-name"></strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Accept request?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modal-reject-btn">Dismiss</button>
                    <button type="button" class="btn btn-primary" id="modal-accept-btn">Accept</button>
                </div>
            </div>
        </div>
</div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="friends.js"></script>
</body>

</html>