<?php 
    session_start();
    include('db.php');

    $condition = "where user_id = 12345 ";
    $instruction = "select * from users $condition";
    $action = mysqli_query($conn, $instruction) or die(mysqli_error($conn));
    $user_profile = mysqli_fetch_array($action);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | New Assessor</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="add-assessor.css">
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
                    <td style="padding-left: 15px;"><h1>New Assessor</h1></td>
                </tr>
            </table>
        </header>

        <div class="add-assessor-container">
            <div class="add-assessor">
                <div id="back-button"><a href="assessor-profile.php">X</a></div>
                <h2 style="text-align: center; text-decoration: underline;">Assessor Details</h2>
                <br>
                <form action="add-assessor-process.php" method="POST">
                    <div class="assessor-detail-container">
                        <h4><b>Please fill in following details...</b></h4>
                        <label for="user_id">User ID:</label>
                        <input type="text" id="user_id" name="user_id" required><br>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name"><br>
                        <label for="role">Role:</label>
                        <input type="text" id="role" name="role" value="Assessor" readonly><br>
                        <label for="passwords">Password:</label>
                        <input type="password" id="passwords" name="passwords"><br>
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email"><br>
                    </div>
                    <input type="submit" id="submit-button" class="submit-button" name="submit-button" value="Add Assessor">
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