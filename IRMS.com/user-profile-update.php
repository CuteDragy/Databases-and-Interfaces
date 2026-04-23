<?php 
    include('db.php');

    $hashOptions = ['cost' => 12 ];

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $passwords = $_POST['passwords'];
        $h_passwords = password_hash($passwords, PASSWORD_DEFAULT, $hashOptions);
        $organization = $_POST['organization'];
        $role = $_POST['role'];
        $email = $_POST['email'];

        $sql = "UPDATE users SET name=?, passwords=?, organization=?, role=?, email=? 
                WHERE user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssssi", $name, $h_passwords, $organization, $role, $email, $user_id);

        if($stmt->execute()){
            $stmt->close();
            header("Location: user-profile.php?status=updated");
            exit();
        } else {
            $error = $stmt->error;
            $stmt->close();
            header("Location: user-profile-edit.php?status=error&msg=" . urlencode("Failed to update user account " . $error));
            exit();
        }
    }
?>