<?php
// Start session and include necessary files
session_start();
require_once "config.php";

// Initialize error message variable
$errorMsg = "";

// Check if form is submitted
if(isset($_POST['btnsubmit'])){
    // Validate and sanitize input data
    $studentNumber = $_GET['studentnumber'];
    $subjectCode = $_POST['cmbSubjectCode'];
    $grade = $_POST['cmbGrade'];

    // Check if student already has a grade for the subject code
    $sql_check_grade = "SELECT * FROM tblgrades WHERE studentnumber = ? AND subjectcode = ?";
    if($stmt_check_grade = mysqli_prepare($link, $sql_check_grade)){
        mysqli_stmt_bind_param($stmt_check_grade, "ss", $studentNumber, $subjectCode);
        if(mysqli_stmt_execute($stmt_check_grade)){
            mysqli_stmt_store_result($stmt_check_grade);
            if(mysqli_stmt_num_rows($stmt_check_grade) > 0){
                $errorMsg = "Student already has a grade for this subject code";
            }
        } else {
            $errorMsg = "Error checking for existing grade";
        }
    }

    // If no error, continue with inserting the grade
    if(empty($errorMsg)) {
        // Continue with inserting the grade if no existing grade found

        // Check if all required fields are filled
        if(empty($subjectCode) || empty($grade)){
            $errorMsg = "Subject Code and Grade are required.";
        } else {
            // Insert grade into tblgrades with encoded by and date encoded
            $encodedBy = $_SESSION['Username'];
            $dateEncoded = date("m/d/Y");

            $sql = "INSERT INTO tblgrades (studentnumber, subjectcode, grade, encodedby, dateencoded) VALUES (?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sssss", $studentNumber, $subjectCode, $grade, $encodedBy, $dateEncoded); // Fix variable names here
                if(mysqli_stmt_execute($stmt)){
                    // Insert log into tbllogs
                    $date = date("m/d/Y");
                    $time = date("h:i:s");
                    $action = "Add";
                    $module = "Grades Management";
                    $performedby = $_SESSION['Username'];
                    $sql_log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt_log = mysqli_prepare($link, $sql_log)){
                        mysqli_stmt_bind_param($stmt_log, "ssssss", $date, $time, $action, $module, $studentNumber, $performedby);
                        if(mysqli_stmt_execute($stmt_log)){
                            $_SESSION['status'] = "Grade Added Successfully!";
                            header("location: grade.management.php");
                            exit();
                        } else {
                            $errorMsg = "Error adding log record.";
                        }
                    }
                } else {
                    $errorMsg = "Error adding grade record.";
                }
            }
                    }
                }
            }

// Fetch student information from URL parameters
$studentNumber = $_GET['studentnumber'];
$course = $_GET['course'];
$year = $_GET['year'];
$name = $_GET['name'];

// Fetch subject codes for the selected course from tblsubjects
$sql_subjects = "SELECT subjectcode, description FROM tblsubjects WHERE course = ?";
if($stmt_subjects = mysqli_prepare($link, $sql_subjects)){
    mysqli_stmt_bind_param($stmt_subjects, "s", $course);
    if(mysqli_stmt_execute($stmt_subjects)){
        $result_subjects = mysqli_stmt_get_result($stmt_subjects);
    } else {
        $errorMsg = "Error fetching subjects.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<title>Add Grade - AU Student Advising System - AUSAS</title>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #d9d9d9;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
header, footer {
    background-color: #0020c2;
    color: white;
    text-align: center;
    padding: 20px 0;
}
.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
header{
    display: flex;
}

.container h3 {
    margin-top: 0;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.group {    
    width: 520px;
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    margin-top: 10px;
}

input[type="text"],
select {
    width: calc(100% - 22px);
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

input[type="submit"],
a {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
}

input[type="submit"] {
    background-color: #1DB954;
}

a {
    background-color: #333;
}

a:hover {
    background-color: #4CAF50;
}

.error-message {
    color: red;
    margin-top: 5px;
}

.success-message {
    color: green;
    margin-top: 5px;
}

.button-container {
    text-align: center;
}

.dark-bg input[type="text"] {
    background-color: #C8C8C8;
    color: black;
}
main {
    flex: 1; /* Ensures main content takes up remaining vertical space */
}
.logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
    }
</style>
</head>
<body>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
  <main>    
<div class="container">
    <h3>Fill up this form and submit in order to add a new grade</h3>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?studentnumber=' . $studentNumber . '&name=' . $name . '&year=' . $year . '&course=' . $course ); ?>" method="POST">
                <?php if (!empty($errorMsg)): ?>
                <div class="error-message"><?php echo $errorMsg; ?></div>
                <?php endif; ?>                
        <div class="form-group dark-bg">
                <label>Student Number:</label>
                <input type="text" name="txtStudentNumber" value="<?php echo $studentNumber; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
                <label>Name:</label>
                <input type="text" name="txtName" value="<?php echo $name; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
               <label>Course:</label>
                <input type="text" name="txtCourse" value="<?php echo $course; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label>Year:</label>
                <input type="text" name="txtYear" value="<?php echo $year; ?>" disabled>  
        </div>
        <div class="form-group dark-bg">
            <label>Subject Code:</label>
            <div class="group">
                <select name="cmbSubjectCode" required>
                    <option value="">Select Subject Code</option>
                    <?php while($row_subject = mysqli_fetch_assoc($result_subjects)): ?>
                        <option value="<?php echo $row_subject['subjectcode']; ?>"><?php echo $row_subject['subjectcode']; ?></option>
                    <?php endwhile; ?>
                </select>
                </div>
        </div>
        <div class="form-group dark-bg">
                <label>Description:</label>
                <input type="text" name="txtDescription" id="txtDescription" disabled>
        </div>
        <div class="form-group">
                <label>Grade:</label>
            <div class="group">
                <select name="cmbGrade" required>
                    <option value="">Select Grade</option>
                    <option value="1.0">1.0</option>
                    <option value="1.25">1.25</option>
                    <option value="1.5">1.5</option>
                    <option value="1.75">1.75</option>
                    <option value="2.0">2.0</option>
                    <option value="2.25">2.25</option>
                    <option value="2.5">2.5</option>
                    <option value="2.75">2.75</option>
                    <option value="3.0">3.0</option>
                    <option value="5.0">5.0</option>
                </select>
            </div>
        </div>
        <div class="button-container">
            <input type="submit" name="btnsubmit" value="Add">
            <a href="grade.management.php">Cancel</a>
        </div>
    </form>
</div>
</main>
<footer>
        ARELLANO UNIVERSITY <BR>
      2600, Legarda St., Sampaloc, Manila <br>
      Telephone No. 8-734731 <br>
      CopyRight &copy; 2024 
</footer>
    <script>
        $(document).ready(function(){
            // Add event listener for select element
            $('select[name="cmbSubjectCode"]').change(function(){
                var subjectCode = $(this).val(); // Get selected subject code
                // Send AJAX request to fetch description
                $.ajax({
                    url: 'get-description.php', // Replace 'get-description.php' with the actual path to your PHP script
                    method: 'POST',
                    data: {subjectCode: subjectCode},
                    success: function(response){
                        // Update description input field with fetched description
                        $('#txtDescription').val(response);
                    },
                    error: function(xhr, status, error){
                        console.error(error);
                    }
                });
            });
        });
    </script>
</body>
</html>
