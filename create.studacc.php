<!DOCTYPE html>
<html>
<head>
  <title>Add New Student - AU Student Advising System - AUSAS</title>
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
    margin-top: 25px;
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
}   color: black;
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
                if (isset($_POST['btnsubmit'])) {
                    // Check if the username is existing
                    $sql = "SELECT * FROM tblstudent WHERE studentnumber = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $_POST['txtstudent']);
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            if (mysqli_num_rows($result) == 0) {
                                // Insert student information into tblstudents
                                $sql = "INSERT INTO tblstudent (studentnumber, lastname, firstname, middlename, course, yearlevel, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                if ($stmt = mysqli_prepare($link, $sql)) {
                                    $status = "ACTIVE";
                                    $date = date("m/d/Y");
                                    mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['txtstudent'], $_POST['txtlastname'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['cmbcourse'], $_POST['cmbyearlevel'], $_SESSION['Username'], $date);
                                    if (mysqli_stmt_execute($stmt)) {
                                        // Insert student information into tblaccounts
                                        $sql = "INSERT INTO tblaccounts (username, password, usertype, userstatus, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";
                                        if ($stmt = mysqli_prepare($link, $sql)) {
                                            $password = "arellano1938"; // Hash the default password
                                            $usertype = "STUDENT";
                                            mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtstudent'], $password, $usertype, $status, $_SESSION['Username'], $date);
                                            if (mysqli_stmt_execute($stmt)) {
                                                // Insert log into tbllogs
                                                $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                                                if ($stmt = mysqli_prepare($link, $sql)) {
                                                    $time = date("h:i:s");
                                                    $action = "Create";
                                                    $module = "Students Management";
                                                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtstudent']), $_SESSION['Username']);
                                                    if (mysqli_stmt_execute($stmt)) {
                                                        echo "User account added!";
                                                        $_SESSION['status'] = "Student Created Successfully!";
                                                        header("location: student.account.php");
                                                        exit();
                                                    } else {
                                                        echo "Error adding log record.";
                                                    }
                                                }
                                            } else {
                                                echo "Error adding student account record.";
                                            }
                                        }
                                    } else {
                                        echo "Error adding student record.";
                                    }
                                }
                            } else {
                                echo "<p style='color: red;'>Student Number already exists</p>";
                            }
                        } else {
                            echo "Error executing select statement";
                        }
                    }
                }
    ?>
<h3>Fill up this form and submit in order to add a new user</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="txtstudent">Student Number:</label><br>
            <input type="text" name="txtstudent" required><br>
        </div>
        <div class="form-group">
            <label for="txtlastname">Last Name:</label><br>
            <input type="text" name="txtlastname" required><br>
        </div>
        <div class="form-group">
            <label for="txtfirstname">First Name:</label><br>
            <input type="text" name="txtfirstname" required><br>
        </div>
        <div class="form-group">
            <label for="txtmiddlename">Middle Name:</label><br>
            <input type="text" name="txtmiddlename" required><br>
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
        <div class="form-group">
            <label for="cmbyearlevel">Year Level:</label>
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
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="student.account.php">Cancel</a>
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
