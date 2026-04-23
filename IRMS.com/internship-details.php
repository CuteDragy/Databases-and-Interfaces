<?php 
    session_Start();
    include('db.php');
    include('internship-details-result-calculation.php');
    include('auth-check.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    $internship_details = null;
    $external_comments = "";
    $internal_comments = "";
    $formatted_internship_id = "000";
    
    if(isset($_GET['internshipid'])){
        $internship_id = mysqli_real_escape_string($conn, $_GET['internshipid']);
        $condition = "WHERE internship_id = $internship_id";
        $instruction = "SELECT * FROM internships $condition" ;
        $action = mysqli_query($conn, $instruction);
        $internship_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
        
        if($internship_details){
            $external_comment_stmt = "SELECT comments FROM assessments WHERE internship_id = $internship_id AND assessor_id = {$internship_details['external_assessor_id']}";
            $external_comment_query = mysqli_query($conn, $external_comment_stmt);
            $external_comments = mysqli_fetch_array($external_comment_query);
        }
        if($internship_details){
            $internal_comment_stmt = "SELECT comments FROM assessments WHERE internship_id = $internship_id AND assessor_id = {$internship_details['internal_assessor_id']}";
            $internal_comment_query = mysqli_query($conn, $internal_comment_stmt);
            $internal_comments = mysqli_fetch_array($internal_comment_query);
        }
        if($internship_details){
            $company_stmt = "SELECT company_name FROM companies WHERE company_id = {$internship_details['company_id']}";
            $company_query = mysqli_query($conn, $company_stmt);
            $company_req = mysqli_fetch_array($company_query);
            if ($company_req){
                $company_name = $company_req['company_name'];
            }
        }
        if($internship_details){
            $programme_stmt = "SELECT programme FROM students WHERE student_id = {$internship_details['student_id']}";
            $programme_query = mysqli_query($conn, $programme_stmt);
            $programme_req = mysqli_fetch_array($programme_query);
            if($programme_req){
                $programme = $programme_req['programme'];
            }
        }
    }

    $formatted_internship_id = sprintf('%03d', $internship_id);
    $formatted_company_id = sprintf('%03d', $internship_details['company_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Internship Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/internship-details.css?v=<?php echo filemtime('style.css');?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include('error-function.php'); ?>
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p
                style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello,<br> <?php echo $user_profile['name'];?></p>
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
                    <td style="padding-left: 15px;"><h1>Internship Details</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="internship-detail-header">
            <div id="internship-detail-header-content">
                <div>
                    <table>
                        <tr>
                            <td id="back-button" style="padding-right: 15px; font-weight: 900;"><a href="internships.php" title="BACK">&larr;</a></td>
                            <td style="font-size: 20px; font-weight: bold;">Internship Information</td>
                        </tr>
                    </table> 
                </div>
                <div style="padding-right: 20px;"><?php echo "<a href='internship-details-edit.php?internshipid=". $internship_id ."'>EDIT</a>"?></div>
            </div>
        </div>
        
        <div id="main-content-container">
            <div id="main-content">
                <div id="main-content-left-container">
                    <div class="content-row">
                        <div id="internship-details">
                            <div id="programme"><?php echo "" . $programme . " Internship"?></div>
                            <div id="company-name"><span id="company-id"><?php echo $formatted_company_id?></span><?php echo $company_name?></div>
                        </div>
                        <div id="identification-details">
                            <div class="section-header">IDENTIFICATION</div>
                            <div id="student-details">
                                <div>
                                    <label>INTERNSHIP ID</label>
                                    <div class="section-data"><?php echo $formatted_internship_id?></div>
                                </div>
                                <div>
                                    <label>STUDENT ID</label>
                                    <div class="section-data"><?php echo $internship_details['student_id']?></div>
                                </div>
                            </div>
                            <label>ASSESSOR ID</label>
                            <div id="assessor-details">
                                <div id="internal-assessor-id">
                                    <div class="section-data"><?php echo $internship_details['internal_assessor_id']?></div>
                                    <div class="assessor-description">INTERNAL ASSESSOR</div>
                                </div>
                                <div id="external-assessor-id">
                                    <div class="section-data"><?php echo $internship_details['external_assessor_id'] ?></div>
                                    <div class="assessor-description">EXTERNAL ASSESSOR</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-row">
                        <div id="timeline">
                            <div id="timeline-section-header">Timeline & Duration</div>
                            <div id="timeline-section-content">
                                <div>
                                    <label>START DATE</label>
                                    <div class="section-data"><?php echo $internship_details['startDate']?></div>
                                </div>
                                <div>
                                    <label>END DATE</label>
                                    <div class="section-data"><?php echo $internship_details['endDate']?></div>
                                </div>
                                <div>
                                    <label>TOTAL DURATION</label>
                                    <div class="section-data"><?php echo "". $internship_details['duration'] . " days"?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-row">
                        <div id="assessor-feedback-container">
                            <div id="feedback-section-header">Assessor Feedback</div>
                            <div id="assessor-feedback">
                                <?php 
                                    if (!empty(trim($external_comments['comments']))) {
                                        echo "<u>\"" . $external_comments['comments'] . "\"</u>";
                                    } else {
                                        echo "<u><i>\"No comments given yet\"</i></u>";}
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="main-content-right-container">
                    <a href="internship-result.php?internshipid=<?php echo $internship_id; ?>" id="view-result-details" title="MORE DETAILS">
                        <div class="content-row">
                            <div id="result-overview">
                                <div class="section-header">RESULT OVERVIEW</div>
                                <div style="position: relative; width: 34vh; height: 34vh;">
                                    <canvas id="myRadarChart"></canvas>
                                    <div id="average-mark">
                                        <h2 style="margin: 0; color: #3f587e;"><?php echo round($total_score); ?>%</h2>
                                        <small style="color: #666; font-size: 14px;">AVG PERFORM</small>
                                    </div>
                                </div>
                                <div id="result-mark-container">
                                    <label>TOTAL SCORE</label>
                                    <div id="result-mark"><span id="student-mark"><?php echo $total_score ?></span>/100</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="content-row">
                        <div id="internal-comment">
                            <div class="section-header">INTERNAL FEEDBACK</div>
                            <div style="margin-top: 15px;">
                                <?php 
                                    if (!empty(trim($internal_comments['comments']))) {
                                        echo $internal_comments['comments'] ;
                                    } else {
                                        echo "<i>No comments given yet</i>";}
                                ?>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>

    <script src="js/sidebar.js"></script>   
    <?php if ($error_msg): ?>
    <script>
        window.alert("<?php echo $error_msg; ?>");
    </script>
    <?php endif; ?> 
    <script>
        const ctx = document.getElementById('myRadarChart').getContext('2d');

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Performance',
                    data: <?php echo json_encode($values); ?>,
                    fill: true,
                    backgroundColor: 'rgba(63, 88, 126, 0.2)', 
                    borderColor: '#3f587e',                 
                    pointRadius: 0,                         
                    borderWidth: 3
                }]
            },
            options: {
                scales: {
                    r: {
                        angleLines: { display: true, color: '#e0e0e0' },
                        grid: { color: '#e0e0e0', circular: false }, 
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: { display: false } 
                    }
                },
                plugins: {
                    legend: { display: false } 
                }
            }
        });
    </script>

</body>
</html>