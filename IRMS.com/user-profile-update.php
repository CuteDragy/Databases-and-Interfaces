<?php 
    include('db.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $passwords = $_POST['passwords'];
        $organization = $_POST['organization'];
        $role = $_POST['role'];
        $email = $_POST['email'];

        $sql = "UPDATE users SET name=?, passwords=?, organization=?, role=?, email=? 
                WHERE user_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssssi", $name, $passwords, $organization, $role, $email, $user_id);

        if($stmt->execute()){
            header("Location: user-profile.php?status=updated");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    }
?>