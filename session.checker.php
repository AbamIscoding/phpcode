<?php
session_start();
if(!isset($_SESSION['Username'])){
    header("location: login.php");
}
?>