<?php
    include_once "connect.php";

    $db = new Database();
    $db->connect();
    $db->getAllItemDetails();
    $db->disconnect();
?>