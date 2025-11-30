<?php
require("start.php");
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
    <h1>Profile of Tom</h1>
    <nav class="page-navigation">
        <a href="chat.php">&lt; Back to Chat</a> |
        <a class="link-special" href="friends.php">Remove Friend</a>
    </nav>

    <div class="profile">
        <img src="images/profile.png" alt="Chat App Logo">
        <ul class="container-wide">
            <li class="content">
                <p class="profile-description">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum.
                    Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor
                    sit
                    amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna
                    aliquyam
                    erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd
                    gubergren, no
                    sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
            </li>
            <li class="content">
                <dl class="profile-information">
                    <dt>Coffee or Tea?</dt>
                    <dd>Tea</dd>
                    <dt>Name:</dt>
                    <dd>Thomas</dd>
                </dl>
            </li>
        </ul>
    </div>
</body>

</html>