<?php 
    session_start();
    include('db.php');
    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    $search_attempted = isset($_GET['search_term']);
    if($search_attempted && !empty($_GET['search_term'])){
        $search_term = mysqli_real_escape_string($conn, $_GET['search_term']);
        $search_condition = "WHERE company_name like '%$search_term%'";
    }else{
        $search_condition = "";
    }

    $search_stmt = "select * from companies $search_condition";
    $search_req = mysqli_query($conn, $search_stmt) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Index | Companies</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/companies.css?v=<?php echo filemtime('style.css');?>">
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
            <a href="companies.php" id="navigation-button" style="background-color:black; color: white;">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div id="main">
        <header>
            <table>
                <tr>
                    <td style="padding: 5px;"><button class="menu-btn" onclick="toggleSidebar()">&#9776;</button></td>
                    <td style="padding-left: 15px;"><h1>Companies</h1></td>
                </tr>
            </table>
            <div><a href="#" title="Logout"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div id="internship-header-container">
            <div id="internship-header">
                <div id="internship-title">
                    <div id="title">Company List</div>
                    <div id="description">Manage institutional partners and industry contacts.</div>    
                </div>
                <div id="functions">
                    <div id="add-button"><a href="new-company.php">+ Add New Company</a></div>
                </div>
            </div>
        </div>
        <div id="search-bar-container">
            <div id="search-bar-outer">
                <div id="search-bar-inner">
                    <img src="image/search-icon.png" height="12" width="12">
                    <input type="text" placeholder="Search internships..." id="search_term" name="search_term">
                </div>
            </div>
        </div>

        <div id='content-container'>
            <div id='content'>
            <?php 
            while($companies = mysqli_fetch_array($search_req)){
                $company_id = $companies['company_id'];
                $formatted_company_id = sprintf('%03d', $company_id);
                echo "
                    <div class='content-section'>
                        <div class='internship-content-row'>
                            <div class='company-data'>
                                <label>COMPANY ID</label>
                                <div class='company-id'>#" . $formatted_company_id . "</div>
                            </div>
                        </div>
                        <div class='content-row'>
                            <div class='company-name'>
                                <label>COMPANY NAME</label>
                                <div>" . $companies['company_name'] . "</div>
                            </div>
                        </div>
                        <div class='content-row'>
                            <label>PERSON IN CHARGE</label>
                            <div class='person-in-charge'>" . $companies['person_in_charge'] . "</div>
                        </div>
                        <div class='email-content-row'>
                            <div id='company-email'>
                                <div class='email-data'>" . $companies['company_email'] . "</div>
                            </div>
                            <div id='detail-button'>
                                <a href='company-details.php?companyid=$company_id'>DETAILS &rarr;</a>
                            </div>
                        </div>
                    </div>";
                if(mysqli_num_rows($search_req)==0){
                    $term = isset($search_term) ? htmlspecialchars($search_term) : "";
                    echo "<div style='padding: 20px;'>No company with '$term' was found.</div>";
                }
            }
            ?>
            </div>
        </div>
    </div>

    <script src="js/sidebar.js"></script>

</body>
</html>