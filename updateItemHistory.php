<?php
    include_once "connect.php";

    $db = new Database();
    $db->connect();
    $db->insertAllItemPrices();
    $db->disconnect();
?>