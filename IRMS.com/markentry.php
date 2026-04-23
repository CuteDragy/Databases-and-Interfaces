<?php
session_start();
$dbName     = 'internship_management';
$serverName = 'localhost';
$dbUser     = 'root';
$dbPassword = 'root';
$conn = new mysqli($serverName, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Database connection failed: " . mysqli_connect_error());
}

$assessor_id = 1;

// Fetch students assigned to this assessor
$query = "
    SELECT s.student_id, s.name, i.internship_id,
        CASE 
            WHEN i.internal_assessor_id = ? THEN 'lecturer'
            WHEN i.external_assessor_id = ? THEN 'supervisor'
        END AS role
    FROM students s
    JOIN internships i ON s.student_id = i.student_id
    WHERE i.internal_assessor_id = ? OR i.external_assessor_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $assessor_id, $assessor_id, $assessor_id, $assessor_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Internship Management</title>
    <link rel="stylesheet" href="markentry.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="mySidebar">
        <p class="sidebar-greeting">Hello, Test User</p>
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <div class="sidebar-menu">
            <p class="sidebar-menu-title">Menu</p>
            <a href="admin-index.html">Home</a>
            <a href="user-profile.html">User Profile</a>
            <a href="student-profile.html">Student Profile</a>
            <a href="assessor-profile.html">Assessor Profile</a>
            <a href="internships.html">Internships</a>
            <a href="companies.html">Companies</a>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div id="main">
        <header>
            <div style="display: flex; align-items: center;">
                <button class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
                <h1 style="margin: 0; padding-left: 10px;">Internship Assessment</h1>
            </div>
        </header>

        <div class="content">

            <div class="identification-card">
                <div class="card-header">
                    <span class="card-icon">&#128100;</span>
                    <h2>Assessment Identification</h2>
                </div>
                <div class="card-body">
                    <div class="field-group">
                        <label for="roleSelect">ROLE</label>
                        <select id="roleSelect" onchange="onRoleChange()">
                            <option value="">-- Select a role --</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="supervisor">Industry Supervisor</option>
                        </select>
                        <input type="hidden" id="roleValue" value="">
                    </div>
                    <div class="field-group">
                        <label for="studentSelect">SELECT STUDENT</label>
                        <select id="studentSelect">
                            <option value="">-- Select a student --</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['student_id'] ?>"
                                    data-role="<?= $student['role'] ?>"
                                    data-internship="<?= $student['internship_id'] ?>">
                                    <?= $student['student_id'] ?> - <?= htmlspecialchars($student['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Assessment Table -->
            <table id="assessmentTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Component</th>
                        <th>Weight</th>
                        <th>Score (1-5)</th>
                        <th>Weighted Contribution</th>
                        <th>Evaluation Notes</th>
                    </tr>
                </thead>
                <tbody id="assessmentBody">
                    <!-- Populated by JS -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total Score</td>
                        <td id="totalScore">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Buttons -->
            <div class="submit-area">
                <button onclick="resetForm()">Reset</button>
                <button id="submitBtn" onclick="submitAssessment()" style="display:none;">Save Assessment</button>
            </div>

        </div>
    </div>

    <script src="assessor.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById("mySidebar").classList.toggle("show");
            document.getElementById("overlay").classList.toggle("show");
        }

        function onRoleChange() {
            const role = document.getElementById("roleSelect").value;
            document.getElementById("roleValue").value = role;

            if (role) {
                document.getElementById("assessmentTable").style.display = "table";
                document.getElementById("submitBtn").style.display = "inline-block";
                buildAssessmentTable();
            } else {
                document.getElementById("assessmentTable").style.display = "none";
                document.getElementById("submitBtn").style.display = "none";
            }
        }
    </script>
</body>
</html>
