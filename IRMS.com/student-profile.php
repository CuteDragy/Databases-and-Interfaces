<?php 
    session_start();
    include('db.php');
    include('auth-check.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    $search_attempted = isset($_GET['search_term']);
    if($search_attempted && !empty($_GET['search_term'])){
        $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
        $search_condition = "WHERE student_id like '%$search_term%'";
    }else{
        $search_condition = "";
    }

    $search_stmt = "select * from students $search_condition";
    $search_req = mysqli_query($conn, $search_stmt) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Student Profile</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/student-profile.css?v=<?php echo filemtime('style.css');?>">
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
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="student-header-container">
            <div id="student-header">
                <div id="student-title">
                    <div id="title">Student List</div>
                    <div id="description">Browse and manage student academic records.</div>    
                </div>
            </div>
        </div>

        <div id="function-container">
            <div id="functions">
                <div id="search-bar">
                    <img src="image/search-icon.png" height="12" width="12">
                    <input type="text" placeholder="Search students..." id="search_term" name="search_term">
                </div>
                <div id="add-button"><a href="add-student.php">+ Add New Student</a></div>
            </div>
        </div>

        <div id="content-container">
            <div id="content">
                <?php
                    while($student_profile = mysqli_fetch_array($search_req)){
                        echo "
                            <a href='student-details.php?studentid=" . $student_profile['student_id'] . "'>
                                <div class='content-section'>
                                    <div class='label-content-row'>
                                        <div class='student-label'>STUDENT</div>
                                    </div>
                                    <div class='content-row'>
                                        <div class='student-name'>" . $student_profile['name'] . "</div>
                                    </div>
                                    <div class='content-row'>
                                        <div class='company-name'>
                                            <label>STUDENT ID</label>
                                            <div class='content-data'>" . $student_profile['student_id'] . "</div>
                                        </div>
                                    </div>
                                    <div class='content-row'>
                                        <label>PROGRAMME</label>
                                        <div class='content-data'>" . $student_profile['programme'] . "</div>
                                    </div>
                                    <div class='content-row'>
                                        <div id='company-email'>
                                            <div class='email-data'>" . $student_profile['school_email'] . "</div>
                                        </div>
                                    </div>
                                </div>
                            </a>";
                    }
                    if(mysqli_num_rows($search_req)==0){
                        $term = isset($search_term) ? htmlspecialchars($search_term) : "";
                        echo "<div style='padding: 20px;'>No student with '$term' was found.</div>";
                    }
                ?>
            </div>
        </div>
    </div>
    <script src="js/sidebar.js"></script>
</body>
</html>