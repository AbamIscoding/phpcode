<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Add New Subject - AU Student Advising System - AUSAS</title>
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
h3 {
    text-align: center;
    padding-bottom: 20px;
}
.container h2 {
    margin-top: 0;
    color: #333;
}

.form-group {
    margin-bottom: 5px;
    margin-top: 5px;
}

.group {    
    width: 520px;
    margin-bottom: 5px;
    margin-top: 20px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    margin-top: 20px;
}

input[type="text"],
select {
    width: calc(100% - 22px);
    padding: 10px;
    margin: -5px 0;
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
    background-color: red;
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
  </style>
</head>
<body>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
  <div class="container">
    <?php
    require_once "config.php";
    include("session.checker.php");
    if (isset($_POST['btnsubmit'])){
        $sql = "SELECT * FROM tblsubjects WHERE subjectcode = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtsubject']);
            if(mysqli_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 0){
                    $sql = "INSERT INTO tblsubjects (subjectcode, description, unit, course, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)){
                        $status = "ACTIVE";
                        $date = date("m/d/Y");
                        mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtsubject'], $_POST['txtdescription'], $_POST['cmbunit'], $_POST['cmbcourse'], $_SESSION['Username'], $date);
                        if(mysqli_stmt_execute($stmt)){
                            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ? ,?)";
                            if($stmt = mysqli_prepare($link, $sql)){
                                $date = date("m/d/Y");
                                $time = date("h:i:s");
                                $action = "Create";
                                $module = "Subject Management";
                                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtsubject']), $_SESSION['Username']);
                                if(mysqli_stmt_execute($stmt)){
                                    header("location: subject.management.php");
                                    $_SESSION['status'] = "Subject created successfully!";
                                    exit();
                                }
                            }else{
                                echo "<font color = 'red'>Error on insert log statement</font>";
                            }
                        }else {
                            echo "<font color = 'red'>Error on insert statement";
                        }
                    }
                }else{
                    echo "<font color = 'red'>Subject code already in use</font>";
                }
            }else{
                echo "<font color = 'red'Error on select statement</font>";
            }
        }
    }
    ?>
<h3>Fill up this form and submit to add a new subject</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="txtsubject">Subject Code:</label><br>
            <input type="text" name="txtsubject" required><br>
        </div>
        <div class="form-group">
            <label for="txtdescription">Description:</label><br>
            <input type="text" name="txtdescription" required><br>
        </div>
        <div class="form-group">
            <label for="cmbunit">Unit:</label>
            <div class="group">
                <select name="cmbunit" required>
                    <option value="">Select Unit</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="cmbcourse">Course:</label>
            <div class="group">
                <select name="cmbcourse" required>
                    <option value="">Select Course</option>
                    <option value="Bachelor of Science in Computer Science">Bachelor of Science in Computer Science</option>
                    <option value="Bachelor of Science in Business Administration">Bachelor of Science in Business Administration</option>
                    <option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>
                    <option value="Bachelor of Science in Tourism Management">Bachelor of Science in Tourism Management</option>
                    <option value="Bachelor of Science in Accountancy">Bachelor of Science in Accountancy</option>
                    <option value="Bachelor of Science in Hospitality Management">Bachelor of Science in Hospitality Management</option>
                    <option value="Bachelor of Science in Pharmacy">Bachelor of Science in Pharmacy</option>
                    <option value="Bachelor of Science in Psychology">Bachelor of Science in Psychology</option>
                    <option value="Bachelor of Science in Midwifery">Bachelor of Science in Midwifery</option>
                    <option value="Bachelor of Science in Radiologic Technology">Bachelor of Science in Radiologic Technology</option>
                    <option value="Bachelor of Science in Physical Therapy">Bachelor of Science in Physical Therapy</option>
                    <option value="Bachelor of Science in Nursing">Bachelor of Science in Nursing</option>
                    <option value="Bachelor of Science Medical Technology">Bachelor of Science Medical Technology</option>
                    <option value="Bachelor of Library and Information Science">Bachelor of Library and Information Science</option>
                </select>
            </div>
        </div>
      <div class="button-container">
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="subject.management.php">Cancel</a>
        </div>
    </form>
  </div>
  <footer>
    <div class="footer">
      ARELLANO UNIVERSITY <BR>
      2600, Legarda St., Sampaloc, Manila <br>
      Telephone No. 8-734731 <br>
      CopyRight &copy; 2024 
    </div>
  </footer>
</body>
</html>
