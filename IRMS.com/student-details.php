<?php 
    session_Start();
    include('db.php');
    include('auth-check.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    if(isset($_GET['studentid'])){
        $student_id = mysqli_real_escape_string($conn, $_GET['studentid']);
        $condition = "WHERE student_id = $student_id";
        $instruction = "SELECT * FROM students $condition" ;
        $action = mysqli_query($conn, $instruction);
        $student_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
    }
    $encoded_address = urlencode($student_details['address'] . "Malaysia");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Student Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/student-details.css?v=<?php echo filemtime('style.css');?>">
    <?php include('error-function.php'); ?>
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p
                style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello,<br> <?php echo $user_profile['name']; ?></p>
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
                    <td style="padding-left: 15px;"><h1>Student Details</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="student-detail-header">
            <div id="student-detail-header-content">
                <div>
                    <table>
                        <tr>
                            <td style="padding-right: 15px;"><a href="student-profile.php" title="Back" Style="color: black; text-decoration: none; font-weight: 500;">
                                &larr;</a></td>
                            <td style="font-size: 20px; font-weight: bold;">Student Information</td>
                        </tr>
                    </table> 
                </div>
                <div id="function">
                    <div id="back-button"><?php echo "<a href='student-details-edit.php?studentid=". $student_id . 
                        "' style='text-decoration:none; color:black;'>EDIT</a>"?></div>
                    <div id="delete-button">
                        <form method="POST" action="student-delete.php" 
                            onsubmit="return confirm('Are you sure you want to delete this student record? This cannot be undone.');">
                            <input type="hidden" name="studentid" value="<?php echo $student_id; ?>">
                            <button type="submit">DELETE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="student-details-profile-container">
            <div id="student-details-profile">
                <div id="student-details-left">
                    <div id="student-profile-pic"><img src="image/empty-profile.png" height="100" width="100"></div>
                    <div id="student-details-profile-content">
                        <div id="student-profile-id">STUDENT ID: <b><?php echo $student_details['student_id'] ?></b></div>
                        <div id="student-profile-name"><?php echo $student_details['name'] ?></div>
                        <div id="student-profile-course"><?php echo $student_details['programme'] ?></div>
                    </div>
                </div>
                <div id="email-button"><a href="mailto:poong@gmail.com">
                    <img src="image/email.png" height="40px" width="40px" title="Mail Student"></a></div>
            </div>
        </div>

        <div id="student-details-content-container">
            <div id="student-details-content">
                <div id="personal-details-container">
                    <div id="personal-details">
                        <div class="section-header">PERSONAL DETAILS</div><br>
                        <div><label>GENDER</label></div>
                        <div class="personal-details-data"><?php echo $student_details['gender'] ?></div><br>
                        <div><label>DATE OF BIRTH</label></div>
                        <div class="personal-details-data"><?php echo $student_details['date_of_birth'] ?></div>
                    </div>
                    <div id="personal-email">
                        <div class="section-header">PERSONAL EMAIL</div><br>
                        <div><span class="personal-details-data"><?php echo $student_details['personal_email'] ?></span></div>
                    </div>
                </div>
                <div id="course-details-container">
                    <div class="course-details-row">
                        <div id="course-details">
                            <div class="section-header">COURSE DETAILS</div><br>
                            <div class="course-details-content">
                                <div id="course-details-content">
                                    <label>FACULTY</label>
                                    <div class="personal-details-data" style="font-size: 20px; color: rgb(20, 20, 152);">
                                        <?php echo $student_details['faculty'] ?></div>
                                </div>
                                <div id="course-details-content">
                                    <label>PROGRAMME</label>
                                    <div class="personal-details-data" style="font-size: 20px; color: rgb(20, 20, 152);">
                                        <?php echo $student_details['programme'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="course-details-row">
                        <div class="partition-section">
                            <div class="section-header">CONTACT NO</div><br>
                            <div class="personal-details-data"><?php echo $student_details['contact_no'] ?></div>
                            <div class="contact-owner">Primary Mobile</div>
                        </div>
                        <div class="partition-section">
                            <div class="section-header">EMERGENCY CONTACT NO</div><br>
                            <div class="personal-details-data"><?php echo $student_details['emergency_contact_no'] ?></div>
                            <div class="contact-owner"><?php echo $student_details['emergency_contact_relation'] ?></div>
                        </div>
                    </div>
                    <div class="course-details-row">
                        <div class="partition-section" style="background-color: rgba(206, 215, 223, 0.829); height: 45%">
                            <div class="section-header">UNIVERSITY EMAIL</div><br>
                            <div><span class="personal-details-data"><?php echo $student_details['school_email'] ?></span>
                            </div>
                        </div>
                        <div class="partition-section">
                            <div class="section-header">CURRENT ADDRESS</div><br>
                            <div class="personal-details-data"><?php echo $student_details['address'] ?></div>
                            <div id="map"><br>
                                <label>LOCATION MAP</label>
                                <iframe 
                                    width="270" 
                                    height="160" 
                                    style="border:0; border-radius: 8px;" 
                                    src="https://maps.google.com/maps?q=<?php echo $encoded_address; ?>&t=&z=15&ie=UTF8&iwloc=&output=embed">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="js/sidebar.js"></script>

</body>
</html>