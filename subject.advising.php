<!DOCTYPE html>
<html>
<head>
  <title>Subject Advising - Arellano University Subject Advising System - AUSAS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header>
    <img src="logo.png" alt="au-logo" class="logo">
    <h1>Arellano University Subject Advising System - AUSAS</h1>
  </header>
  <main>
    <div class="welcome-container">
      <?php
        session_start();
        if(isset($_SESSION['status'])){
        echo "<script>alert('" . $_SESSION['status'] . "')</script>";
        unset($_SESSION['status']);
        }
        if(isset($_SESSION['Username'])){
          echo "<div class='welcome-message'>Welcome, " . $_SESSION['Username'] . "</div>";
          echo "<div class='account-type'>Account type: " . $_SESSION['Usertype'] . "</div>";
        }
        else{
          header("Location: login.php");
        }
      ?>
    </div>
<div class="action-container">
  <form class="search-box" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      Search: <input type="text" name="txtsearch">
      <button type="submit" name="btnsearch" value="Search"><i class="fa fa-search"></i></button>
  </form>
  <div class="menu-container">
    <div class="dropdown">
      <button class="dropbtn">Menu</button>
      <div class="dropdown-content">
        <a href="logout.php">Logout</a>
        <a href="index.php">Index</a>
      </div>
    </div>
    <div class="dropdown">
      <button class="dropbtn drop-arrow">&#9662;</button>
      <div class="dropdown-content">
        <a href="..\database\accounts.management.php">Accounts Management</a>
        <a href="..\database\student.account.php">Students Management</a>
        <a href="..\database\subject.management.php">Subjects Management</a>
        <a href="..\database\grade.management.php">Grades Management</a>
      </div>
    </div>
  </div>
</div>
<div class="details-and-grades-container">
<?php
require_once "config.php";

// Function definition
        function checkPrerequisite($link, $student_number, $prerequisite) {
              $sql_check_prerequisite = "SELECT * FROM tblgrades WHERE studentnumber = ? AND subjectcode = ?";
              $stmt_check_prerequisite = mysqli_prepare($link, $sql_check_prerequisite);
              mysqli_stmt_bind_param($stmt_check_prerequisite, "ss", $student_number, $prerequisite);
              mysqli_stmt_execute($stmt_check_prerequisite);
              mysqli_stmt_store_result($stmt_check_prerequisite);
              $num_rows = mysqli_stmt_num_rows($stmt_check_prerequisite);
              mysqli_stmt_close($stmt_check_prerequisite);
              return $num_rows > 0;
}


      if(isset($_POST['btnsearch']) && !empty($_POST['txtsearch'])) {
          $student_number = $_POST['txtsearch'];
          
          // Perform search by student number
          $sql_student = "SELECT * FROM tblstudent WHERE studentnumber = ?";
          if($stmt = mysqli_prepare($link, $sql_student)){
              mysqli_stmt_bind_param($stmt, "s", $student_number);
              if(mysqli_stmt_execute($stmt)){
                  $result_student = mysqli_stmt_get_result($stmt);
                  if(mysqli_num_rows($result_student) > 0){
                      // Student details found, display them
                      $row_student = mysqli_fetch_assoc($result_student);
                      // Display student details
                      echo "<div class='student-details-container'>";
                      echo "<h2><center>Student Details</center></h2>";
                      echo "<div class='info-container'>";
                      echo "<div class='info-row'><strong>Student Number:</strong> <input type='text' value='" . $row_student['studentnumber'] . "' readonly></div>";
                      echo "<div class='info-row'><strong>Name:</strong> <input type='text' value='" . $row_student['lastname'] . ", " . $row_student['firstname'] . " " . $row_student['middlename'] . "' readonly></div>";
                      echo "<div class='info-row'><strong>Course:</strong> <input type='text' value='" . $row_student['course'] . "' readonly></div>";
                      echo "<div class='info-row'><strong>Year Level:</strong> <input type='text' value='" . $row_student['yearlevel'] . "' readonly></div>";                      
                      echo "</div>"; // Close info-container
                      echo "</div>"; // Close student-details-container
                  } else {
                      // No student found with the entered student number
                      echo "<p>No student found with the entered student number.</p>";
                  }
              } else {
                  echo "<p>Error executing search query: " . mysqli_error($link) . "</p>";
              }
          } else {
              echo "<p>Error preparing search statement: " . mysqli_error($link) . "</p>";
          }
      }
                
                // Display table of subjects
                $sql_subjects = "SELECT * FROM tblsubjects WHERE course = ?";
                if($stmt_subjects = mysqli_prepare($link, $sql_subjects)){
                    mysqli_stmt_bind_param($stmt_subjects, "s", $row_student['course']);
                    if(mysqli_stmt_execute($stmt_subjects)){
                        $result_subjects = mysqli_stmt_get_result($stmt_subjects);
                        if(mysqli_num_rows($result_subjects) > 0){
                            echo "<div class='details-and-grades-container'>";
                            echo "<div class='grades-table'>";
                            echo "<h2>Subject/s need to be taken</h2>";
                            echo "<center>";
                            echo "<table id='main'>";
                            echo "<tr>";
                            echo "<th>Subject Code</th><th>Description</th><th>Unit</th>"; // Added Grade column header
                            echo "</tr>";
                            while ($row_subject = mysqli_fetch_assoc($result_subjects)){
                                $subjectcode = $row_subject['subjectcode'];
                                $prerequisite1 = $row_subject['prerequisite1'];
                                $prerequisite2 = $row_subject['prerequisite2'];
                                $prerequisite3 = $row_subject['prerequisite3'];

                                // Check if the subject is not already graded
                                $sql_check_grade = "SELECT * FROM tblgrades WHERE studentnumber = ? AND subjectcode = ?";
                                $stmt_check_grade = mysqli_prepare($link, $sql_check_grade);
                                mysqli_stmt_bind_param($stmt_check_grade, "ss", $student_number, $subjectcode);
                                mysqli_stmt_execute($stmt_check_grade);
                                mysqli_stmt_store_result($stmt_check_grade);
                                $num_rows = mysqli_stmt_num_rows($stmt_check_grade);
                                mysqli_stmt_close($stmt_check_grade);

                                // Check if all prerequisites are in tblgrades
                                if ($num_rows == 0 && 
                                    (!$prerequisite1 || checkPrerequisite($link, $student_number, $prerequisite1)) && 
                                    (!$prerequisite2 || checkPrerequisite($link, $student_number, $prerequisite2)) && 
                                    (!$prerequisite3 || checkPrerequisite($link, $student_number, $prerequisite3))) {

                                    echo "<tr>";
                                    echo "<td>" . $row_subject['subjectcode'] . "</td>";
                                    echo "<td>" . $row_subject['description'] . "</td>";
                                    echo "<td>" . $row_subject['unit'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                            echo "</table>";
                            echo "</center>";
                            echo "</div>";
                            echo "</div>";
                        } else {
                            echo "<p style='text-align: center;'>No subjects found.</p>";
                        }
                    }
                }
?>

</div>
</main>
<footer>
      ARELLANO UNIVERSITY <BR>
      2600, Legarda St., Sampaloc, Manila <br>
      Telephone No. 8-734731 <br>
      CopyRight &copy; 2024 
</footer>

</body>
</html>

  <style>
   body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #d9d9d9;
    }
    header, footer {
      background-color: #0020c2;
      color: white;
      text-align: center;
      padding: 10px; /* Reduced padding */
      width: 100%;
    }
    header {
      position: fixed;
      top: 0;
      font-size: 18px;
      display: flex;
      z-index: 999; /* Set a higher z-index value */
    }

    footer {
      position: fixed;
      bottom: 0;
      font-size: 14px;
      z-index: 999; /* Set a higher z-index value */
    }
    .logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
    }
    main {
      padding-top: 5%; /* Adjusted padding to accommodate header */
      padding-bottom: 5%; /* Adjusted padding to accommodate footer */
      margin-top: 50px; /* Adjusted margin to accommodate header */
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      overflow-y: auto;
      height: 100%;
    }
    .welcome-container {
      background-color: #1679AB;
      padding: 20px;
      border-radius: 10px;
      margin: 20px auto;;
      max-width: 900px; /* Adjusted max-width */
      width: 100%;
      height: 100px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      font-family: 'Arial', sans-serif;
      margin-top: 20px;
    }
    .welcome-message {
      font-size: 35px;
      color: white;
      margin-top: 5px;
      margin-bottom: 20px;
      font-weight: bold;
    }
    .account-type {
      font-size: 25px;
      color: white;
      margin-top: 10px;
    }
    form {
      margin-top: 10px;
    }
    .search-box {
      margin: 10px auto;
    }
table {
  width: 95%; /* Set table width to 100% */
  border-collapse: collapse;
  overflow-y: auto;
  max-height: 300px; /* Example max-height, adjust as needed */
  margin-bottom: 25px;
}

th, td {
  border: 1px solid #1f7a1f; 
  padding: 12px; /* Increase padding for better spacing */
  text-align: center;
}

th {
  background-color: #1679AB;
  color: white;
}

tr:nth-child(odd) {
  background-color: #1679AB;
}

tr:hover {
  background-color: #8c8c8c;
}
    .links-container .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .links-container . button:hover {
        background-color: #45a049;
    }
.action-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 5px; /* Adjusted spacing */
  margin-bottom: 0px; /* Adjusted spacing */
}

.action-container .search-box {
  display: flex;
  align-items: center;
  margin-right: 0px; /* Add margin to the right */
  margin-bottom: 200px;
}


.action-container .search-box input[type="text"] {
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  width: 300px; /* Adjusted width */
  margin-right: 5px;
}

.action-container .search-box button[type="submit"] {
  padding: 10px 15px;
  background-color: #1f7a1f;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.menu-container {
  margin-left: 10px;
}
/* Dropdown Button */
.dropbtn {
  background-color: #5DEBD7;
  color: black;
  font-weight: bolder;
  font-size: 15px;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  border: none;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: yellow;
  min-width: 160px;
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
  background-color: #C8C8C8;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: grey;
}
.details-and-grades-container {
  display: flex;
  justify-content: space-around; /* Adjusted to distribute space evenly */
  align-items: flex-start; /* Adjusted to align items at the start */
  padding: 0 20px; /* Adjusted padding */
  margin-top: -90px; /* Adjusted margin */
  margin-bottom: 20px; /* Adjusted margin */
  width: 90%;
}

.student-details-container {
  width: calc(50% - 10px); /* Adjusted width */
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: left;
}

.grades-table {
  width: calc(100% - 50px); /* Adjusted width */
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.info-row {
  flex: 0 0 calc(50% - 20px);
  margin-bottom: 15px;
}

.info-row div {
  margin-bottom: 5px;
}

.info-row input[type="text"] {
  width: calc(100% - 40px); /* Adjusted width */
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
  margin: 0 auto; /* Center the textbox */
  display: block; /* Ensure the textbox takes the full width of its container */
}
.student-details-container {
  width: 450px; /* Adjusted width */
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: left; /* Align text to the left */
  margin-top: -90px; /* Adjusted margin */  
  margin-left: 40px;
}

.info-container {
  padding: 20px;
}

.info-row {
  margin-bottom: 10px;
}

.add-grade-button {
  display: inline-block;
  padding: 10px 20px;
  background-color: #4CAF50; /* Green color */
  color: white;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 10px; /* Adjust margin as needed */
}

.add-grade-button:hover {
  background-color: #45a049; /* Darker green color on hover */
}
.create-account-link {
  text-decoration: none;
  color: black;
  background-color: #5DEBD7;
  padding: 10px 20px;
  border-radius: 5px;
  margin-right: 5px; /* Add margin to create space */
  margin-left: 450px;
}
.dropdown {
  position: relative;
  display: inline-block;
  margin-bottom: 190px;
}

.drop-arrow {
  background-color: #5DEBD7; /* Initially transparent */
  color: black;
  font-weight: bolder;
  font-size: 15px;
  padding: 9px 10px; /* Adjust padding as needed */
  border-radius: 5px;
  cursor: pointer;
  border: none;
}

.dropdown:hover .drop-arrow {
  background-color: #5DEBD7; /* Background color same as menu */
  color: white; /* Text color when hovered */
}


.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #C8C8C8;
}

.dropdown:hover .dropdown-content {
  display: block;
  z-index: 1001;
}

.dropdown:hover .drop-arrow {
  background-color: grey;
}
/* Adjusted dropdown content z-index */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: yellow;
  min-width: 160px;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
  z-index: 1001; /* Set a higher z-index value */
  right: 0;
}

/* Adjusted dropdown content positioning */
.dropdown:hover .dropdown-content {
  display: block;
}
  </style>
