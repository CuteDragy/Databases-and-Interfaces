<?php 
    session_start();
    include('db.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    $search_attempted = isset($_GET['search_term']);
    if($search_attempted && !empty($_GET['search_term'])){
        $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
        $search_condition = "WHERE internship_id like '%$search_term%'";
    }else{
        $search_condition = "";
    }

    $search_stmt = "select * from internships $search_condition";
    $search_req = mysqli_query($conn, $search_stmt) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Internships</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="internships.css">
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
            <a href="internships.php" id="navigation-button" style="background-color:black; color: white;">Internships</a>
            <a href="companies.php" id="navigation-button">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div id="main">
        <header>
            <table>
                <tr>
                    <td style="padding: 5px;"><button class="menu-btn" onclick="toggleSidebar()">&#9776;</button></td>
                    <td style="padding-left: 15px;"><h1>Internships</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="internship-header-container">
            <div id="internship-header">
                <div id="internship-title">
                    <div id="title">Internship List</div>
                    <div id="description">Manage and curate professional internship placements within the acadenic ecosystem</div>    
                </div>
                <div id="functions">
                    <div id="search-bar">
                        <form method="GET" action="internships.php">
                            <img src="image/search-icon.png" height="12" width="12">
                            <input type="text" placeholder="Search internships..." id="search_term" name="search_term">
                        </form>
                    </div>
                    <div id="add-button"><a href="new-internship.php">&#10010; Create New Internship</a></div>
                </div>
                
            </div>
        </div>

        <div id="content-container">
            <div id="content">
                <?php 
                    while($internships = mysqli_fetch_array($search_req)){
                        $student_id = $internships['student_id'];
                        $programme_stmt = "SELECT programme FROM students WHERE student_id = $student_id";
                        $programme_req = mysqli_query($conn,$programme_stmt);
                        $programme = mysqli_fetch_array($programme_req);
                        
                        echo "
                            <div class='content-section'>
                                <div class='internship-content-row'>
                                    <div class='status'>" . strtoupper($internships['current_status']) . "</div>
                                    <div class='internship-details'>
                                        <label>INTERNSHIP ID</label>
                                        <div class='internship-id'>" . $internships['internship_id'] . "</div>
                                    </div>
                                </div>
                                <div class='content-row'>
                                    <div class='internship-label'>
                                        " . $programme['programme'] . " Internship
                                    </div>
                                </div>
                                <div class='content-row'>
                                    <label>START DATE</label>
                                    <div class='date-data'>" . $internships['startDate'] . "</div>
                                </div>
                                <div class='content-row'>
                                    <label>END DATE</label>
                                    <div class='date-data'>" . $internships['endDate'] . "</div>
                                </div>
                                <div class='duration-content-row'>
                                    <div id='duration'>
                                        <label>DURATION</label>
                                        <div class='duration-data'>" . $internships['duration'] . "days</div>
                                    </div>
                                    <div id='detail-button'>
                                        <a href='internship-details.php?internshipid=" . $internships['internship_id'] . "'>DETAILS &rarr;</a>
                                    </div>
                                </div>
                            </div>";
                    } 
                    if(mysqli_num_rows($search_req)==0){
                        $term = isset($search_term) ? htmlspecialchars($search_term) : "";
                        echo "<div style='padding: 20px;'>No internship with '$term' was found.</div>";
                    }
                ?>
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