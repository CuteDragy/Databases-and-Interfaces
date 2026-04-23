<?php 
    include('config.php');

    $projects = $safety = $knowledge = $report = $language = $activities = 0; 
    $project_management = $time_management = $total_score = 0;
    $labels = ['project management','projects', 'safety', 'knowledge', 'time management', 'report','language', 'activities'];
    $values = [0,0,0,0,0,0,0,0]; 

    if(isset($_GET['internshipid'])){
        $internship_id = mysqli_real_escape_string($conn, $_GET['internshipid']);
        $instruction = "SELECT * FROM assessments WHERE internship_id = '$internship_id'";
        $action = mysqli_query($conn, $instruction);

        if($action && mysqli_num_rows($action) > 0){
            $records_no = mysqli_num_rows($action);

            while($internship_details = mysqli_fetch_array($action)){
                $projects += $internship_details['undertaking_projects'];
                $safety += $internship_details['health_safety_requirements'];
                $knowledge += $internship_details['knowledge'];
                $report += $internship_details['report'];
                $language += $internship_details['language_clarity'];
                $activities += $internship_details['lifelong_activities'];
                $project_management += $internship_details['project_management'];
                $time_management += $internship_details['time_management'];
                $total_score += $internship_details['total_score']; 
            }

            $projects = ($projects / $records_no) / 10 * 100;
            $safety = ($safety / $records_no) / 10 * 100;
            $knowledge = ($knowledge / $records_no) / 10 * 100;
            $report = ($report / $records_no) / 15 * 100;
            $language = ($language / $records_no) / 10 * 100;
            $activities = ($activities / $records_no) / 15 * 100;
            $project_management = ($project_management / $records_no) / 15 * 100;
            $time_management = ($time_management / $records_no) / 15 * 100;
            $total_score /= $records_no;

            $values = [$project_management, $projects, $safety, $knowledge, $time_management, $report, $language, $activities];
        }
    }
?>