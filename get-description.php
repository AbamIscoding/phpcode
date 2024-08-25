<?php
// Include necessary files and start session if required
require_once "config.php";

// Check if subject code is provided
if(isset($_POST['subjectCode'])){
    // Sanitize and validate input
    $subjectCode = $_POST['subjectCode'];

    // Query to fetch description based on subject code
    $sql = "SELECT description FROM tblsubjects WHERE subjectcode = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $subjectCode);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $description);
                mysqli_stmt_fetch($stmt);
                // Return description as response
                echo $description;
            } else {
                echo "Description not found";
            }
        } else {
            echo "Error executing query";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement";
    }
} else {
    echo "Subject code not provided";
}
?>