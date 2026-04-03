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
    
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Index | Edit Internship Details</title>
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
                    <td style="padding-left: 15px;"><h1>Editting Internship Details</h1></td>
                </tr>
            </table>
        </header>

        <div class="internship-details-container">
            <div class="internship-details">
                <div id="back-button"><div><a href="internships.php">X</a></div></div>
                <h2 style="text-align: center; text-decoration: underline;">Internship Details</h2>
                <form method="POST" action="internship-details-update.php">
                    <div class="internship-details-edit-container">
                        <h4><b>Internship Details</b></h4>
                        <label for="internship_id">Internship ID:</label> 
                        <input type="text" id="internship_id" name="internship_id" value="<?php echo $internship_details['internship_id'] ?>" readonly><br>
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" value="<?php echo $internship_details['student_id'] ?>"><br>
                        <label for="internal_assessor_id">Internal Assessor ID:</label>
                        <input type="text" id="internal_assessor_id" name="internal_assessor_id" value="<?php echo $internship_details['internal_assessor_id'] ?>"><br>
                        <label for="external_assessor_id">External Assessor ID:</label>
                        <input type="text" id="external_assessor_id" name="external_assessor_id" value="<?php echo $internship_details['external_assessor_id'] ?>"><br>
                        <label for="company_id">Company ID:</label>
                        <input type="text" id="company_id" name="company_id" value="<?php echo $internship_details['company_id'] ?>"><br>
                    </div><hr>
                    <div class="internship-details-edit-container">
                        <h4><b>Date and Duration</b></h4>
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="startDate" onchange="calculateDuration()" 
                            value="<?php echo date('Y-m-d', strtotime($internship_details['startDate']));?>"><br>
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate" onchange="calculateDuration()" 
                            value="<?php echo date('Y-m-d', strtotime($internship_details['endDate']));?>"><br>
                        <?php $duration = date_diff(date_create($internship_details['startDate']),date_create($internship_details['endDate'])); ?>
                        <label for="duration">Duration:</label>
                        <input type="text" id="duration" name="duration" value="<?php echo $duration -> format('%a days'); ?>"><br>
                        <label for="current_status">Current Status:</label>
                        <div id="current-status-container">
                            <span>
                                <input type="radio" id="Ongoing" name="current_status" value="Ongoing"  
                                    <?php echo ($internship_details['current_status'] == 'Ongoing') ? 'checked' : ''; ?>>
                                <label for="Ongoing">Ongoing</label>
                            </span>
                            <span>
                                <input type="radio" id="Completed" name="current_status" value="Completed"
                                    <?php echo ($internship_details['current_status'] == 'Completed') ? 'checked' : ''; ?>>
                                <label for="Completed">Completed</label>
                            </span>
                        </div>
                    </div><br>    
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

        function calculateDuration() {
            const startInput = document.getElementById('startDate').value;
            const endInput = document.getElementById('endDate').value;
            const durationInput = document.getElementById('duration');

            if (startInput && endInput) {
                const start = new Date(startInput);
                const end = new Date(endInput);

                // Calculate the difference in milliseconds
                const diffInMs = end - start;

                // Convert milliseconds to days
                // 1 day = 24 hours * 60 mins * 60 secs * 1000 ms
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