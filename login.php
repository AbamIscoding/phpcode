<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page - Arellano University Subject Advising System - AUSAS</title>
<style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-image: url(au-pasig.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container h1 {
            font-size: 50px;
            color: #fff;
            margin-bottom: 20px;
            text-transform: uppercase;
            line-height: 1.2; /* Bagong linya */
        }
        .login-container h1 span {
            font-size: 24px;
            display: block;
        }
        .login-container p {
            font-size: 18px;
            color: #fff;
            margin-bottom: 10px;
            text-align: left;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            font-size: 16px;
            width: calc(100% - 20px);
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .login-container input[type="submit"] {
            font-size: 18px;
            width: calc(100% - 20px);
            padding: 12px;
            border: none;
            outline: none;
            border-radius: 5px;
            background-color: #0020c2;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-container input[type="submit"]:hover {
            background-color: #074173;
        }
        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
</style>
</head>
<body>
<div class="login-container">
        <h1><span>Arellano University Subject Advising System</span></h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <p>Username</p>
            <input type="text" name="txtusername" required><br>
            <p>Password</p> 
            <input type="password" name="txtpassword" required><br>
            <input type="submit" name="btnlogin" value="Login">
        </form>
<?php
if(isset($_POST['btnlogin'])){
    require_once "config.php";
    $sql = "SELECT * FROM tblaccounts WHERE Username = ? AND Password = ? AND Userstatus = 'ACTIVE'";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                session_start();
                $_SESSION['Username'] = $_POST['txtusername'];
                $_SESSION['Usertype'] = $account['Usertype'];
                header("location: index.php");  
            }else{
                echo "<center><font color='red'><br>Incorrect login details or account is disabled/inactive</font></center>";
            }
        }else{
            echo "<center><font color='red'>Error on login statement</font></center>";
        }
    }
}
?>
    </div>
</body>
</html>
