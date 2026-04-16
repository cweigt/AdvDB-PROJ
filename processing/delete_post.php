<?php
    require_once('../database/db_connect.php');

    $id    = (int)($_POST['id'] ?? 0);
    $photo = $_POST['photo'] ?? '';

    if ($id <= 0) {
        die("Invalid post ID.");
    }

    // Delete from DB
    $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Remove the uploaded file if it exists
        if (!empty($photo) && file_exists($photo)) {
            unlink($photo);
        }
        header("Location: ../pages/index.php");
        exit();
    } else {
        echo "Error deleting post: " . $mysqli->error;
    }
?>
