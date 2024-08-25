<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password - AU Student Advising System - AUSAS</title>
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
	text-align: left;
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
	#label {
		text-align: left;
		margin-bottom: 0px;
		cursor: default;
	}

	.radio, .showpass {
		display: flex;
		justify-content: flex-start;
		cursor: default;
		margin-left: 0px;
	}

	.showpass {
		margin-top: -15px;
	}

	#radio, #check {
		cursor: pointer;
		padding: 5px;
		margin-right: 5px;
		width: auto;
	}
</style>
</head>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
  <div class="container">
	<div class = "body">
		<center>
		    <h3><p> Fill up this form and submit in order to change password</p></h3>
		    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
<?php
session_start(); // Initialize session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // PHP code to handle form submission and password validation
    require_once "config.php";

    if (isset($_POST['btnsubmit'])) {
        // Retrieve current password from the database
        if (isset($_SESSION['z'])) {
            $username = $_SESSION['Username'];
            $sql = "SELECT password FROM tblaccounts WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);
                    $stored_password = $row['password']; // 'password' should be lowercase

                    // Check if the current password matches the one in the database
                    if ($_POST['txtcurrentpassword'] === $stored_password) {
                        // Check if the new password matches the confirm password
                        $new_password = $_POST['txtpassword'];
                        $confirm_password = $_POST['txtconfirmpassword'];
                        if ($new_password === $confirm_password) {
                            // Update the password
                            $sql_update_password = "UPDATE tblaccounts SET password = ? WHERE username = ?";
                            if ($stmt_update_password = mysqli_prepare($link, $sql_update_password)) {
                                mysqli_stmt_bind_param($stmt_update_password, "ss", $new_password, $username);
                                if (mysqli_stmt_execute($stmt_update_password)) {
                                    // Log the password change action
                                    $date = date("m/d/Y");
                                    $time = date("h:i:s");
                                    $action = "Change Password";
                                    $module = "Student Account";
                                    $performedby = $username;
                                    $sql_insert_log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                                    if ($stmt_insert_log = mysqli_prepare($link, $sql_insert_log)) {
                                        mysqli_stmt_bind_param($stmt_insert_log, "ssssss", $date, $time, $action, $module, $username, $performedby);
                                        if (mysqli_stmt_execute($stmt_insert_log)) {
                                            echo "Password updated successfully!";
                                            $_SESSION['status'] = "Password Changed Successfully!";
                                            header("location: view.grades.php");
                                            exit(); // Terminate script after redirect
                                        } else {
                                            echo "<font color='red'>Error logging the password change action.</font>";
                                        }
                                    } else {
                                        echo "<font color='red'>Error preparing log statement.</font>";
                                    }
                                } else {
                                    echo "<font color='red'>Error updating password.</font>";
                                }
                            } else {
                                echo "<font color='red'>Error preparing update statement.</font>";
                            }
                        } else {
                            echo "<p style='color: red;'>New and Confirm password do not match</p>";
                        }
                    } else {
                        echo "<p style='color: red;'>Current password is incorrect</p>";
                    }
                } else {
                    echo "<font color='red'>Error retrieving password from database.</font>";
                }
            }
        } else {
            echo "<font color='red'>Error: Session username not set.</font>";
        }
    }
}
?>
<br>
        <div class="form-group">
		        <label>Current Password:</label><br>
		        <input type="password" id="txtcurrentpassword" name="txtcurrentpassword" required><br>
		        <div class = "showpass">
				<input id = "check" type="checkbox" onclick="myFunction()"><p style = 'font-size: 14px;'>Show Current Password</p>
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
		</div>
		        <label>New Password:</label><br>
		        <input type="password" id="txtpassword" name="txtpassword" required><br>
		        <div class = "showpass">
				<input id = "check" type="checkbox" onclick="Function()"><p style = 'font-size: 14px;'>Show New Password</p>
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
				<label>Confirm New Password:</label><br>
				<input type="password" id="txtconfirmpassword" name="txtconfirmpassword" required><br>
				<div class="showpass">
				    <input id="check" type="checkbox" onclick="ConfirmFunction()"><p style='font-size: 14px;'>Show Confirm Password</p>
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
		        <a href = "view.grades.php">Cancel</a>
		    </form>
		</center>
	</div>
</div>
	<footer>
		<div class = "text">
			Change Password Page - AUSAS
		</div>
		<div class="contact-con"><span id="contact">Arellano University</span>
	        <br>1002 Jacinto St, Quezon, Mabini, Plaridel
	        <br>+63 912 345 6789
	        <br>email.address.123@gmail.com
	    </div>
	</footer>
</body>
</html>