<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update account - AU Student Advising System - AUSAS</title>
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
h2 {
    text-align: center;
    padding-bottom: 10px;
}
.container h2 {
    margin-top: 0;
    color: #333;
}

.form-group {
    margin-bottom: 10px;
    padding-bottom: 7px;
}

.group {    
    width: 520px;
    margin-bottom: -5px;
    width: calc(111% - 22px);
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 3px;
    margin-top: 15px;
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
    margin-top: -15px;
}
input[type="password"],
select {
    width: calc(100% - 22px);
    padding: 10px;
    margin: 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    margin-top: -15px;
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
        $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, userstatus = ? WHERE username = ?";
				if($stmt = mysqli_prepare($link, $sql)){
				mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['Username']);
					if(mysqli_stmt_execute($stmt)){
					$sql = "INSERT INTO tbllogs (Datelog, Timelog, Action, Module, ID, PerformedBy) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
              $date = date("m/d/Y");
              $time = date("h:i:s");
              $action = "Update";
              $module = "Accounts Management";
              mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtusername']), $_SESSION['Username']);
              if(mysqli_stmt_execute($stmt)) {
                header("location: accounts.management.php");
                $_SESSION['status'] = "Account updated successfully!";                
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
            $sql = "SELECT * FROM tblaccounts WHERE username = ?";
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
        <div class="form-group">
            <label for="txtcurrentpassword">Current Password:</label><br>
             <input type="password" id="txtcurrentpassword" name="txtcurrentpassword" required><br>
             <div class = "showpass">
            <input id = "check" type="checkbox" onclick="myFunction()"><p style = 'font-size: 14px;'>Show Current Password</p>
            </div>
        </div>
                <script>
                    function myFunction() {
                        var x = document.getElementById("txtcurrentpassword");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    }
                </script>
        <div class="form-group">
            <label for="txtpassword">New Password:</label><br>
                <input type="password" id="txtpassword" name="txtpassword" required><br>
                <div class = "showpass">
                <input id = "check" type="checkbox" onclick="Function()"><p style = 'font-size: 14px;'>Show New Password</p>
                </div><br>
        </div>
                <script>
                    function Function() {
                        var x = document.getElementById("txtpassword");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    }
                </script>
        <div class="form-group">
            <label for="txtpassword">Confirm New Password:</label><br>
                <input type="password" id="txtconfirmpassword" name="txtconfirmpassword" required><br>
                <div class="showpass">
                    <input id="check" type="checkbox" onclick="ConfirmFunction()"><p style='font-size: 14px;'>Show Confirm Password</p>
                </div><br>
        </div>
                <!-- JavaScript function for toggling confirm password visibility -->
                <script>
                    function ConfirmFunction() {
                        var x = document.getElementById("txtconfirmpassword");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    }
                </script>
                <input type="submit" name="btnsubmit" value="Submit" id="submit">
                <a href = "index.php">Cancel</a>
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
