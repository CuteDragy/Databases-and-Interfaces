<?php 
    session_start();
    include('config.php');
    include('auth-check.php');

    $user_id = $_SESSION['user'];
    $condition = "where user_id = $user_id";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    if(isset($_GET['assessorid'])){
        $assessor_id = mysqli_real_escape_string($conn, $_GET['assessorid']);
        $stmt = "SELECT * FROM users WHERE user_id = $assessor_id" ;
        $sql = mysqli_query($conn, $stmt);
        $assessor_details = mysqli_fetch_array($sql) or die(mysqli_error($conn));
    }

    $internship_num_stmt = "SELECT * FROM internships 
                            WHERE internal_assessor_id = $assessor_id OR external_assessor_id = $assessor_id";
    $internship_num_req = mysqli_query($conn, $internship_num_stmt);
    $internship_num = mysqli_num_rows($internship_num_req);
    $assessment_num_stmt = "SELECT * FROM internships 
                            WHERE internal_assessor_id = $assessor_id OR external_assessor_id = $assessor_id 
                            AND current_status = 'Ongoing'";
    $assessment_num_req = mysqli_query($conn, $assessment_num_stmt);
    $assessment_num = mysqli_num_rows($assessment_num_req);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Assessor Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/assessor-details.css">
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
                    <td style="padding-left: 15px;"><h1>Assessor Details</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="assessor-detail-header">
            <div id="assessor-detail-header-content">
                <div>
                    <table>
                        <tr>
                            <td style="padding-right: 15px;"><a href="assessor-profile.php" title="Back" Style="color: black; text-decoration: none; font-weight: 500;">
                                &larr;</a></td>
                            <td style="font-size: 20px; font-weight: bold;">Account Details</td>
                        </tr>
                    </table> 
                </div>
                <div style="padding-right: 20px;">
                    <?php echo "<a href='assessor-details-edit.php?assessorid=" . $assessor_id . "' style='text-decoration:none; color:black;' 
                    >EDIT</a>"; ?>
                </div>
            </div>
        </div>
        <div id="assessor-detail-content-container">
            <div id="assessor-detail-content">
                <div id="assessor-detail-profile-container">
                    <div id="assessor-detail-profile">
                        <div><img src="image/empty-profile.png" width="110" height="110"></div><br>
                        <div><b><?php echo $assessor_details['name']?></b></div>
                        <div id="profile-role">ASSESSOR</div>
                        <div id="profile-email"><?php echo $assessor_details['email']?></div>
                    </div>
                    <div id="assessor-internships-container">
                        <div class="assessor-internships">
                            <div style="font-weight: bolder; font-size: 12px; color: rgb(157, 150, 141);">ASSESSMENTS</div>
                            <div style="font-weight: bold; color: rgb(25, 25, 126); width: 100%;">
                                <?php echo $assessment_num ?>
                            </div>
                        </div>
                        <div class="assessor-internships">
                            <div style="font-weight: bolder; font-size: 12px; color: rgb(157, 150, 141);">INTERNSHIPS</div>
                            <div style="font-weight: bold; color: rgb(25, 25, 126); width: 100%;">
                                <?php echo $internship_num ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="assessor-details">
                        <br>
                        <div style="font-size: 20px; font-weight: bold;"><b>Account Information</b></div>
                        <div style="font-size: 14px; color: grey;">Update personal details and and manage account.</div><br>
                        <div id="account-information">
                            <div id="id-name">
                            <div id="assessor-id">
                                    <label for="user_id">USER ID</label>
                                    <input type="text" id="user_id" name="user_id" value="<?php echo $assessor_details['user_id']?>" readonly>
                                </div>
                                <div id="assessor-name">
                                    <label for="role">ROLE</label>
                                    <input type="text" id="role" name="role" value="Assessor" readonly><br>
                                </div>
                            </div>
                            <div>
                                <label for="name">NAME</label>
                                <input type="text" id="name" name="name" value="<?php echo $assessor_details['name']?>"><br>
                            </div>
                            <div>
                                <label for="password">PASSWORD</label>
                                <input type="password" id="password" name="password" readonly style="cursor: not-allowed"><br>
                            </div>
                            <div>
                                <label for="organization">ORGANIZATION</label>
                                <input type="text" id="organization" name="organization" value="<?php echo $assessor_details['organization']?>" ><br>
                            </div>                        
                            <div>
                                <label for="email">EMAIL ADDRESS</label>
                                <input type="text" id="email" name="email" value="<?php echo $assessor_details['email']?>" ><br>
                            </div>                        
                        </div>
                </div>
            </div>
        </div>

    <script src="js/sidebar.js"></script>

</body>
</html>