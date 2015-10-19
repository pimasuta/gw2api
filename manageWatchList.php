<?php
    include_once "connect.php";

    $db = new Database();
    $db->connect();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db->getAllWatchList();
    } else if ($_POST["cmd"] === "insert") {
        if ($_POST["id"] !== null) {
            $db->insertWatchList($_POST["id"]);
        }
    } else if ($_POST["cmd"] === "delete") {
        if ($_POST["id"] !== null) {
            $db->deleteWatchList($_POST["id"]);
        }
    }
    $db->disconnect();
?>