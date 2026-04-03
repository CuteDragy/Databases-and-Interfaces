<?php 
    session_Start();
    include('db.php');

    if(isset($_GET['studentid'])){
        $student_id = mysqli_real_escape_string($conn, $_GET['studentid']);
        $condition = "WHERE student_id = $student_id";
        $instruction = "SELECT * FROM students $condition" ;
        $action = mysqli_query($conn, $instruction);
        $student_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Edit Student Details</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="student-details-edit.css">
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p
                style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello, Guest</p>
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
                    <td style="padding-left: 15px;"><h1>Editting Student Details</h1></td>
                </tr>
            </table>
        </header>

        <div class="user-detail-edit-container">
            <div class="user-detail-edit">
                <div id="back-button">
                    <a href="student-details.php?studentid=<?php echo $student_id; ?>">X</a>
                </div>
                <h2 style="text-align: center; text-decoration: underline;">Student Details</h2>
                <br>
                <form action="student-details-update.php" method="POST">
                    <div class="student-detail-container">
                        <h4><b>Personal Details</b></h4>
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" value="<?php echo $student_details['student_id'] ?>" 
                            readonly style="cursor:not-allowed"><br>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $student_details['name'] ?>"><br>
                        <label for="gender">Gender:</label>
                        <div id="gender-container">
                            <span>
                                <input type="radio" id="male" name="gender" value="Male"  
                                    <?php echo ($student_details['gender'] == 'Male') ? 'checked' : ''; ?>>
                                <label for="male">Male</label>
                            </span>
                            <span>
                                <input type="radio" id="female" name="gender" value="Female"
                                    <?php echo ($student_details['gender'] == 'Female') ? 'checked' : ''; ?>>
                                <label for="female">Female</label>
                            </span>
                        </div><br>
                        <label for="DoB">Date of Birth:</label>
                        <input type="date" id="DoB" name="date_of_birth" value="<?php echo $student_details['date_of_birth'] ?>"><br>
                    </div><hr>
                    <div class="student-detail-container">
                        <h4><b>Course Details</b></h4>
                        <label for="faculty">Faculty:</label>
                        <input type="text" id="faculty" name="faculty" value="<?php echo $student_details['faculty'] ?>"><br>
                        <label for="programme">Programme:</label>
                        <input type="text" id="programme" name="programme" value="<?php echo $student_details['programme'] ?>"><br>
                    </div><hr>
                    <div class="student-detail-container">
                        <h4><b>Contact Details</b></h4>
                        <label for="contactNO">Contact No:</label>
                        <input type="text" id="contactNO" name="contact_no" value="<?php echo $student_details['contact_no'] ?>"><br>
                        <label for="emergencyNO">Emergency Contact No:</label>
                        <input type="text" id="emergencyNO" name="emergency_contact_no" value="<?php echo $student_details['emergency_contact_no'] ?>"><br>
                        <label for="emergencyRelation">Emergency Contact Relation:</label>
                        <input type="text" id="emergencyRelation" name="emergency_contact_relation" value="<?php echo $student_details['emergency_contact_relation'] ?>"><br>
                        <label for="email">Personal Email:</label>
                        <input type="email" id="email" name="personal_email" value="<?php echo $student_details['personal_email'] ?>"><br>
                        <label for="schoolEmail">University Email:</label>
                        <input type="email" id="schoolEmail" name="school_email" value="<?php echo $student_details['school_email'] ?>"><br>
                        <label for="address">Address:</label>
                        <textarea id="address" name="address"><?php echo $student_details['address'] ?></textarea><br><br>
                    </div>
                    <input type="submit" id="submit-button" name="submit-button" value="Save Changes">
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            const overlay = document.getElementById("overlay");

            // Toggle the 'show' class
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        }
    </script>

</body>
</html>