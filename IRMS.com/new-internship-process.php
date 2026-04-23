<?php 
    include('config.php');

    $assessment_query = "SELECT MAX(assessment_id) AS max_id FROM assessments";
    $assessor_stmt = mysqli_query($conn, $assessment_query) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($assessor_stmt);

    $next_id = ($row['max_id'] ?? 0) + 1;
    $second_id = $next_id + 1; 

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $internship_id = $_POST['internship_id'];    
        $student_id = $_POST['student_id'];
        $internal_assessor_id = $_POST['internal_assessor_id'];
        $external_assessor_id = $_POST['external_assessor_id'];
        $company_id = $_POST['company_id'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $duration = $_POST['duration'];
        $current_status = $_POST['current_status'];

        $sql = "INSERT INTO internships (internship_id, student_id, internal_assessor_id, external_assessor_id,
                 company_id, startDate, endDate, duration, current_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("iiiiissis", $internship_id, $student_id, $internal_assessor_id, $external_assessor_id, 
                        $company_id, $startDate, $endDate, $duration, $current_status);

        if(!$stmt->execute()){
            $error = $stmt->error;
            $stmt->close();
            header("Location: new-internship.php?status=error&msg=" . urlencode("Failed to create internship record " . $error));
            exit();
        }

        $assessment_stmt = "INSERT INTO assessments (assessment_id, internship_id, assessor_id) 
                            VALUES (?, ?, ?)";        
        
        $assessment_query = $conn->prepare($assessment_stmt);
        
        $assessment_query->bind_param("iii", $next_id, $internship_id, $internal_assessor_id);
        if(!$assessment_query->execute()){
            $error = $assessment_query->error;
            $assessment_query->close();
            header("Location: new-internship.php?status=error&msg=" . urlencode("Created internship record but fail to insert assessment 1 " . $error));
            exit();
        }

        $assessment_query->bind_param("iii", $second_id, $internship_id, $external_assessor_id);
        if(!$assessment_query->execute()){
            $error = $assessment_query->error;
            $assessment_query->close();
            header("Location: new-internship.php?status=error&msg=" . urlencode("Created internship record but fail to insert assessment 2 " . $error));
            exit();
        }else{
            $assessment_query->close();
            header("Location: internships.php?internshipid=" . urlencode($internship_id) . "&status=success");
            exit();

        }
    }
?>