<?php
    $mysqli = new mysqli("localhost", "my_user", "my_password", "my_db");

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }
?>