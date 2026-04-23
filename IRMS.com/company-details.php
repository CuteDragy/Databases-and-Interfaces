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
    
    $formatted_id = sprintf('%03d', $company_id);
    $encoded_address = urlencode($company_details['company_address'] . "Malaysia");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Company Details</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/company-details.css">
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
                    <td style="padding-left: 15px;"><h1>Company Details</h1></td>
                </tr>
            </table>
            <div><a href="logout.php" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="company-detail-header">
            <div id="company-detail-header-content">
                <div>
                    <table>
                        <tr>
                            <td style="padding-right: 15px;"><a href="companies.php" title="Back" Style="color: black; text-decoration: none; font-weight: 500;">
                                &larr;</a></td>
                            <td style="font-size: 20px; font-weight: bold;">Company Information</td>
                        </tr>
                    </table> 
                </div>
                <div style="padding-right: 20px;"><?php echo "<a href='company-details-edit.php?companyid=". $company_id . 
                    "' style='text-decoration:none; color:black;'>EDIT</a>"?></div>
            </div>
        </div>

        <div id="company-intro-container">
            <div id="company-intro">
                <div id="label">Institution Profile</div>
                <div id="company-name" style="font-size: 28px; font-weight: bold;"><?php echo $company_details['company_name'] ?></div>
                <div id="company-industry"><?php echo $company_details['industry'] ?></div>
            </div>
        </div>

        <div id="company-details-container">
            <div id="company-details">
                <div id="company-info-container">
                    <div id="company-info">
                        <div style="font-size: 13px; color: black; font-weight: bold;">
                            COMPANY INFORMATION</div>
                        <br>
                        <div style="font-size: 12px; color: rgb(191, 191, 191); font-weight: bold;">
                            Company ID</div>
                        <div style="font-weight: bold; padding-left: 2px;">#<?php echo $formatted_id ?></div><br>
                        <div style="font-size: 12px; color: rgb(191, 191, 191); font-weight: bold; border-top: 1px solid rgba(211, 211, 211, 0.7); padding-top: 10px;">
                            Industry</div>
                        <div style="font-weight: bold; padding-left: 2px;"><?php echo $company_details['industry'] ?></div>
                    </div>
                    <div id="company-student-no">
                        <div style="font-size: 12px; color: rgb(132, 121, 93); font-weight: bold;">
                            Number of students worked in this Company</div><br>
                        <div style="font-weight: bold; font-size: 26px; color: rgb(22, 22, 141);">100</div>
                    </div>
                </div>
                <div id="company-contact-info-container">
                    <div id="company-contact-info">
                        <div style="font-size: 18px; font-weight: bold;">Contact Information</div>
                        <div id="main-content">
                            <div class="content-row">
                                <div class="content">
                                    <label>PERSON IN CHARGE</label>
                                    <div><?php echo $company_details['person_in_charge'] ?></div>
                                </div>
                                <div class="content">
                                    <label>CONTACT NO</label>
                                    <div><?php echo $company_details['contact_no'] ?></div>
                                </div>
                            </div>
                            <div class="content-row">
                                <div class="content">
                                    <label>COMPANY EMAIL</label>
                                    <div><?php echo $company_details['company_email'] ?></div>
                                </div>
                                <div class="content">
                                    <label>COMPANY ADDRESS</label>
                                    <div><?php echo $company_details['company_address'] ?></div>
                                </div>
                            </div>
                            <div class="content-row">
                                <div class="content">
                                    <label>LOCATION MAP</label>
                                    <iframe 
                                        width="390" 
                                        height="150" 
                                        style="border:0; border-radius: 8px;" 
                                        src="https://maps.google.com/maps?q=<?php echo $encoded_address; ?>&t=&z=15&ie=UTF8&iwloc=&output=embed">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="js/sidebar.js"></script>

</body>
</html>