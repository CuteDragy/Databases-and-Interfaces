<?php 
    include('config.php');

    $hashOptions = ['cost' => 12 ];

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $h_password = password_hash($password, PASSWORD_DEFAULT, $hashOptions);
        $email = $_POST['email'];
        $organization = $_POST['organization'];

        $sql = "UPDATE users SET name=?, role=?, password=?, email=?, organization=? WHERE user_id=?"; 
        

        $stmt = $conn->prepare($sql);


        $stmt->bind_param("sssssi", $name, $role, $h_password, $email, $organization, $user_id);

        if($stmt->execute()){
            $stmt->close();
            header("Location: assessor-profile.php?assessorid=" . urlencode($user_id) . "&status=success");
            exit();
        } else {
            $error = $stmt->error;
            $stmt->close();
            header("Location: assessor-details-edit.php?assessorid=" . urlencode($user_id) . "&status=error&msg=" . urlencode("Failed to update assessor account " . $error));
            exit();
        }

    }
?>