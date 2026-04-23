<?php 
    session_start();
    include('config.php');
    include('auth-check.php');

    $user_id = $_SESSION['user'];
    $condition = "where user_id = $user_id";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    if(isset($_GET['internshipid'])){
        $internship_id = mysqli_real_escape_string($conn, $_GET['internshipid']);
        $internship_stmt = "SELECT * FROM internships WHERE internship_id = $internship_id";
        $internship_query = mysqli_query($conn, $internship_stmt);
        $internship = mysqli_fetch_array($internship_query) or die(mysqli_error($conn));
    }

    $student_id = $internship['student_id'];
    $internal_id = $internship['internal_assessor_id'];
    $external_id = $internship['external_assessor_id'];

    $student_stmt = "SELECT * FROM students WHERE student_id = $student_id";
    $student_query = mysqli_query($conn, $student_stmt);
    $student_details = mysqli_fetch_array($student_query);

    $internal_stmt = "SELECT * FROM assessments WHERE internship_id = $internship_id AND assessor_id = $internal_id";
    $internal_query = mysqli_query($conn, $internal_stmt);
    $internal = mysqli_fetch_array($internal_query);

    $external_stmt = "SELECT * FROM assessments WHERE internship_id = $internship_id AND assessor_id = $external_id";
    $external_query = mysqli_query($conn, $external_stmt);
    $external = mysqli_fetch_array($external_query);

    if (!$internal || !$external) {
        $error_msg = urlencode("Assessment incomplete. One or both assessors have not graded this internship yet.");
        header("Location: internship-details.php?internshipid=$internship_id&error=$error_msg");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Student Profile</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/internship-result.css?v=<?php echo filemtime('style.css');?>">
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
            <a href="student-profile.php" id="navigation-button" style="background-color:black; color: white;">Student Profile</a>
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
                    <td style="padding-left: 15px;"><h1>Student Profiles</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>
        <div id="back-button-container">
            <div id="back-button">
                <a href="internship-details.php?internshipid=<?php echo $internship_id;?>" >&larr; BACK</a>
            </div>
        </div>
        <div id="student-header-container">
            <div id="student-header">
                <div id="student-name">
                    <label>STUDENT RECORD</label>
                    <div id="name"><?php echo $student_details['name'];?></div>    
                </div>
                <div id="student-info">
                    <div>
                        <labeL>STUDENT ID</labeL>
                        <div><?php echo $student_details['student_id'];?></div>
                    </div>
                    <div>
                        <label>COURSE</label>
                        <div><?php echo $student_details['programme'];?></div>
                    </div>
                    <div>
                        <label>INTERNAL ASSESSOR ID</label>
                        <div><?php echo $internship['internal_assessor_id'];?></div>
                    </div>
                    <div>
                        <label>EXTERNAL ASSESSOR ID</label> 
                        <div><?php echo $internship['external_assessor_id'];?></div>
                    </div>
                </div>
            </div>
        </div>


        <div id="content-container">
            <div id="content">
                <table>
                    <tr>
                       <td colspan="3" id="title">Detailed Assessment Marksheet</td> 
                       <td id="print-button"><button onclick="window.print()">PRINT PDF</button></td>
                    </tr>
                    <tr>
                        <th>ASSESSMENT CATEGORY</th>
                        <th>INTERNAL ASSESSOR</th>
                        <th>EXTERNAL ASSESSOR</th>
                        <th>CATEGORICAL SUBTOTAL</th>
                    </tr>
                    <tr>
                        <td class="category">Undertaking Tasks</td>
                        <td><?php echo $internal['undertaking_projects'];?></td>
                        <td><?php echo $external['undertaking_projects'];?></td>
                        <td><?php echo ($internal['undertaking_projects'] + $external['undertaking_projects'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Health and Safety Requirements at the Workplace</td>
                        <td><?php echo $internal['health_safety_requirements'];?></td>
                        <td><?php echo $external['health_safety_requirements'];?></td>
                        <td><?php echo ($internal['health_safety_requirements'] + $external['health_safety_requirements'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Connectivity and Use of Theoretical Knowledge</td>
                        <td><?php echo $internal['knowledge'];?></td>
                        <td><?php echo $external['knowledge'];?></td>
                        <td><?php echo ($internal['knowledge'] + $external['knowledge'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Presentation of the Report as a Written Document</td>
                        <td><?php echo $internal['report'];?></td>
                        <td><?php echo $external['report'];?></td>
                        <td><?php echo ($internal['report'] + $external['report'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Clarity of Language and Illustration</td>
                        <td><?php echo $internal['language_clarity'];?></td>
                        <td><?php echo $external['language_clarity'];?></td>
                        <td><?php echo ($internal['language_clarity'] + $external['language_clarity'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Lifelong Learning Activities</td>
                        <td><?php echo $internal['lifelong_activities'];?></td>
                        <td><?php echo $external['lifelong_activities'];?></td>
                        <td><?php echo ($internal['lifelong_activities'] + $external['lifelong_activities'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Project Management</td>
                        <td><?php echo $internal['project_management'];?></td>
                        <td><?php echo $external['project_management'];?></td>
                        <td><?php echo ($internal['project_management'] + $external['project_management'])/2; ?></td>
                    </tr>
                    <tr>
                        <td class="category">Time Management</td>
                        <td><?php echo $internal['time_management'];?></td>
                        <td><?php echo $external['time_management'];?></td>
                        <td><?php echo ($internal['time_management'] + $external['time_management'])/2; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="finalized-result-container">
            <div id="finalized-result">
                <div id="marks">
                    <div class="mark-container">
                        <label>INTERNAL ASSESSOR TOTAL</label>
                        <div><span class="mark"><?php echo $internal['total_score'];?></span>/100</div>
                    </div>
                    <div class="mark-container">
                        <label>EXTERNAL ASSESSOR TOTAL</label>
                        <div><span class="mark"><?php echo $external['total_score'];?></span>/100</div>
                    </div>
                    <div class="mark-container">
                        <label>WEIGHTED AVERAGE</label>
                        <div>
                            <span class="mark"><?php echo ($internal['total_score'] + $external['total_score'])/2;  ?></span>/100
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <script src="js/sidebar.js"></script>
</body>
</html>