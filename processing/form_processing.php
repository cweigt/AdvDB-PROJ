<?php
    require_once('../database/db_connect.php');

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header("Location: ../pages/createPost.php");
        exit();
    }

    //store everything in variables for processing and cleaning
    //real_escape_string preventing input injection
    $title = $mysqli->real_escape_string($_POST['title'] ?? 'Untitled');
    $description = $mysqli->real_escape_string($_POST['description'] ?? '');

    //file upload logic
    if (!isset($_FILES['photo'])) {
        echo "No file data received. Make sure your form uses enctype=\"multipart/form-data\".";
        exit();
    }

    $uploadError = $_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($uploadError !== UPLOAD_ERR_OK) {
        $errorMap = [
            UPLOAD_ERR_INI_SIZE   => "The uploaded file is too large (server limit).",
            UPLOAD_ERR_FORM_SIZE  => "The uploaded file is too large (form limit).",
            UPLOAD_ERR_PARTIAL    => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE    => "Please select a photo to upload.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder on the server.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload.",
        ];
        $msg = $errorMap[$uploadError] ?? "Unknown upload error.";
        echo $msg . " (code: " . (int)$uploadError . ")";
        exit();
    }

    $uploadsDirFs = __DIR__ . "/../uploads";
    if (!is_dir($uploadsDirFs)) {
        @mkdir($uploadsDirFs, 0775, true);
    }
    if (!is_dir($uploadsDirFs) || !is_writable($uploadsDirFs)) {
        echo "Uploads folder is missing or not writable: " . htmlspecialchars($uploadsDirFs);
        exit();
    }

    //grabbig file name
    $originalName = (string)($_FILES["photo"]["name"] ?? '');
    $fileName = basename($originalName);
    if ($fileName === '') {
        echo "Please select a photo to upload.";
        exit();
    }

    // basic image validation (reject non-images)
    $tmpName = (string)($_FILES["photo"]["tmp_name"] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        echo "Upload did not complete correctly (missing temp file).";
        exit();
    }
    if (@getimagesize($tmpName) === false) {
        echo "That file doesn't look like a valid image.";
        exit();
    }

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowedExts, true)) {
        echo "Unsupported image type. Allowed: " . implode(", ", $allowedExts);
        exit();
    }

    $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '_', pathinfo($fileName, PATHINFO_FILENAME));
    $safeBase = trim($safeBase, '_');
    if ($safeBase === '') $safeBase = 'image';
    $newFileName = $safeBase . "_" . bin2hex(random_bytes(6)) . "." . $ext;

    $targetFsPath = $uploadsDirFs . "/" . $newFileName;
    $targetFilePath = "../uploads/" . $newFileName; // web path stored in DB (used by pages/index.php)

    //now we have to move the file to uploads, store the string to file into database
    //if we store the image in the database directly, it would become very slow and large
    //move_uploaded_file is a built-in function... 2 params: from, to
    if(!empty($fileName)) {
        if(move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFsPath)) {
            //SQL command to insert these variables into the table
            //don't need to insert PK because of auto-increment
            //use backticks so that SQL knows that we're not using reserved keywords
            $stmt = $mysqli->prepare("INSERT INTO posts (`photo`, `title`, `description`) VALUES (?, ?, ?)");
            if (!$stmt) {
                echo "Database error: " . $mysqli->error;
                exit();
            }
            $stmt->bind_param("sss", $targetFilePath, $title, $description);

            //running the query using mysqli
            if($stmt->execute()) {
                header("Location: ../pages/index.php");
                exit(); //to stop the script
            }else {
                echo "Database error: " . $stmt->error;
            }
        }else {
            echo "There is a problem uploading your file to the folder, please try again.";
        }
    }else {
        echo "Please select a photo to upload.";
    }

?>