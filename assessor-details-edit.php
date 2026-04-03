<?php 
    session_start();
    include('db.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);

    if(isset($_GET['assessorid'])){
        $assessor_id = mysqli_real_escape_string($conn, $_GET['assessorid']);
        $assessor_stmt = "SELECT * FROM users WHERE user_id = $assessor_id";
        $assessor_req = mysqli_query($conn, $assessor_stmt);
        $assessor_details = mysqli_fetch_array($assessor_req) or die(mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | New Company</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="assessor-details-edit.css">
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
                    <td style="padding-left: 15px;"><h1>Editting Assessor Details</h1></td>
                </tr>
            </table>
        </header>

        <div class="assessor-details-edit-container">
            <div class="assessor-details-edit">
                <div id="back-button"><?php echo "<a href='assessor-details.php?assessorid=" . $assessor_id . "'>X</a>";?></div>
                <h2 style="text-align: center; text-decoration: underline;">Edit Assessor Details</h2>
                <br>
                <form action="assessor-details-update.php" method="POST">
                    <div class="assessor-detail-container">
                        <h4><b>Please fill in following details...</b></h4>
                        <label for="user_id">User ID:</label>
                        <input type="text" id="user_id" name="user_id" value="<?php echo $assessor_details['user_id'] ?>" required><br>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $assessor_details['name'] ?>"><br>
                        <label for="role">Role:</label>
                        <input type="text" id="role" name="role" value="Assessor" readonly><br>
                        <label for="passwords">Password:</label>
                        <input type="password" id="passwords" name="passwords" value="<?php echo $assessor_details['passwords'] ?>"><br>
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo $assessor_details['email'] ?>"><br>
                        <label for="organization">Organization:</label>
                        <input type="text" id="organization" name="organization" value="<?php echo $assessor_details['organization'] ?>"><br>
                    </div>
                    <input type="submit" id="submit-button" class="submit-button" name="submit-button" value="Update Assessor">
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