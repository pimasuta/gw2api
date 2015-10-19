<?php
    include_once "connect.php";

    if (isset($_GET["id"])) {
        $db = new Database();
        $db->connect();
        $db->getItemHistory($_GET["id"]);
        $db->disconnect();
    }
?>