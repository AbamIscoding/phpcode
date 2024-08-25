
<!DOCTYPE html>
<html>
<head>
  <title>Accounts Management - Arellano University Subject Advising System - AUSAS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Styles for rounded boxes */
    body {
	  font-family: Arial, sans-serif;
	  margin: 0;
	  padding-top: 190px; /* Add padding to the top */
	  padding-bottom: 190px; /* Add padding to the bottom */
	  background-color: #d9d9d9;
	}
    header, footer {
      background-color: #0020c2;
      color: #0d0d0d;
      text-align: center;
      padding: 10px; /* Reduced padding */
      width: 100%;
    }
    header {
      display: flex;
      position: fixed;
      top: 0;
      font-size: 18px; /* Reduced font size */
    }
    header{
      color: white;
    }
    .footer{
      color: white;
    }
    
      .logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
    }
    footer {
      position: fixed;
      bottom: 0;
      font-size: 14px; /* Reduced font size */
    }
	.container {
	  text-align: center;
	  margin-top: 10px;
	  display: flex;
	  flex-direction: row; /* Change flex direction to row */
	  align-items: center;
	  justify-content: center; /* Horizontally center the items */
	}
  
	.link-box {
	  display: inline-block;
	  padding: 50px; /* Increase padding for a more box-like appearance */
	  padding-top: 175px;
	  margin: 10px;
	  border-radius: 10px; /* Adjust border radius for rounded corners */
	  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add box shadow for depth */

	}
    .link-box:nth-child(1) {
      background-color: #074173; /* Light Coral */
    }
    .link-box:nth-child(2) {
      background-color: #1679AB; /* Sky Blue */
    }
    .link-box:nth-child(3) {
      background-color: #7BC9FF; 
    }
    .link-box:nth-child(4) {
      background-color: #135D66; 
    }  
    .link-box:nth-child(5) {
      background-color: #03AED2; 
    }   
    .link-box a {
      display: block;
      width: 100%; /* Ensure the anchor takes up the full width of the box */
      height: 100%; /* Ensure the anchor takes up the full height of the box */
      text-decoration: none;
      color: black; /* Set text color to black */
      font-weight: bold; /* Make text bold */
    }
   

    .link-box a:hover {
      text-decoration: none; /* Remove underline on hover */
    }
    .link-box:hover{
      transform: scale(1.05);
      transition: all 0.5s ease;
    }
    .icon{
      height: 90px;
      width: 90px;
    }
   
  </style>
 </head>
<body>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
<?php
session_start();
$usertype = $_SESSION['Usertype'];
if($usertype == 'ADMINISTRATOR'){
?>
	<div class="container">
	  <a href="..\database\accounts.management.php" class="link-box"style="color: white; text-decoration: none; font-weight: bold;"><img src="accounticon.png" alt="icon iconAccounts" class="icon">Accounts Management</a>
	  <a href="..\database\student.account.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="studenticon.png" alt="icon iconstudent" class="icon">Students Management</a>
	  <a href="..\database\subject.management.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="subject.png" alt="icon iconsubject" class="icon">Subjects Management</a>
    <a href="..\database\grade.management.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">Grades Management</a>
    <a href="..\database\subject.advising.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">Subject Advising</a>    
	</div>
<?php
}else if($usertype == 'REGISTRAR'){
?>
	<div class="container">
    <a href="..\database\student.account.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="studenticon.png" alt="icon iconstudent" class="icon">Students Management</a>
	  <a href="..\database\subject.management.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="subject.png" alt="icon iconsubject" class="icon">Subjects Management</a>
    <a href="..\database\grade.management.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">Grades Management</a>
    <a href="..\database\subject.advising.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">Subject Advising</a>    
	</div>
<?php
}else{
	?>
    <center><a href="..\database\view.grades.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">View Grades</a>
    <a href="..\database\view-subjects.php" class="link-box" style="color: white; text-decoration: none; font-weight: bold;"><img src="gradesicon.png" alt="icon icongrades" class="icon">View Subjects</a></center>     	
	<?php
}
?>
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
