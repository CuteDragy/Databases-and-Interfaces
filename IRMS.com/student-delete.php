<?php 
    include('config.php');

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location: student-profile.php");
        exit();
    }

    if(isset($_POST['studentid'])){
        $student_id = mysqli_real_escape_string($conn, $_POST['studentid']);

        $delete_stmt = "DELETE from students WHERE student_id = '$student_id'";
        $delete_result = mysqli_query($conn, $delete_stmt);
        
        if($delete_result){
            header("Location: student-profile.php?success=Student+record+deleted+successfully");
            exit();
        }else{
            header("Location: student-details.php?studentid=$student_id&status=error&msg=Failed+to+delete+student");
            exit();
        }
    }else{
        header("Location: student-profile.php");
        exit();
    }
?>