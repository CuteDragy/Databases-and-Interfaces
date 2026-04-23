<?php 
    include('config.php');

    $hashOptions = ['cost' => 12];

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $h_password = password_hash($password, PASSWORD_DEFAULT, $hashOptions);
        $email = $_POST['email'];
        $organization = $_POST['organization'];

        $sql = "INSERT INTO users (user_id, name, role, password, email, organization) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("isssss", $user_id, $name, $role, $h_password, $email, $organization);

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