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
</style>
</head>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
<body>
<div class="container">
    <h2>Update Subject</h2>
<?php
require_once "config.php";
include("session.checker.php");
if(isset($_POST['btnsubmit'])){
    $sql = "UPDATE tblsubjects SET description = ?, unit = ?, course = ?, prerequisite1 = ?, prerequisite2 = ?, prerequisite3 = ? WHERE subjectcode = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "sssssss", $_POST['txtdescription'], $_POST['cmbunit'], $_POST['cmbcourse'], $_POST['cmbprerequisite1'], $_POST['cmbprerequisite2'], $_POST['cmbprerequisite3'], $_GET['Username']);
        if(mysqli_stmt_execute($stmt)){
            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
              $date = date("m/d/Y");
              $time = date("h:i:s");
              $action = "Update";
              $module = "Subjects Management";
              mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['Username'], $_SESSION['Username']);
              if(mysqli_stmt_execute($stmt)) {
                $_SESSION['status'] = "Subject Updated Successfully!";
                header("location: subject.management.php");
                exit();
                    } else {
                        echo "<font color = 'red'>Error on insert log statement</font>";
                    }
                }
            }else{
                echo "<font color = 'red'>Error on update statement.</font>";
            }
        }
}else{
    if(isset($_GET['Username']) && !empty(trim($_GET['Username']))){
        $sql = "SELECT * FROM tblsubjects WHERE subjectcode = ?";
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
            <label for="username">Subject Code:</label>
            <input type="text" name="username" value="<?php echo $account['subjectcode']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="txtdescription">Description:</label>
            <input type="text" name="txtdescription" value="<?php echo $account['description']; ?>" required>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Unit:</label>
            <input type="text" name="username" value="<?php echo $account['unit']; ?>" readonly>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Course:</label>
            <input type="text" name="username" value="<?php echo $account['course']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="cmbunit">Change Unit:</label>
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
        <div class="form-group dark-bg">
            <label for="username">Current Prerequisite 1:</label>
            <input type="text" name="username" value="<?php echo $account['prerequisite1']; ?>" readonly>
        </div>        
        <div class="form-group">
            <label for="cmbprerequisite1">Prerequisite 1:</label> 
            <div class="group">
            <select name="cmbprerequisite1" id="cmbprerequisite1" placeholder="Prerequisite 1">
            <option value="">NONE</option>
            </select>
            </div>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Prerequisite 2:</label>
            <input type="text" name="username" value="<?php echo $account['prerequisite2']; ?>" readonly>
        </div>        
        <div class="form-group">
            <label for="cmbprerequisite2">Prerequisite 2:</label> 
            <div class="group">
            <select name="cmbprerequisite2" id="cmbprerequisite2" placeholder="Prerequisite 2">
            <option value="">NONE</option>
            </select>
            </div>
        </div>
        <div class="form-group dark-bg">
            <label for="username">Current Prerequisite 3:</label>
            <input type="text" name="username" value="<?php echo $account['prerequisite3']; ?>" readonly>
        </div>        
        <div class="form-group">
            <label for="cmbprerequisite3">Prerequisite 3:</label> 
            <div class="group">
            <select name="cmbprerequisite3" id="cmbprerequisite3" placeholder="Prerequisite 3">
            <option value="">NONE</option>
            </select>
            </div>
        </div>        
        <div class="button-container">
            <input type="submit" name="btnsubmit" value="Update">
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
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var cmbCourse = document.querySelector('select[name="cmbcourse"]');
    var cmbPrerequisite1 = document.querySelector('select[name="cmbprerequisite1"]');
    var cmbPrerequisite2 = document.querySelector('select[name="cmbprerequisite2"]');
    var cmbPrerequisite3 = document.querySelector('select[name="cmbprerequisite3"]');

    cmbCourse.addEventListener("change", function() {
        var selectedCourse = cmbCourse.value;
        populatePrerequisites(selectedCourse);
    });

    function populatePrerequisites(course) {
        cmbPrerequisite1.innerHTML = '<option value="">NONE</option>';
        cmbPrerequisite2.innerHTML = '<option value="">NONE</option>';
        cmbPrerequisite3.innerHTML = '<option value="">NONE</option>';

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get-subject-codes.php?course=' + course, true);
        xhr.onload = function() {
            if(xhr.status === 200) {
                var subjectCodes = JSON.parse(xhr.responseText);
                subjectCodes.forEach(function(subject) {
                    var option1 = document.createElement('option');
                    option1.value = subject.subjectcode;
                    option1.textContent = subject.subjectcode;
                    cmbPrerequisite1.appendChild(option1);

                    var option2 = document.createElement('option');
                    option2.value = subject.subjectcode;
                    option2.textContent = subject.subjectcode;
                    cmbPrerequisite2.appendChild(option2);

                    var option3 = document.createElement('option');
                    option3.value = subject.subjectcode;
                    option3.textContent = subject.subjectcode;
                    cmbPrerequisite3.appendChild(option3);
                });
            } else {
                console.error('Error fetching subjects: ' + xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    }
});

    </script>
</body>
</html>
