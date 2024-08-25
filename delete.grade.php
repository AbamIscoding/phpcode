<?php
session_start();
require_once "config.php";

if(isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    $subjectcode = trim($_POST['txtsubjectcode']);
    $studentnumber = $_POST['Studentnumber']; // Retrieve student number from POST request

    // Verify that the subject code belongs to the specific student by checking it in tblgrades
    $sql_verify = "SELECT * FROM tblgrades WHERE subjectcode = ? AND studentnumber = ?";
    if($stmt_verify = mysqli_prepare($link, $sql_verify)) {
        mysqli_stmt_bind_param($stmt_verify, "ss", $subjectcode, $studentnumber);
        if(mysqli_stmt_execute($stmt_verify)) {
            $result_verify = mysqli_stmt_get_result($stmt_verify);
            if(mysqli_num_rows($result_verify) > 0) {
                // Delete the subject code from tblgrades
                $sql_delete = "DELETE FROM tblgrades WHERE subjectcode = ? AND studentnumber = ?";
                if($stmt_delete = mysqli_prepare($link, $sql_delete)) { 
                    mysqli_stmt_bind_param($stmt_delete, "ss", $subjectcode, $studentnumber);
                    if(mysqli_stmt_execute($stmt_delete)) {
                        // Log the deletion action
                        $sql_log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                        if($stmt_log = mysqli_prepare($link, $sql_log)){
                            $date = date("Y-m-d");
                            $time = date("H:i:s");
                            $action = "Delete";
                            $module = "Grades Management";
                            $performedby = $_SESSION['Username']; // Correct the session variable name
                            mysqli_stmt_bind_param($stmt_log, "ssssss", $date, $time, $action, $module, $studentnumber, $performedby); // Change $subjectcode to $studentnumber
                            if(mysqli_stmt_execute($stmt_log)){
                                echo "Success"; // Notify AJAX request of successful deletion
                            } else {
                                echo "Error logging the action"; // Notify AJAX request of logging error
                            }
                        } else {
                            echo "Error preparing log statement"; // Notify AJAX request of log statement preparation error
                        }
                    } else {
                        echo "Error deleting grade"; // Notify AJAX request of deletion error
                    }
                } else {
                    echo "Error preparing delete statement"; // Notify AJAX request of delete statement preparation error
                }
            } else {
                echo "Subject code does not belong to the specified student"; // Notify AJAX request if subject code doesn't match student number
            }
        } else {
            echo "Error executing verification query: " . mysqli_error($link); // Notify AJAX request of verification query execution error
        }
    } else {
        echo "Error preparing verification statement"; // Notify AJAX request of verification statement preparation error
    }
}
?>
