<?php
    include_once "connect.php";
    $db = new Database();
    $db->connect();
    $db->getAllItemDetailsFromDB();
    $db->disconnect();
?>
