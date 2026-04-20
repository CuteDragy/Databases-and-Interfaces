<?php 
    session_Start();
    include('db.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);


    if(isset($_GET['internshipid'])){
        $internship_id = mysqli_real_escape_string($conn, $_GET['internshipid']);
        $condition = "WHERE internship_id = $internship_id";
        $instruction = "SELECT * FROM internships $condition" ;
        $action = mysqli_query($conn, $instruction);
        $internship_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
    }
    
?>

<!DOCTYPE html>
<html  lang="en">
<head>
    <title>Admin Index | Edit Internship Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/internship-details-edit.css?v=<?php echo filemtime('style.css');?>">
    <link rel="stylesheet" href="css/formCheck.css">
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p
                style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello,<br><?php echo $user_profile['name'];?></p>
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
                    <td style="padding-left: 15px;"><h1>Editting Internship Details</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div class="internship-details-container">
            <form method="POST" action="internship-details-update.php" id="editInternshipForm">
                <div class="internship-details">
                    <div id="back-button"><div><a href="internships.php">BACK</a></div></div>
                    <h2 style="text-align: center; text-decoration: underline;">Internship Details</h2>
                    <div class="internship-details-edit-container">
                        <h4><b>Internship Details</b></h4>
                        <span class="error-msg" id="error-internship_id"></span>
                        <label for="internship_id">INTERNSHIP ID</label> 
                        <input type="text" id="internship_id" name="internship_id" value="<?php echo $internship_details['internship_id'] ?>" readonly><br>
                        <span class="error-msg" id="error-student_id"></span>
                        <label for="student_id">STUDENT ID</label>
                        <input type="text" id="student_id" name="student_id" value="<?php echo $internship_details['student_id'] ?>"><br>
                        <span class="error-msg" id="error-internal_assessor_id"></span>
                        <label for="internal_assessor_id">INTERNAL ASSESSOR ID</label>
                        <input type="text" id="internal_assessor_id" name="internal_assessor_id" value="<?php echo $internship_details['internal_assessor_id'] ?>"><br>
                        <span class="error-msg" id="error-external_assessor_id"></span>
                        <label for="external_assessor_id">EXTERNAL ASSESSOR ID</label>
                        <input type="text" id="external_assessor_id" name="external_assessor_id" value="<?php echo $internship_details['external_assessor_id'] ?>"><br>
                        <span class="error-msg" id="error-company_id"></span>
                        <label for="company_id">COMPANY ID</label>
                        <input type="text" id="company_id" name="company_id" value="<?php echo $internship_details['company_id'] ?>"><br>
                    </div><hr>
                    <div class="internship-details-edit-container">
                        <h4><b>Date and Duration</b></h4>
                        <span class="error-msg" id="error-startDate"></span>
                        <label for="startDate">START DATE</label>
                        <input type="date" id="startDate" name="startDate" onchange="calculateDuration()" 
                            value="<?php echo date('Y-m-d', strtotime($internship_details['startDate']));?>"><br>
                        <span class="error-msg" id="error-endDate"></span>
                        <label for="endDate">END DATE</label>
                        <input type="date" id="endDate" name="endDate" onchange="calculateDuration()" 
                            value="<?php echo date('Y-m-d', strtotime($internship_details['endDate']));?>"><br>
                        <?php $duration = date_diff(date_create($internship_details['startDate']),date_create($internship_details['endDate'])); ?>
                        <span class="error-msg" id="error-duration"></span>
                        <label for="duration">DURATION</label>
                        <input type="text" id="duration" name="duration" value="<?php echo $duration -> format('%a days'); ?>" readonly><br>
                        <span class="error-msg" id="error-current_status"></span>
                        <label for="current_status">CURRENT STATUS</label>
                        <div id="current-status-container">
                            <span>
                                <input type="radio" id="Ongoing" name="current_status" value="Ongoing"  
                                    <?php echo ($internship_details['current_status'] == 'Ongoing') ? 'checked' : ''; ?>>
                                <label for="Ongoing">Ongoing</label>
                            </span>
                            <span>
                                <input type="radio" id="Completed" name="current_status" value="Completed"
                                    <?php echo ($internship_details['current_status'] == 'Completed') ? 'checked' : ''; ?>>
                                <label for="Completed">Completed</label>
                            </span>
                        </div>
                    </div><br>    
                </div>
                <input type="submit" id="submit-button" name="submit-button" value="Save Changes">
            </form>
            </div>
        </div>
    </div>

    <script src="js/sidebar.js"></script>
    <script src="js/calculateDuration.js"></script>
    <script src="js/formCheck.js"></script>

</body>
</html>