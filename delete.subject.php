<?php
    session_start();
    require_once "config.php";
    if(isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        $subjectcode = trim($_POST['txtstudent']);
        $sql = "DELETE FROM tblsubjects WHERE subjectcode = ?";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $subjectcode);
            if(mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    $date = date("m/d/Y");
                    $time = date("h:i:s");
                    $action = "Delete";
                    $module = "Students Management";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $subjectcode, $_SESSION['Username']);
                    if(mysqli_stmt_execute($stmt)){
                        exit(json_encode(['success' => true]));
                    }
                }
            }else {
                $_SESSION['status'] = "Error on delete account!";
                exit(json_encode(['success' => false]));
            }
        }
    }
?>
