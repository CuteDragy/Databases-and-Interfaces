<?php 
    include('config.php');

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

        if($stmt->execute()){
            $stmt->close();
            header("Location: internships.php?id=" . urlencode($internship_id) . "&status=success");
            exit();
        } else {
            $error = $stmt->error;
            $stmt->close();
            header("Location: new-internship.php?status=error&msg=" . urlencode("Failed to create internship record " . $error));
            exit();
        }

        $stmt->close();
    }
?>