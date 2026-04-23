<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: LoginMenu.php");
        exit();
    }

    if ($_SESSION['role'] !== 'Admin') {
        header("Location: LoginMenu.php"); 
        exit();
    }
?>