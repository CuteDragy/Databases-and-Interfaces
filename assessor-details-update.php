<?php 
    include('db.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $passwords = $_POST['passwords'];
        $email = $_POST['email'];
        $organization = $_POST['organization'];

        $sql = "UPDATE users SET name=?, role=?, passwords=?, email=?, organization=? WHERE user_id=?"; 
        

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("sssssi", $name, $role, $passwords, $email, $organization, $user_id);

        if($stmt->execute()){
            header("Location: assessor-profile.php?assessorid=" . urlencode($user_id) . "&status=updated");
            exit();
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }
?>