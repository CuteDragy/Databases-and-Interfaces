<?php 
    session_start();
    include('db.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    $company_query = "SELECT MAX(company_id) AS max_id FROM companies";
    $company_action = mysqli_query($conn, $company_query) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($company_action);

    $next_id = ($row['max_id'] ?? 0) + 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | New Company</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="new-company.css">
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
                    <td style="padding-left: 15px;"><h1>New Student</h1></td>
                </tr>
            </table>
        </header>

        <div class="new-company-container">
            <div class="new-company">
                <div id="back-button"><a href="companies.php">X</a></div>
                <h2 style="text-align: center; text-decoration: underline;">Company Details</h2>
                <br>
                <form action="new-company-process.php" method="POST">
                    <div class="new-company-detail-container">
                        <h4><b>Company Details</b></h4>
                        <label for="company_id">Company ID:</label>
                        <input type="text" id="company_id" name="company_id" value="<?php echo $next_id; ?>" required><br>
                        <label for="name">Company Name:</label>
                        <input type="text" id="name" name="company_name"><br>
                        <label for="industry">Industry:</label>
                        <input type="text" id="industry" name="industry"><br>
                    </div><hr>
                    <div class="new-company-detail-container">
                        <h4><b>Contact Details</b></h4>
                        <label for="person_in_charge">Person In Charge:</label>
                        <input type="text" id="person_in_charge" name="person_in_charge"><br>
                        <label for="contactNO">Contact No:</label>
                        <input type="text" id="contactNO" name="contact_no"><br>
                        <label for="email">Company Email:</label>
                        <input type="email" id="email" name="company_email"><br>
                        <label for="address">Company Address:</label><br>
                        <textarea id="address" name="company_address"></textarea><br><br>
                    </div>
                    <input type="submit" id="submit-button" class="submit-button" name="submit-button" value="Add Company">
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