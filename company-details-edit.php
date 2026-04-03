<?php 
    session_Start();
    include('db.php');

    if(isset($_GET['companyid'])){
        $company_id = mysqli_real_escape_string($conn, $_GET['companyid']);
        $condition = "WHERE company_id = $company_id";
        $instruction = "SELECT * FROM companies $condition" ;
        $action = mysqli_query($conn, $instruction);
        $company_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Edit Company Details</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="company-details-edit.css">
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <div>
            <p
                style="margin-top: 10px; margin-left: 13px; margin-bottom: 3px; line-height: 1; box-sizing: content-box; font-weight: bold; font-size: 27px; font-style: oblique;">
                Hello, Guest</p>
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
        </header>

        <div class="company-details-edit-container">
            <div class="company-details-edit">
                <div id="back-button">
                    <a href="company-details.php?companyid=<?php echo $company_id; ?>">X</a>
                </div>
                <h2 style="text-align: center; text-decoration: underline;">Company Details</h2>
                <br>
                <form action="company-details-update.php" method="POST">
                    <div class="company-detail-container">
                        <h4><b>Company Details</b></h4>
                        <label for="company_id">Company ID:</label>
                        <input type="text" id="company_id" name="company_id" value="<?php echo $company_details['company_id'] ?>" 
                            readonly style="cursor:not-allowed"><br>
                        <label for="name">Company Name:</label>
                        <input type="text" id="name" name="company_name" value="<?php echo $company_details['company_name'] ?>"><br>
                        <label for="industry">Industry:</label>
                        <input type="text" id="industry" name="industry" value="<?php echo $company_details['industry'] ?>"><br>
                    </div><hr>
                    <div class="company-detail-container">
                        <h4><b>Contact Details</b></h4>
                        <label for="person_in_charge">Person In Charge:</label>
                        <input type="text" id="person_in_charge" name="person_in_charge" value="<?php echo $company_details['person_in_charge'] ?>"><br>
                        <label for="contactNO">Contact No:</label>
                        <input type="text" id="contactNO" name="contact_no" value="<?php echo $company_details['contact_no'] ?>"><br>
                        <label for="company_email">Company Email:</label>
                        <input type="text" id="company_email" name="company_email" value="<?php echo $company_details['company_email'] ?>"><br>
                        <label for="company_address">Company Address:</label>
                        <textarea id="company_address" name="company_address"><?php echo $company_details['company_address'] ?></textarea><br><br>
                    </div>
                    <input type="submit" id="submit-button" name="submit-button" value="Save Changes">
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            const overlay = document.getElementById("overlay");

            // Toggle the 'show' class
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        }
    </script>

</body>
</html>