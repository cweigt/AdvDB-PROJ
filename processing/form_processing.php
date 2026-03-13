<?php
    require_once('../database/db_connect.php');

    //store everything in variables for processing and cleaning
    //real_escape_string preventing input injection
    $title = $mysqli->real_escape_string($_POST['title'] ?? 'Untitled');
    $description = $mysqli->real_escape_string($_POST['description'] ?? '');

    //file upload logic
    $targetDir = "../uploads/";

    //grabbig file name
    $fileName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . $fileName; //location of the photo

    //now we have to move the file to uploads, store the string to file into database
    //if we store the image in the database directly, it would become very slow and large
    //move_uploaded_file is a built-in function... 2 params: from, to
    if(!empty($fileName)) {
        if(move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            //SQL command to insert these variables into the table
            //don't need to insert PK because of auto-increment
            //use backticks so that SQL knows that we're not using reserved keywords
            $sql = "INSERT INTO posts (`photo`, `title`, `description`)
            VALUES ('$targetFilePath', '$title', '$description')";

            //running the query using mysqli
            if($mysqli->query($sql)) {
                header("Location: ../pages/index.php");
                exit(); //to stop the script
            }else {
                echo "Database error: " . $mysqli->error;
            }
        }else {
            echo "There is a problem uploading your file to the folder, please try again.";
        }
    }else {
        echo "Please select a photo to upload.";
    }

?>