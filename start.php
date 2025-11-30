<?php
spl_autoload_register(function($class) {
    include str_replace('\\', '/', $class) . '.php';
});

session_start();

define('CHAT_SERVER_URL', 'https://online-lectures-cs.thi.de/chat/');
define('CHAT_SERVER_ID', '5929e8ea-f3a6-4ae3-b9b4-c0d0971e0f03');

$service = new Utils\BackendService(CHAT_SERVER_URL, CHAT_SERVER_ID);
?>