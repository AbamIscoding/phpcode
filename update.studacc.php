<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Update Student Account - AU Student Advising System - AUSAS</title>
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

.container h2 {
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
    <h2>Update Student Account</h2>
    <?php
    require_once "config.php";
    include("session.checker.php");
    if (isset($_POST['btnsubmit'])) {
        $sql = "UPDATE tblstudent SET lastname = ?, firstname = ?, middlename = ?, course = ?, yearlevel = ? WHERE studentnumber = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtlastname'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['cmbcourse'], $_POST['cmbyearlevel'], $_GET['Username']);
                    if(mysqli_stmt_execute($stmt)){
                    $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedBy) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
              $date = date("m/d/Y");
              $time = date("h:i:s");
              $action = "Update";
              $module = "Students Management";
              mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, ($_GET['Username']), $_SESSION['Username']);
              if(mysqli_stmt_execute($stmt)) {
                $_SESSION['status'] = "Student account updated!";
                header("location: student.account.php");
                exit();
                    } else {
                        echo "<font color = 'red'>Error on insert log statement</font>";
                    }
                    }
                }else{
                    echo "<font color = 'red'>Error on update statement.</font>";
                }
            }
    } else {
        if (isset($_GET['Username']) && !empty(trim($_GET['Username']))) {
            $sql = "SELECT * FROM tblstudent WHERE studentnumber = ?";
                        if($stmt = mysqli_prepare($link, $sql)){    
                        mysqli_stmt_bind_param($stmt, "s", $_GET['Username']);
                            if(mysqli_stmt_execute($stmt)){
                                $result = mysqli_stmt_get_result($stmt);
                                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            }else{
                                echo "<font color = 'red'Error on loading account data.</font>";
                            }
                        }
        }
    }
    ?>
    <form action="#" method="POST">
        <div class="form-group dark-bg">
            <label for="username">Student Number:</label>
            <input type="text" name="username" value="<?php echo $account['studentnumber']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="txtlastname">Last Name:</label>
            <input type="text" name="txtlastname" value="<?php echo $account['lastname']; ?>" required>
        </div>
        <div class="form-group">
            <label for="txtfirstname">First Name:</label>
            <input type="text" name="txtfirstname" value="<?php echo $account['firstname']; ?>" required>
        </div>
        <div class="form-group">
            <label for="txtmiddlename">Middle Name:</label>
            <input type="text" name="txtmiddlename" value="<?php echo $account['middlename']; ?>" required>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Course:</label>
            <input type="text" name="username" value="<?php echo $account['course']; ?>" readonly>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Year Level:</label>
            <input type="text" name="username" value="<?php echo $account['yearlevel']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="cmbcourse">Change Course:</label>
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
        <div class="form-group">
            <label for="cmbyearlevel">Change Year Level:</label>
            <div class="group">
                <select name="cmbyearlevel" required>
                    <option value="">Select Year Level</option>
                    <option value="1ST YEAR">1st Year</option>
                    <option value="2ND YEAR">2nd Year</option>
                    <option value="3RD YEAR">3rd Year</option>
                    <option value="4TH YEAR">4th Year</option>
                </select>
            </div>
        </div>
        <div class="button-container">
            <input type="submit" name="btnsubmit" value="Update">
            <a href="student.account.php">Cancel</a>
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
</body>
</html>
