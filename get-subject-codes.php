<?php
// Include your database connection
require_once "config.php";

// Check if course parameter is set and not empty
if(isset($_GET['course']) && !empty($_GET['course'])) {
    $course = $_GET['course'];

    // Fetch subject codes based on the selected course
    $sql = "SELECT subjectcode FROM tblsubjects WHERE course = ?";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $course);
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $subjectCodes = array();
            while($row = mysqli_fetch_assoc($result)) {
                $subjectCodes[] = array(
                    "subjectcode" => $row['subjectcode'],
                );
            }
            // Output subject codes as JSON
            echo json_encode($subjectCodes);
        } else {
            echo json_encode(array("error" => "Error executing SQL statement"));
        }
    } else {
        echo json_encode(array("error" => "Error preparing SQL statement"));
    }
} else {
    echo json_encode(array("error" => "Course parameter not set"));
}
?>
