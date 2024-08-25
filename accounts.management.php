<!DOCTYPE html>
<html>
<head>
  <title>Accounts Management - Arellano University Subject Advising System - AUSAS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    header{
      display: flex;
    }
    header h1{
      color: white;
    }
    .logo{
      height: 100px;
      width: 100px;
      margin-left: 25px;
      margin-right: 150px;
    }
  </style>
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
  <a class="button create-account-link" href="create.account.php">Create New Account</a>
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
        <a href="..\database\student.account.php">Students Management</a>
        <a href="..\database\subject.management.php">Subjects Management</a>
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
          echo "<th>Username</th><th>Usertype</th><th>Status</th><th>Created by</th><th>Date Created</th><th>Actions</th><th>Email Address</th>";
          echo "</tr>";
          echo "<br>";
          while($row = mysqli_fetch_array($result)){
            echo "<tr>";  
            echo "<td>" . $row['Username'] . "</td>";
            echo "<td>" . $row['Usertype'] . "</td>";
            echo "<td>" . $row['Userstatus'] . "</td>";
            echo "<td>" . $row['CreatedBy'] . "</td>";
            echo "<td>" . $row['Datecreated'] . "</td>";
            echo "<td>";
            echo "<a href='update.account.php?Username=" . $row['Username'] . "'>Update</a>";
            echo "<span style='margin: 0 5px;'>|</span>";
            echo "<button onclick='confirmDelete(\"" . $row['Username'] . "\")'>Delete</button>";
            echo "<td>" . $row['Email'] . "</td>";
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
        $sql = "SELECT * FROM tblaccounts WHERE Username LIKE ? OR Usertype LIKE ? ORDER BY Username";
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
        $sql = "SELECT * FROM tblaccounts ORDER BY Username";
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
        function confirmDelete(username) {
            var result = confirm("Are you sure you want to delete the account for \"" + username + "\"?");
            if (result) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete.account.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if(response.success) {
                            alert("Account deleted successfully!");
                            window.location.reload(); // Reload the current page
                        } else {
                            alert("Error deleting the account!");
                        }
                    }
                };
                xhr.send("confirm=yes&txtusername=" + username);
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
      color: #0d0d0d;
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
      color: white;
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
      color: white;
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
      width: 85%;
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
      background-color: #4793AF;
      color: white;
    }
    tr:nth-child(odd) {
      background-color: #4793AF;
    }
    tr:hover {
      background-color: #8c8c8c;
    }
    .links-container .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #5DEBD7;
        color: white;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .links-container . button:hover {
        background-color: #5DEBD7;
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
  margin-right: 300px; /* Add margin to the right */
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

.logout-container {
  margin-left: 76%; /* Add margin to the left */
  margin-bottom: 60px;
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
  background-color:  #5DEBD7;
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
  right: 0; /* Adjust dropdown content alignment *//
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