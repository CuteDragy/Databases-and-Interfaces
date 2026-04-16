<?php
session_start();
$dbName     = 'internship_management';
$serverName = 'localhost';
$dbUser     = 'root';
$dbPassword = 'root';

$conn = new mysqli($serverName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Database connection failed: " . mysqli_connect_error());
}