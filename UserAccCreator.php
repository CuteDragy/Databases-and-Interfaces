<?php
require_once "config.php";


?>
<!doctype HMTL>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="UserAccCreator.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="sidebar" id="mySidebar">
        <div>
            <p style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello, Guest</p>
        </div>
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <div style="margin-top: 35px; font-family:Arial, sans-serif;">
            <p style="margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 26px;">Menu</p>
            <a href="admin-index.html" id="navigation-button">Home</a>
            <a href="user-profile.html" id="navigation-button">User Profile</a>
            <a href="student-profile.html" id="navigation-button">Student Profile</a>
            <a href="assessor-profile.html" id="navigation-button">Assessor Profile</a>
            <a href="internships.html" id="navigation-button">Internships</a>
            <a href="companies.html" id="navigation-button">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
    </div>
    <div>
        <div class="content-header">
            <button class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
            <h1>User Creator</h1>
        </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            const overlay = document.getElementById("overlay");
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        }
    </script>
    </div>

</body>
</html>
        