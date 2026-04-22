<?php 
    include('db.php');

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

        $sql = "INSERT INTO students (student_id, name, gender, date_of_birth, faculty, programme, contact_no, 
        emergency_contact_no, emergency_contact_relation, personal_email, school_email, address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("isssssssssss", $student_id, $name, $gender, $date_of_birth, $faculty, $programme, $contact_no,
                            $emergency_contact_no, $emergency_contact_relation, $personal_email, $school_email, $address);

        if($stmt->execute()){
            header("Location: student-profile.php?id=" . urlencode($student_id) . "&status=success");
            exit();
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }
?>