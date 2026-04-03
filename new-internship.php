<?php 
    session_Start();
    include('db.php');


    if(isset($_GET['internshipid'])){
        $internship_id = mysqli_real_escape_string($conn, $_GET['internshipid']);
        $condition = "WHERE internship_id = $internship_id";
        $instruction = "SELECT * FROM internships $condition" ;
        $action = mysqli_query($conn, $instruction);
        $internship_details = mysqli_fetch_array($action) or die(mysqli_error($conn));
    }
    
    $internship_query = "SELECT MAX(internship_id) AS max_id FROM internships";
    $internship_action = mysqli_query($conn, $internship_query) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($internship_action);

    $next_id = ($row['max_id'] ?? 0) + 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | New Internship</title>
    <link rel="stylesheet" href="admin-sidebar.css">
    <link rel="stylesheet" href="internship-details-edit.css">
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
                    <td style="padding-left: 15px;"><h1>New Internship</h1></td>
                </tr>
            </table>
        </header>

        <div class="internship-details-container">
            <div class="internship-details">
                <div id="back-button"><div><a href="internships.php">X</a></div></div>
                <h2 style="text-align: center; text-decoration: underline;">Internship Details</h2>
                <form method="POST" action="new-internship-process.php">
                    <div class="internship-details-edit-container">
                        <h4><b>Internship Details</b></h4>
                        <label for="internship_id">Internship ID:</label> 
                        <input type="text" id="internship_id" name="internship_id" value="<?php echo $next_id; ?>" readonly><br>
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id"><br>
                        <label for="assessor_id">Assessor ID:</label>
                        <input type="text" id="assessor_id" name="assessor_id"><br>
                        <label for="company_id">Company ID:</label>
                        <input type="text" id="company_id" name="company_id"><br>
                    </div><hr>
                    <div class="internship-details-edit-container">
                        <h4><b>Date and Duration</b></h4>
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="startDate" onchange="calculateDuration()"><br>

                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate" onchange="calculateDuration()"><br>

                        <label for="duration">Duration:</label>
                        <input type="text" id="duration" name="duration" readonly><br>
                        <label for="current_status">Current Status:</label>
                        <div id="current-status-container">
                            <span>
                                <input type="radio" id="Ongoing" name="current_status" value="Ongoing">
                                <label for="Ongoing">Ongoing</label>
                            </span>
                            <span>
                                <input type="radio" id="Completed" name="current_status" value="Completed">
                                <label for="Completed">Completed</label>
                            </span>
                        </div><br>
                    </div>
                    <br>    
                    <input type="submit" id="submit-button" name="submit-button" value="Add Internship">
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("mySidebar");
            const overlay = document.getElementById("overlay");

            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        }

        function calculateDuration() {
            const startInput = document.getElementById('startDate').value;
            const endInput = document.getElementById('endDate').value;
            const durationInput = document.getElementById('duration');

            if (startInput && endInput) {
                const start = new Date(startInput);
                const end = new Date(endInput);

                const diffInMs = end - start;

                const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

                if (diffInDays >= 0) {
                    durationInput.value = diffInDays;
                } else {
                    durationInput.value = "Invalid range";
                }
            }
        }
    </script>

</body>
</html>