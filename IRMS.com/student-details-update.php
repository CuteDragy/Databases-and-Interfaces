<?php 
    include('db.php');
    include('auth-check.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $date_of_birth = $_POST['date_of_birth'];
        $faculty = $_POST['faculty'];
        $programme = $_POST['programme'];
        $contact_no = $_POST['contact_no'];
        $emergency_contact_no = $_POST['emergency_contact_no'];
        $emergency_contact_relation = $_POST['emergency_contact_relation'];
        $personal_email = $_POST['personal_email'];
        $school_email = $_POST['school_email'];
        $address= $_POST['address'];

        $sql = "UPDATE students SET name=?, gender=?, date_of_birth=?, faculty=?, programme=?, contact_no=?, 
                emergency_contact_no=?, emergency_contact_relation=?, personal_email=?, school_email=?, address=? 
                WHERE student_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssssssssssi", $name, $gender, $date_of_birth, $faculty, $programme, $contact_no,
                            $emergency_contact_no, $emergency_contact_relation, $personal_email, $school_email, $address,
                            $student_id);

        if($stmt->execute()){
            $stmt->close();
            header("Location: student-profile.php?id=" . urlencode($student_id) . "&status=updated");
            exit();
        } else {
            $error = $stmt->error;
            $stmt->close();
            header("Location: student-details-edit.php?studentid=" . urlencode($student_id) . "&status=error&msg=" . urlencode("Failed to update student record " . $error));
            exit();
        }
    }
?>