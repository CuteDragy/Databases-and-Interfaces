<?php 
    session_start();
    include('db.php');
    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/admin-index.css?v=<?php echo filemtime('style.css');?>">
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box;
             font-weight: bold; font-size: 27px; font-style: oblique;">
                <?php echo "Hello, <br>".$user_profile['name']. "" ?></p>
        </div>
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <div style="margin-top: 35px; font-family:Arial, sans-serif;">
            <p style="margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 26px;">Menu</p>
            <a href="admin-index.php" id="navigation-button" style="background-color:black; color: white;">Home</a>
            <a href="user-profile.php" id="navigation-button">User Profile</a>
            <a href="student-profile.php" id="navigation-button">Student Profile</a>
            <a href="assessor-profile.php" id="navigation-button">Assessor Profile</a>
            <a href="internships.php" id="navigation-button">Internships</a>
            <a href="companies.php" id="navigation-button">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
    
        

    <div id="main">
        <header>  
            <table>
                <tr>
                    <td style="padding: 5px;"><button class="menu-btn" onclick="toggleSidebar()">&#9776;</button></td>
                    <td style="padding-left: 15px;"><h1>Admin Internship Management</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <a href="user-profile.php" class="admin-home-options">
            <div class="admin-index-container">
                <div class="admin-index-content">
                    <img src="image/user-profile.png" alt="user profile icon">
                    <br>User Profile
                </div>
            </div>
        </a>
        <a href="student-profile.php" class="admin-home-options">
            <div class="admin-index-container">
                <div class="admin-index-content">
                    <img src="image/student-profile.png" alt="student profile icon" id="student-profile-icon">
                    <br>Student Profiles
                </div>
            </div>
        </a>
        <a href="assessor-profile.php" class="admin-home-options">
            <div class="admin-index-container">
                <div class="admin-index-content">
                    <img src="image/assessor-profile.png" alt="assessor profile icon" id="assessor-profile-icon">
                    <br>Assessor Profiles
                </div>
            </div>
        </a>
        <a href="internships.php" class="admin-home-options">
            <div class="admin-index-container">
                <div class="admin-index-content">
                    <img src="image/internship.png" alt="internship icon" id="internship-icon">
                    <br>Internships
                </div>
            </div>
        </a>
        <a href="companies.php" class="admin-home-options">
            <div class="admin-index-container">
                <div class="admin-index-content">
                    <img src="image/company.png" alt="company icon" id="company-icon">
                    <br>Companies
                </div>
            </div>
        </a>
    </div>

    <script src="js/sidebar.js"></script>

</body>
</html>