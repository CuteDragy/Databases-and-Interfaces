<?php 
    include('db.php');

    $hashOptions = ['cost' => 12];

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $passwords = $_POST['passwords'];
        $h_passwords = password_hash($passwords, PASSWORD_DEFAULT, $hashOptions);
        $email = $_POST['email'];
        $organization = $_POST['organization'];

        $sql = "INSERT INTO users (user_id, name, role, passwords, email, organization) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("isssss", $user_id, $name, $role, $h_passwords, $email, $organization);

        if($stmt->execute()){
            $stmt->close();
            header("Location: assessor-profile.php?id=" . urlencode($user_id) . "&status=success");
            exit();
        } else {
            $error = $stmt->error;
            $stmt->close();
            header("Location: add-assessor.php?status=error&msg=" . urlencode("Failed to create assessor account " . $error));
            exit();
        }
    }
?>