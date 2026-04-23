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

        $hashOptions = ['cost' => 12];
        $role = 'Student';
        $organization = 'University of Nottingham Malaysia';
        $h_passwords = password_hash($student_id, PASSWORD_DEFAULT, $hashOptions);

        $sql = "INSERT INTO students (student_id, name, gender, date_of_birth, faculty, programme, contact_no, 
        emergency_contact_no, emergency_contact_relation, personal_email, school_email, address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("isssssssssss", $student_id, $name, $gender, $date_of_birth, $faculty, $programme, $contact_no,
                            $emergency_contact_no, $emergency_contact_relation, $personal_email, $school_email, $address);

        if(!$stmt->execute()){
            $error = $stmt->error;
            $stmt->close();
            header("Location: add-student.php?status=error&msg=" . urlencode("Failed to add student: " . $error));
            exit();
        }
        $stmt->close();

        $user_sql = "INSERT INTO users (user_id, name, role, passwords, email, organization) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $user_stmt = $conn->prepare($user_sql);

        $user_stmt->bind_param("isssss", $student_id, $name, $role, $h_passwords, $school_email, $organization);

        if(!$user_stmt->execute()){
            $user_error = $user_stmt->error;
            $user_stmt->close();
            header("Location: add-student.php?status=error&msg=" . urlencode("Student added but failed to create user account: " . $user_error));
            exit();
        }
        $user_stmt->close();

        header("Location: student-profile.php?id=" . urlencode($student_id) . "&status=success");
        exit();
    }
?>