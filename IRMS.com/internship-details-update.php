<?php 
    include('db.php');

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

        $sql = "UPDATE internships SET student_id=?, internal_assessor_id=?, external_assessor_id=?, company_id=?, 
                startDate=?, endDate=?, duration=?, current_status=? WHERE internship_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("iiiissisi", $student_id, $internal_assessor_id, $external_assessor_id, $company_id, $startDate, $endDate, $duration,
                            $current_status, $internship_id);

        if($stmt->execute()){
            header("Location: internship-details.php?internshipid=" . urlencode($internship_id) . "&status=updated");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    }
?>