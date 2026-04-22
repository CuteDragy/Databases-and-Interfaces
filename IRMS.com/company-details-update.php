<?php 
    include('db.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-button"])){
        $company_id = $_POST['company_id'];
        $company_name = $_POST['company_name'];
        $industry = $_POST['industry'];
        $person_in_charge = $_POST['person_in_charge'];
        $contact_no = $_POST['contact_no'];
        $company_email = $_POST['company_email'];
        $company_address= $_POST['company_address'];

        $sql = "UPDATE companies SET company_name=?, industry=?, person_in_charge=?, contact_no=?, 
                company_email=?, company_address=? 
                WHERE company_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssssi", $company_name, $industry, $person_in_charge, $contact_no,
                            $company_email, $company_address, $company_id);

        if($stmt->execute()){
            header("Location: company-details.php?companyid=" . urlencode($company_id) . "&status=updated");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
    }
?>