<?php 
    session_Start();
    include('db.php');
    include('auth-check.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    if(isset($_GET['companyid'])){
        $company_id = mysqli_real_escape_string($conn, $_GET['companyid']);
        $company_condition = "WHERE company_id = $company_id";
        $company_stmt = "SELECT * FROM companies $company_condition" ;
        $company_query = mysqli_query($conn, $company_stmt);
        $company_details = mysqli_fetch_array($company_query) or die(mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Edit Company Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/company-details-edit.css?v=<?php echo filemtime('style.css');?>">
    <link rel="stylesheet" href="css/formCheck.css">
    <?php include('error-function.php'); ?>
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box;
             font-weight: bold; font-size: 27px; font-style: oblique;">
                <?php echo "Hello, <br>".$user_profile['name']. "" ?></p>
        </div>
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <div style="margin-top: 35px; font-family:Arial, sans-serif;">
            <p style="margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 26px;">Menu</p>
            <a href="admin-index.php" id="navigation-button">Home</a>
            <a href="user-profile.php" id="navigation-button">User Profile</a>
            <a href="student-profile.php" id="navigation-button">Student Profile</a>
            <a href="assessor-profile.php" id="navigation-button">Assessor Profile</a>
            <a href="internships.php" id="navigation-button">Internships</a>
            <a href="companies.php" id="navigation-button">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div id="main">
        <header>
            <table>
                <tr>
                    <td style="padding: 5px;"><button class="menu-btn" onclick="toggleSidebar()">&#9776;</button></td>
                    <td style="padding-left: 15px;"><h1>Editting Company Details</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div class="company-details-edit-container">
            <form action="company-details-update.php" method="POST" id="newCompanyForm">
                <div class="company-details-edit">
                    <div id="back-button">
                        <a href="company-details.php?companyid=<?php echo $company_id; ?>">BACK</a>
                    </div>
                    <h2 style="text-align: center; text-decoration: underline;">Company Details</h2>
                    <br>        
                    <div class="company-detail-container">
                        <h4><b>Company Details</b></h4>
                        <span class="error-msg" id="error-company_id"></span>
                        <label for="company_id">COMPANY ID</label>
                        <input type="text" id="company_id" name="company_id" value="<?php echo $company_details['company_id'] ?>" 
                            readonly style="cursor:not-allowed"><br>
                        <span class="error-msg" id="error-name"></span>
                        <label for="name">COMPANY NAME</label>
                        <input type="text" id="name" name="company_name" value="<?php echo $company_details['company_name'] ?>"><br>
                        <span class="error-msg" id="error-industry"></span>
                        <label for="industry">INDUSTRY</label>
                        <input type="text" id="industry" name="industry" value="<?php echo $company_details['industry'] ?>"><br>
                    </div><hr>
                    <div class="company-detail-container">
                        <h4><b>Contact Details</b></h4>
                        <span class="error-msg" id="error-person_in_charge"></span>
                        <label for="person_in_charge">PERSON IN CHARGE</label>
                        <input type="text" id="person_in_charge" name="person_in_charge" value="<?php echo $company_details['person_in_charge'] ?>"><br>
                        <span class="error-msg" id="error-contactNO"></span>
                        <label for="contactNO">CONTACT NO</label>
                        <input type="text" id="contactNO" name="contact_no" value="<?php echo $company_details['contact_no'] ?>"><br>
                        <span class="error-msg" id="error-email"></span>
                        <label for="company_email">COMPANY EMAIL</label>
                        <input type="text" id="company_email" name="company_email" value="<?php echo $company_details['company_email'] ?>"><br>
                        <span class="error-msg" id="error-address"></span>
                        <label for="company_address">COMPANY ADDRESS</label>
                        <textarea id="company_address" name="company_address"><?php echo $company_details['company_address'] ?></textarea><br><br>
                    </div>
                </div>
                <input type="submit" id="submit-button" name="submit-button" value="SAVE CHANGES">
            </form>
        </div>
    </div>

    <script src="js/sidebar.js"></script>
    <script src="js/formCheck.js"></script>

</body>
</html>