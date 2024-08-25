<?php
// Start session and include necessary files
session_start();
require_once "config.php";

// Check if form is submitted
if(isset($_POST['btnsubmit'])){
    // Validate and sanitize input data
    $studentNumber = $_GET['studentnumber'];
    $subjectCode = $_GET['subjectcode'];
    $grade = $_POST['cmbChangeGrade'];

    // Update grade in tblgrades
    $sql_update_grade = "UPDATE tblgrades SET grade = ? WHERE studentnumber = ? AND subjectcode = ?";
    if($stmt_update_grade = mysqli_prepare($link, $sql_update_grade)){
        mysqli_stmt_bind_param($stmt_update_grade, "sss", $grade, $studentNumber, $subjectCode);
        if(mysqli_stmt_execute($stmt_update_grade)){
            // Insert log into tbllogs
            $date = date("m/d/Y");
            $time = date("h:i:s");
            $action = "Update";
            $module = "Grades Management";
            $performedby = $_SESSION['Username'];
            $sql_log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt_log = mysqli_prepare($link, $sql_log)){
                mysqli_stmt_bind_param($stmt_log, "ssssss", $date, $time, $action, $module, $subjectCode, $performedby);
                if(mysqli_stmt_execute($stmt_log)){
                    $_SESSION['status'] = "Grade Updated Successfully!";
                    header("location: grade.management.php");
                    exit();
                } else {
                    $errorMsg = "Error adding log record.";
                }
            }
        } else {
            $errorMsg = "Error updating grade.";
        }
    }
}

// Fetch student information from URL parameters
$studentNumber = $_GET['studentnumber'];
$course = $_GET['course'];
$year = $_GET['year'];
$name = $_GET['name'];
$subjectCode = $_GET['subjectcode'];
$description = $_GET['description'];
$grade = $_GET['grade'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Update Student Account - AU Student Advising System - AUSAS</title>
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
.logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?studentnumber=' . $studentNumber . '&name=' . urlencode($name) . '&year=' . $year . '&course=' . $course . '&subjectcode=' . $subjectCode . '&description=' . urlencode($description)); ?>" method="POST">
                <?php if (!empty($errorMsg)): ?>
                <div class="error-message"><?php echo $errorMsg; ?></div>
                <?php endif; ?>                
        <div class="form-group dark-bg">
            <label for="txtStudentNumber">Student Number:</label>
            <input type="text" name="txtStudentNumber" value="<?php echo $studentNumber; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label for="txtName">Name:</label>
                <input type="text" name="txtName" value="<?php echo $name; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label for="txtCourse">Course:</label>
                <input type="text" name="txtCourse" value="<?php echo $course; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label for="txtYear">Year:</label>
                <input type="text" name="txtYear" value="<?php echo $year; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label for="txtSubjectCode">Subject Code:</label>
            <input type="text" name="txtSubjectCode" value="<?php echo $subjectCode; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label id="txtDescription">Description:</label>
            <input type="text" name="txtDescription" value="<?php echo $description; ?>" disabled>
        </div>
        <div class="form-group dark-bg">
            <label id="txtGrade">Current Grade:</label>
            <input type="text" name="txtGrade" value="<?php echo $grade; ?>" disabled>
        </div>        
        <div class="form-group">
            <label for="cmbChangeGrade">Grade:</label>
            <div class="group">
                <select name="cmbChangeGrade" required>
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
            <input type="submit" name="btnsubmit" value="Update">
            <a href="grade.management.php">Cancel</a>
        </div>
    </form>
</div>
</main>
  <footer>
    <div class="footer">
      ARELLANO UNIVERSITY <BR>
      2600, Legarda St., Sampaloc, Manila <br>
      Telephone No. 8-734731 <br>
      CopyRight &copy; 2024 
    </div>
  </footer>
    <script>
$(document).ready(function(){
    // Add event listener for select element
    $('select[name="cmbSubjectCode"]').change(function(){
        var subjectCode = $(this).val(); // Get selected subject code
        // Send AJAX request to fetch description
        $.ajax({
            url: 'get-description.php',
            method: 'POST',
            data: {subjectCode: subjectCode},
            success: function(response){
                // Update description input field with fetched description
                $('#txtDescription').val(response);
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText); // Log the response text to console
                console.error(error); // Log the error message to console
            }
        });
    });
});
    </script>
</body>
</html>
