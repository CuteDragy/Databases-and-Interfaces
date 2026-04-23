<?php 
    session_start();
    include('db.php');
    include('auth-check.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | User Profile</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/user-profile.css?v=<?php echo filemtime('style.css');?>">
</head>
<body>
    <?php 
        $condition = "where user_id = 12345 ";
        $instruction = "select * from users $condition";
        $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
        $user_profile = mysqli_fetch_array($action);
    ?>
    <div class="sidebar" id="mySidebar">
        <div>
            <p style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box;
             font-weight: bold; font-size: 27px; font-style: oblique;">
                <?php echo "Hello, <br>".$user_profile['name']. "" ?></p>
        </div>
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <div style="margin-top: 35px; font-family:Arial, sans-serif;">
            <p style="margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 26px;">Menu</p>
            <a href="admin-index.php" id="navigation-button">Home</a>
            <a href="user-profile.php" id="navigation-button" style="background-color:black; color: white;">User Profile</a>
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
                    <td style="padding-left: 15px;"><h1>User Profile</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50" style="margin-right:15px;"></a></div>
        </header>

        
        <div id="main-text-container">
            <div id="main-text">
                <div id="title">
                    Account Details
                </div>
               <div id="title-description">
                    Manage your administrative identity and profile settings
               </div>
            </div>
        </div>
        <div class="user-profile-container">
            <div class="user-profile">
                <div id="profile-card">
                    <div id="identity">
                        <img src="image/empty-profile.png" width="100" height="100">
                        <div style="line-height: 1;">
                            <span style="font-weight: bold; font-size: 24;"><?php echo $user_profile['name'] ?></span><br>
                            <span style="font-size: 15px; font-weight: bold;color: rgb(44, 68, 220);">
                                <?php echo $user_profile['role'] ?></span>
                        </div>
                    </div>
                    <div>
                        <div id="edit-button"><?php echo "<a href='user-profile-edit.php'>EDIT PROFILE</a>"; ?></div>
                    </div>
                </div>
                <div id="user-information">
                    <div class="user-information-row">
                        <div class="data">
                            <label>FULL NAME</label><br>
                            <input type="text" value="<?php echo $user_profile['name'] ?>" readonly>
                        </div>
                        <div class="data">
                            <label>USER ID</label><br>
                            <input type="text" value="<?php echo $user_profile['user_id'] ?>" readonly>
                        </div>
                    </div>
                    <div class="user-information-row">
                        <div class="data">
                            <label>USER ROLE</label><br>
                            <input type="text" value="<?php echo $user_profile['role'] ?>" readonly>
                        </div>
                        <div class="data">
                            <label>EMAIL</label><br>
                            <input type="text" value="<?php echo $user_profile['email'] ?>" readonly>
                        </div>
                    </div>
                    <div class="user-information-row">
                        <div class="data">
                            <label>ORGANIZATION</label><br>
                            <textarea readonly><?php echo $user_profile['organization']?></textarea> 
                        </div>
                        <div class="data">
                            <label>ACCOUNT CREATED</label><br>
                            <input type="text" value="<?php echo $user_profile['creation_date'] ?>" readonly>
                        </div>
                    </div>
                </div>
            </div><br>
        </div>
    </div>

    <script src="js/sidebar.js"></script>

</body>
</html>