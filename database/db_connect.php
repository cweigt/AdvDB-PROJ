<?php
    $mysqli = new mysqli("localhost", "root", "", "wahly");

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }
?>