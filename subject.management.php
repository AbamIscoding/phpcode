<!DOCTYPE html>
<html>
<head>
  <title>Subject Management - Arellano University Subject Advising System - AUSAS</title>
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
  <a class="button create-account-link" href="create.subject.php">Create New Subject</a>  
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
        <a href="..\database\grade.management.php">Grades Management</a>
        <a href="..\database\subject.advising.php">Subjects Advising</a>        
      </div>
    </div>
  </div>
</div>
    <?php
      function buildtable($result){
        if(mysqli_num_rows($result)>0){
          echo "<table>";
          echo "<tr>";
          echo "<th>Subject Code</th><th>Description</th><th>Unit</th><th>Course</th><th>Created By</th><th>Date Created</th><th>Prerequisite 1</th><th>Prerequisite 2</th><th>Prerequisite 3</th><th>Actions</th>";
          echo "</tr>";
          echo "<br>";
          while($row = mysqli_fetch_array($result)){
            echo "<tr>";  
            echo "<td>" . $row['subjectcode'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['unit'] . "</td>";
            echo "<td>" . $row['course'] . "</td>";
            echo "<td>" . $row['createdby'] . "</td>";
            echo "<td>" . $row['datecreated'] . "</td>";
            echo "<td>" . $row['prerequisite1'] . "</td>";
            echo "<td>" . $row['prerequisite2'] . "</td>";
            echo "<td>" . $row['prerequisite3'] . "</td>";
            echo "<td>";
            echo "<a href='update.subject.php?Username=" . $row['subjectcode'] . "'>Update</a>";
            echo "<button onclick='confirmDelete(\"" . $row['subjectcode'] . "\")'>Delete</button>";
            echo "</td>";
            echo "</tr>";
          }
          echo "</table>";
        }else{
          echo "No record/s found";
        }
      }
      require_once "config.php";
      if(isset($_POST['btnsearch'])){
        $sql = "SELECT * FROM tblsubjects WHERE SubjectCode LIKE ? OR Description LIKE ? ORDER BY SubjectCode";
        if($stmt = mysqli_prepare($link, $sql)){
          $searchvalue = '%' . $_POST['txtsearch'] . '%';
          mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
          if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
          }
        }else{
          echo "ERROR on search";
        }
      }else{
        $sql = "SELECT * FROM tblsubjects ORDER BY SubjectCode";
        if($stmt = mysqli_prepare($link, $sql)){
          if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
          }else{
            echo "ERROR on accounts load";
          }
        }
      }
    ?>  
</main>
  <footer>
    ARELLANO UNIVERSITY <BR>
      2600, Legarda St., Sampaloc, Manila <br>
      Telephone No. 8-734731 <br>
      CopyRight &copy; 2024 
  </footer>
    <script>
        function confirmDelete(subjectcode) {
            var result = confirm("Are you sure you want to delete \"" + subjectcode + "\"?");
            if (result) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete.subject.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if(response.success) {
                            alert("Subject deleted successfully!");
                            window.location.reload(); // Reload the current page
                        } else {
                            alert("Error deleting the subject!");
                        }
                    }
                };
                xhr.send("confirm=yes&txtstudent=" + subjectcode);
            } else {
                alert("Account deletion cancelled.");
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
    .logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
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
    main {
      padding-top: 60px; /* Adjusted padding to accommodate header */
      padding-bottom: 40px; /* Adjusted padding to accommodate footer */
      margin-top: 50px; /* Adjusted margin to accommodate header */
      margin-bottom: 50px; /* Adjusted margin to accommodate footer */
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      overflow-y: auto;
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
      width: 90%;
      border-collapse: collapse;
      overflow-y: auto;
      height: 150px;
      margin-bottom: 10px;
      margin-bottom: 25px;
    }
    th, td {
      border: 1px solid #1f7a1f;
      padding: 8px;
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
  margin-bottom: -5px; /* Adjusted spacing */
}

.action-container .search-box {
  display: flex;
  align-items: center;
  margin-right: 280px; /* Add margin to the right */
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

.create-account-link {
  text-decoration: none;
  color: black;
  background-color: #5DEBD7;
  padding: 10px 20px;
  border-radius: 5px;
  margin-right: 10px; /* Add margin to create space */
}
.create-account-link:hover{
  transform: scale(1.05);
  transition: all 0.5s ease;
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
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
  z-index: 1001;
  right: 0; /* Adjust dropdown content alignment *//
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
.dropdown {
  position: relative;
  display: inline-block;
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
  z-index: 1001;
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