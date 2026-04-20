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
    <title>Admin Index | New Student</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/add-student.css?v=<?php echo filemtime('style.css');?>">
    <link rel="stylesheet" href="css/formCheck.css">
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
                    <td style="padding-left: 15px;"><h1>New Student</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div class="add-student-container">
            <form action="add-student-process.php" method="POST" id="addStudentForm">
                <div class="add-student">
                    <div id="back-button"><a href="student-profile.php">BACK</a></div>
                    <h2 style="text-align: center; text-decoration: underline;">Student Details</h2>
                    <br>        
                    <div class="add-student-detail-container">
                        <h4><b>Personal Details</b></h4>
                        <span class="error-msg" id="error-student_id"></span>
                        <label for="student_id">STUDENT ID</label>
                        <input type="text" id="student_id" name="student_id"class= required><br>
                        <span class="error-msg" id="error-name"></span>
                        <label for="name">NAME</label>
                        <input type="text" id="name" name="name"><br>
                        <span class="error-msg" id="error-gender"></span>
                        <label for="gender">GENDER</label>
                        <div id="gender-container">
                            <span>
                                <input type="radio" id="male" name="gender" value="Male">  
                                <label for="male">Male</label>
                            </span>
                            <span>
                                <input type="radio" id="female" name="gender" value="Female">
                                <label for="female">Female</label>
                            </span>
                        </div><br>
                        <span class="error-msg" id="error-DoB"></span>
                        <label for="DoB">DATE OF BIRTH</label>
                        <input type="date" id="DoB" name="date_of_birth"><br>
                    </div><hr>
                    <div class="add-student-detail-container">
                        <h4><b>Course Details</b></h4>
                        <span class="error-msg" id="error-faculty"></span>
                        <label for="faculty">FACULTY</label>
                        <input type="text" id="faculty" name="faculty"><br>
                        <span class="error-msg" id="error-programme"></span>
                        <label for="programme">PROGRAMME</label>
                        <input type="text" id="programme" name="programme"><br>
                    </div><hr>
                    <div class="add-student-detail-container">
                        <h4><b>Contact Details</b></h4>
                        <span class="error-msg" id="error-contactNO"></span>
                        <label for="contactNO">CONTACT NO</label>
                        <input type="text" id="contactNO" name="contact_no"><br>
                        <span class="error-msg" id="error-emergencyNO"></span>
                        <label for="emergencyNO">EMERGENCY CONTACT NO</label>
                        <input type="text" id="emergencyNO" name="emergency_contact_no"><br>
                        <span class="error-msg" id="error-emergencyRelation"></span>
                        <label for="emergencyRelation">EMERGENCT CONTACT RELATION</label>
                        <input type="text" id="emergencyRelation" name="emergency_contact_relation"><br>
                        <span class="error-msg" id="error-email"></span>
                        <label for="email">PERSONAL EMAIL</label>
                        <input type="email" id="email" name="personal_email"><br>
                        <span class="error-msg" id="error-schoolEmail"></span>
                        <label for="schoolEmail">UNIVERSITY EMAIL</label>
                        <input type="email" id="schoolEmail" name="school_email"><br>
                        <span class="error-msg" id="error-address"></span>
                        <label for="address">ADDRESS</label><br>
                        <textarea id="address" name="address"></textarea><br><br>
                    </div>
                </div>
                <input type="submit" id="submit-button" class="submit-button" name="submit-button" value="Add Student">
            </form>
        </div>
    </div>

    <script src="js/sidebar.js"></script>
    <script src="js/formCheck.js"></script>

</body>
</html>