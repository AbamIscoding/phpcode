<!DOCTYPE html>
<html>
<head>
  <title>Grade Management - Arellano University Subject Advising System - AUSAS</title>
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
  <form class="search-box" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
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
        <a href="..\database\subject.advising.php">Subject Advising</a>        
      </div>
    </div>
  </div>
</div>
<div class="details-and-grades-container">
<?php
function buildGradesTable($result) {
    if(mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Encoded By</th><th>Date Encoded</th><th>Action</th>";
        echo "</tr>";
        while($row = mysqli_fetch_array($result)) {
            echo "<tr>";  
            echo "<td>" . $row['SubjectCode'] . "</td>";
            echo "<td>" . $row['Description'] . "</td>";
            echo "<td>" . $row['Unit'] . "</td>";
            echo "<td>" . $row['Grade'] . "</td>";
            echo "<td>" . $row['EncodedBy'] . "</td>";
            echo "<td>" . $row['DateEncoded'] . "</td>";
            echo "<td>";
            echo "<a href='update.grade.php?studentnumber=" . $row['StudentNumber'] . "&name=" . urlencode($row['Firstname'] . " " . $row['Lastname']) . "&course=" . $row['Course'] . "&year=" . $row['Yearlevel'] . "&subjectcode=" . $row['SubjectCode'] . "&description=" . urlencode($row['Description']) . "&grade=" . $row['Grade'] . "'>Update</a>";
            echo "<span style='margin: 0 5px;'>|</span>";
            echo "<button onclick=\"confirmDelete('" . $row['StudentNumber'] . "', '" . $row['SubjectCode'] . "')\">Delete</button>";        
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No records found";
    }
}

require_once "config.php";
if(isset($_POST['btnsearch'])) {
    $studentNumber = $_POST['txtsearch'];
    
    // Retrieve student details
    $sqlStudent = "SELECT Studentnumber, Lastname, Firstname, Middlename, Course, Yearlevel FROM tblstudent WHERE Studentnumber = ?";
    if($stmt = mysqli_prepare($link, $sqlStudent)) {
        mysqli_stmt_bind_param($stmt, "s", $studentNumber);
        if(mysqli_stmt_execute($stmt)) {
            $resultStudent = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($resultStudent) > 0) {
                $studentDetails = ''; // Store student details here
                while($row = mysqli_fetch_array($resultStudent)) {
                    // Display student details
                    $studentDetails .= "<div class='info-row'>";
                    $studentDetails .= "<div><strong>Student Number:</strong></div>"; 
                    $studentDetails .= "<div><input type='text' value='" . $row['Studentnumber'] . "' readonly></div>";         
                    $studentDetails .= "<div><strong>Last Name:</strong></div>"; 
                    $studentDetails .= "<div><input type='text' value='" . $row['Lastname'] . "' readonly></div>"; 
                    $studentDetails .= "<div><strong>First Name:</strong></div>"; 
                    $studentDetails .= "<div><input type='text' value='" . $row['Firstname'] . "' readonly></div>"; 
                    $studentDetails .= "<div><strong>Middle Name:</strong></div>"; 
                    $studentDetails .= "<div><input type='text' value='" . $row['Middlename'] . "' readonly></div>";
                    $studentDetails .= "<div><strong>Course:</strong></div>";
                    $studentDetails .= "<div><input type='text' value='" . $row['Course'] . "' readonly></div>";
                    $studentDetails .= "<div><strong>Year Level:</strong></div>";
                    $studentDetails .= "<div><input type='text' value='" . $row['Yearlevel'] . "' readonly></div>";
                    $studentDetails .= "<div><a href='add-grade.php?studentnumber={$row['Studentnumber']}&course={$row['Course']}&year={$row['Yearlevel']}&name={$row['Firstname']} {$row['Lastname']}' class='add-grade-button'>Add Grade</a></div>";                      
                    $studentDetails .= "</div>";
                }
                echo "<div class='student-details-container'>";
                echo "<h2>Student Details</h2>";
                echo "<div class='student-details'>";
                echo $studentDetails; // Echo student details here
                echo "</div>";
                echo "</div>";
            } else {
                echo "<center>No student found</center><span style='margin: 0 5px;'>|</span>";
            }
        } else {
            echo "Error retrieving student details";
        }
    } else {
        echo "Error in student query";
    }

    // Retrieve grades of the student
$sqlGrades = "SELECT g.StudentNumber, g.SubjectCode, s.Description, s.Unit, g.Grade, g.EncodedBy, g.DateEncoded, st.Firstname, st.Lastname, st.Course, st.Yearlevel FROM tblgrades g 
                  INNER JOIN tblsubjects s ON g.SubjectCode = s.SubjectCode 
                  INNER JOIN tblstudent st ON g.StudentNumber = st.StudentNumber
                  WHERE g.StudentNumber = ?";
if($stmtGrades = mysqli_prepare($link, $sqlGrades)) {
    mysqli_stmt_bind_param($stmtGrades, "s", $studentNumber);
    if(mysqli_stmt_execute($stmtGrades)) {
        $resultGrades = mysqli_stmt_get_result($stmtGrades);
        if(mysqli_num_rows($resultGrades) > 0) {
            echo "<div class='grades-table-container'>";
            echo "<h2>Grades</h2>";
            echo "<div class='grades-table'>";
            // Display grades table
            buildGradesTable($resultGrades);
            echo "</div>";
            echo "</div>";
        } else {
            echo "<center>No grades found</center>";
        }
    } else {
        echo "Error retrieving grades";
    }
} else {
    echo "Error in grades query";
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

<script>
function confirmDelete(studentnumber, subjectcode) {
    var result = confirm("Are you sure you want to delete the grade in subject code '" + subjectcode + "' of student number '" + studentnumber + "'?");
    if (result) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete.grade.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if(xhr.responseText === "Success") {
                    alert("Grade Successfully Deleted!");
                    location.reload();
                } else {
                    alert("Error deleting grade: " + xhr.responseText);
                }
            }
        };
        xhr.send("confirm=yes&txtsubjectcode=" + encodeURIComponent(subjectcode) + "&Studentnumber=" + encodeURIComponent(studentnumber));
    } else {
        alert("Grade deletion cancelled.");
    }
}
</script>

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
      z-index: 1000; /* Set a higher z-index value */
    }

    footer {
      position: fixed;
      bottom: 0;
      font-size: 14px;
      z-index: 1000; /* Set a higher z-index value */
    }
    .logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
    }
    main {
      padding-top: 60px; /* Adjusted padding to accommodate header */
      padding-bottom: 60px; /* Adjusted padding to accommodate footer */
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
  width: 100%; /* Set table width to 100% */
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
  margin-top: 10px; /* Adjusted spacing */
  margin-bottom: -90px; /* Adjusted spacing */
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
  justify-content: space-between;
  padding-left: 7%;
  margin-top: -80px;
  margin-bottom: 25px;
  padding-bottom: 120px;

}

.student-details-container h2,
.grades-table-container h2 {
  font-size: 24px;
  margin-bottom: 15px;
}

.student-details {
  margin-inline: 20px; /* Adjusted margin */
  margin-left: 10px;
  margin-right: 30px;
}
.grades-table {
  margin-inline: 20px; /* Adjusted margin */
  padding: 1px;
}

.info-row {
  flex: 0 0 calc(50% - 20px);
  margin-bottom: 15px;
}

.info-row div {
  margin-bottom: 5px;
}

.info-row strong {
  font-weight: bold;
}

.info-row input[type="text"] {
  width: 100%;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.student-details-container {
  width: 500px; /* Adjusted width */
  margin-right: 30px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.grades-table-container {
  width: 100%; /* Adjusted width */
  margin-right: 10%;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
.dropdown-content {
  display: none;
  position: absolute;
  background-color: yellow;
  min-width: 160px;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
  z-index: 1001; /* Adjusted z-index value */
  right: 0;
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
  </style>
