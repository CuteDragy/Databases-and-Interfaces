<?php 
    session_start();
    include('db.php');
    include('auth-check.php');
    

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | New Assessor</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/add-assessor.css?v=<?php echo filemtime('style.css');?>">
    <link rel="stylesheet" href="css/formCheck.css">
    <?php include('error-function.php'); ?>
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
            <a href="admin-index.php" id="navigation-button">Home</a>
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
                    <td style="padding-left: 15px;"><h1>New Assessor</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div class="add-assessor-container">
            <form action="add-assessor-process.php" method="POST" id="addAssessorForm">    
                <div class="add-assessor">
                    <div id="back-button"><a href="assessor-profile.php">BACK</a></div>
                    <h2 style="text-align: center; text-decoration: underline;">Assessor Details</h2>
                    <br>
                    <div class="assessor-detail-container">
                        <h4><b>Please fill in following details...</b></h4>
                        <span class="error-msg" id="error-user_id"></span>
                        <label for="user_id">USER ID</label>
                        <input type="text" id="user_id" name="user_id" required><br>
                        <span class="error-msg" id="error-name"></span>
                        <label for="name">NAME</label>
                        <input type="text" id="name" name="name"><br>
                        <span class="error-msg" id="error-role"></span>
                        <label for="role">ROLE</label>
                        <input type="text" id="role" name="role" value="Assessor" readonly><br>
                        <span class="error-msg" id="error-passwords"></span>
                        <label for="passwords">PASSWORD</label>
                        <input type="password" id="passwords" name="passwords"><br>
                        <span class="error-msg" id="error-email"></span>
                        <label for="email">EMAIL</label>
                        <input type="text" id="email" name="email"><br>
                        <span class="error-msg" id="error-organization"></span>
                        <label for="organization">ORGANIZATION</label>
                        <input type="text" id="organization" name="organization">
                    </div>
                </div>
                <input type="submit" id="submit-button" class="submit-button" name="submit-button" value="ADD ASSESSOR">
            </form>            
        </div>
    </div>

    <script src="js/sidebar.js"></script>
    <script src="js/formCheck.js"></script>

</body>
</html>