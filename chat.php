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

<body class="bg-light pt-4" id="friends-page">
    <div class="container-md">
        <h1 class="pb-2">Chat with <?=$chatPartner?></h1>
        <div class="btn-group">
            <a class="btn btn-secondary" href="friends.php">&lt; Back</a>
            <a class="btn btn-secondary" href="profile.php?friend=<?=urlencode($chatPartner)?>">Profile</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeFriendModal">Remove Friend</button>
        </div>

        <div class="card mt-4 mb-4 p-2">
            <ul id="chat" class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="msg">
                        <span class="text-muted">Messages loading...</span>
                    </div>
                </li>
            </ul>
        </div>

        <form>
            <div class="input-group">
                <input id="send-msg" name="send-msg" class="form-control" type="text" placeholder="New Message">
                <button for="send-msg" type="submit" class="btn btn-secondary">Send</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="removeFriendModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeFriendModalLabel">Remove <?=$chatPartner?> as Friend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you really want to end your friendship?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="friends.php?action=remove&friend=<?=urlencode($chatPartner)?>" class="btn btn-primary">Yes, Please!</a>
                </div>
            </div>
        </div>
    </div>
    <script src="chat.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>