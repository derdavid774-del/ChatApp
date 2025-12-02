<?php
require("start.php");

if (!isset($_SESSION["user"]) || empty($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$profileName = $_GET['friend'] ?? null;

if ($profileName === null) {
    header("Location: friends.php");
    exit();
}

$profileUser = $service->loadUser($profileName);

if (!$profileUser) {
    header("Location: friends.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Profile of <?=htmlspecialchars($profileName)?></h1>
    <nav class="page-navigation">
        <a href="chat.php?friend=<?= urlencode($profileName) ?>">&lt; Back to Chat</a> |
        <a class="link-special" href="friends.php?action=remove&friend=<?=urlencode($profileName)?>">Remove Friend</a>
    </nav>

    <div class="profile">
        <img src="images/profile.png" alt="Chat App Logo">
        <ul class="container-wide">
            <li class="content">
                <p class="profile-description">
                    <?php
                        $desc = $profileUser->getDescription();
                        echo !empty($desc) ? htmlspecialchars($desc) : "This user has not provided a description yet.";
                    ?>         
                </p>
            </li>
            <li class="content">
                <dl class="profile-information">
                    <dt>Name:</dt>
                    <dd>
                        <?= htmlspecialchars($profileUser->getFirstName()) ?>
                        <?= htmlspecialchars($profileUser->getLastName()) ?>
                    </dd>
                    <dt>Coffee or Tea?</dt>
                    <dd>
                        <?= htmlspecialchars($profileUser->getCoffeeOrTea()) ?>
                    </dd>
                    <dt>Preferred Chat Layout:</dt>
                    <dd>
                        <?= htmlspecialchars($profileUser->getChatLayout()) ?>
                    </dd>
                    <dt>History of Changes:</dt>
                    <dd>
                        <?php
                        $history = $profileUser->getHistory();
                        if (empty($history)) {
                            echo "<li>No changes recorded.</li>";
                        } else {
                            foreach ($history as $entry) {
                                echo "<li>" . htmlspecialchars($entry) . "</li>";
                            }
                        }
                        ?>
                    </dd>
                </dl>
            </li>
        </ul>
    </div>
</body>
</html>