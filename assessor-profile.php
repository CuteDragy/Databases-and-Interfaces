<?php 
    session_start();
    include('db.php');
    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Assessor Profile</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="assessor-profile.css?v=<?php echo filemtime('style.css');?>">
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
            <a href="assessor-profile.php" id="navigation-button" style="background-color:black; color: white;">Assessor Profile</a>
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
                    <td style="padding-left: 15px;"><h1>Assessor Profiles</h1></td>
                </tr>
            </table>
        </header>

        <?php 
            $search_attempted = isset($_GET['search_term']);
            if($search_attempted && !empty($_GET['search_term'])){
                $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
                $search_condition = "WHERE role = 'Assessor' AND user_id like '$search_term' OR name like '$search_term'";
            }else{
                $search_condition = "WHERE role = 'Assessor'";
            }

            $search_stmt = "select * from users $search_condition";
            $search_req = mysqli_query($conn, $search_stmt) or die(mysqli_error($conn));
        ?>

        <div id="assessor-header-container">
            <div id="assessor-header">
                <div id="assessor-title">
                    <div id="title">Assessor List</div>
                    <div id="description">View and manage internal and external assessors' information.</div>    
                </div>
            </div>
        </div>

        <div id="function-container">
            <div id="functions">
                <div id="search-bar">
                    <img src="image/search-icon.png" height="12" width="12">
                    <input type="text" placeholder="Search assessors..." id="search_term" name="search_term">
                </div>
                <div id="add-button">
                    <?php echo"<a href='add-assessor.php'>+ Add New Assessor</a>"; ?>
                </div>
            </div>
        </div>

        <div id="content-container">
            <div id="content">
                <?php 
                    while($assessors = mysqli_fetch_array($search_req)){
                        $current_assessor_id = $assessors['user_id'];
                        $student_query = "SELECT students.name FROM students 
                                            JOIN internships ON students.student_id = internships.student_id
                                            WHERE internships.current_status = 'Ongoing' AND 
                                            internships.internal_assessor_id = $current_assessor_id OR internships.external_assessor_id = $current_assessor_id";
                        $student_assigned = mysqli_query($conn, $student_query);
                        echo "
                            <div class='content-section'>
                                <div class='assessor-details-content-row'>
                                    <div class='assessor-details'>
                                        <div class='assessor-name'>" . $assessors['name'] . "</div>
                                        <div class='assessor-id'>ID: " . $assessors['user_id'] . "</div>
                                    </div>
                                    <div class='assessor-label'>
                                        <div>ASSESSOR</div>
                                    </div>
                                </div>
                                <div class='content-row'>
                                    <div class='assessor-email'>" . $assessors['email'] . "</div>
                                </div>
                                <div class='content-row'>
                                    <div class='student-list-container'>
                                        <div class='student-list-title'>STUDENTS ASSIGNED</div>
                                        <div class='student-list'>
                                            <div class='student-list-content'>";
                                            if(mysqli_num_rows($student_assigned) > 0){
                                                while($students = mysqli_fetch_array($student_assigned)){
                                                    echo"<div class='student-name'>" . $students['name'] . "</div>";   
                                                }
                                            } else{
                                                echo"<div class='student-name'><i>No student assigned yet</i></div>";
                                            }
                                            echo  "
                                            </div>
                                            <div id='detail-button'>
                                                <a href='assessor-details.php?assessorid=" . $assessors['user_id'] . "'>MORE &rarr;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }?>
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