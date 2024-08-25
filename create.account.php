<!DOCTYPE html>
<html>
<head>
  <title>Add New Account - AU Student Advising System - AUSAS</title>
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
    margin-bottom: 10px;
    margin-top: -10px;
}
input[type="password"],
select {
    width: calc(100% - 22px);
    padding: 10px;
    margin: 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    margin-top: -10px;
    margin-bottom: 10px;
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
        $sql = "SELECT * FROM tblaccounts WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
            if(mysqli_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 0){
                    $sql = "INSERT INTO tblaccounts (username, password, usertype, userstatus, createdby, datecreated, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)){
                        $status = "ACTIVE";
                        $date = date("m/d/Y");
                        mysqli_stmt_bind_param($stmt, "sssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['cmbaccountType'], $status, $_SESSION['Username'], $date, $time);
                        if(mysqli_stmt_execute($stmt)){
                            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ? ,?)";
                            if($stmt = mysqli_prepare($link, $sql)){
                                $date = date("m/d/Y");
                                $time = date("h:i:s");
                                $action = "Create";
                                $module = "Accounts Management";
                                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtusername']), $_SESSION['Username']);
                                if(mysqli_stmt_execute($stmt)){
                                    header("location: accounts.management.php");
                                    $_SESSION['status'] = "Account createdS successfully!";
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
                    echo "<font color = 'red'>Username already in use</font>";
                }
            }else{
                echo "<font color = 'red'Error on select statement</font>";
            }
        }
    }
    ?>
    <h3>Fill up this form and submit in order to add a new user</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="txtusername">Username:</label><br>
            <input type="text" name="txtusername" required><br>
        </div>
        <div class="form-group">
            <label for="txtpassword">Password:</label><br>
            <input type="password" name="txtpassword" id="txtpassword" required>
            <input type="checkbox" id="showPassword"> Show Password
        </div>
        <div class="form-group">
            <label for="cmbaccountType">Account Type:</label>
            <div class="group">
                <select name="cmbaccountType" required>
                    <option value="">Select Account Type</option>
                    <option value="ADMINISTRATOR">Administrator</option>
                    <option value="REGISTRAR">Registrar</option>
                </select>
            </div>
        </div>
        <div class="button-container">
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="accounts.management.php">Cancel</a>
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
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('txtpassword');

    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
  </script>
</body>
</html>

