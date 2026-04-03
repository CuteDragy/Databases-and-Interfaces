<?php 
    include('db.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $passwords = $_POST['passwords'];
        $email = $_POST['email'];

        $sql = "INSERT INTO users (user_id, name, role, passwords, email) 
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("issss", $user_id, $name, $role, $passwords, $email);

        if($stmt->execute()){
            header("Location: assessor-profile.php?id=" . urlencode($student_id) . "&status=success");
            exit();
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }
?>