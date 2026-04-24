<?php
session_start(); 
include("config.php");

if (!isset($_SESSION['user'])) {
    header("Location: LoginMenu.php");
    exit();
}

$assessor_id = $_SESSION['user'];

// Fetch assessor's name and role from users table
$user_stmt = $conn->prepare("SELECT name, role FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $assessor_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$assessor = $user_result->fetch_assoc();
$assessor_name = $assessor['name'] ?? 'Unknown';
$assessor_role = $assessor['role'] ?? '';

$query = "
    SELECT s.student_id, s.name, i.internship_id,
        CASE 
            WHEN i.internal_assessor_id = ? THEN 'lecturer'
            WHEN i.external_assessor_id = ? THEN 'supervisor'
        END AS role
    FROM students s
    JOIN internships i ON s.student_id = i.student_id
    WHERE (i.internal_assessor_id = ? OR i.external_assessor_id = ?)
      AND i.current_status = 'Ongoing'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $assessor_id, $assessor_id, $assessor_id, $assessor_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Read and clear flash messages
$flash_success = $_SESSION['success'] ?? '';
$flash_error   = $_SESSION['error']   ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internship Assessment</title>
    <link rel="stylesheet" href="css/markentry.css">
</head>
<body>
    <div id="main">
        <header>
            <h1 style="margin: 0; padding-left: 10px;">Internship Assessment</h1>
            <div><a href="logout.php" title="Logout" id="logout-button"><img src="image/logout-button.png" width="50" height="50"></a></div>
        </header>

        <div class="content">

            <?php if ($flash_success): ?>
                <div style="padding:14px 18px; background:#d4edda; border:1px solid #28a745;
                            border-radius:8px; color:#155724; margin-bottom:16px;">
                    ✅ <?= htmlspecialchars($flash_success) ?>
                </div>
            <?php endif; ?>

            <?php if ($flash_error): ?>
                <div style="padding:14px 18px; background:#f8d7da; border:1px solid #dc3545;
                            border-radius:8px; color:#721c24; margin-bottom:16px;">
                    ❌ <?= htmlspecialchars($flash_error) ?>
                </div>
            <?php endif; ?>

            <?php if (count($students) === 0): ?>
                <div style="padding:14px 18px; background:#fff3cd; border:1px solid #ffc107;
                            border-radius:8px; color:#856404; margin-bottom:16px;">
                    <strong>No ongoing internships found.</strong>
                    You have no students with an <em>Ongoing</em> internship assigned to you.
                </div>
            <?php endif; ?>

            <form id="assessmentForm" action="save_assessment.php" method="POST"
                  onsubmit="return validateForm()">

                <input type="hidden" name="assessor_id"   id="hiddenAssessorId"   value="<?= $assessor_id ?>">
                <input type="hidden" name="student_id"    id="hiddenStudentId"    value="">
                <input type="hidden" name="internship_id" id="hiddenInternshipId" value="">
                <input type="hidden" name="role"          id="hiddenRole"         value="">
                <input type="hidden" name="total_score"   id="hiddenTotalScore"   value="0">

                <div class="identification-card">
                    <div class="card-header">
                        <span class="card-icon">&#128100;</span>
                        <h2><?= htmlspecialchars($assessor_name) ?></h2>
                    </div>
                    <div class="card-body">
                        <div class="field-group">
                            <label>ROLE</label>
                            <div id="roleDisplay" style="font-size:15px; padding:12px 16px;
                                 background:#f0f2f5; border-radius:8px;">
                                — detected automatically —
                            </div>
                        </div>
                        <div class="field-group">
                            <label for="studentSelect">SELECT STUDENT</label>
                            <select id="studentSelect" onchange="detectRole()">
                                <option value="">-- Select a student --</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['student_id'] ?>"
                                        data-role="<?= $student['role'] ?>"
                                        data-internship="<?= $student['internship_id'] ?>">
                                        <?= $student['student_id'] ?> – <?= htmlspecialchars($student['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <table id="assessmentTable" style="display:none;">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Weight</th>
                            <th>Score (1–5)</th>
                            <th>Weighted Contribution</th>
                            <th>Evaluation Notes</th>
                        </tr>
                    </thead>
                    <tbody id="assessmentBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total Score</strong></td>
                            <td id="totalScore">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="submit-area">
                    <button type="button" onclick="resetForm()">Reset</button>
                    <button type="submit" id="submitBtn" style="display:none;">
                        Save Assessment
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script src="js/assessor.js?v=4"></script>
    <script>
        function toggleSidebar() {
            document.getElementById("mySidebar").classList.toggle("show");
            document.getElementById("overlay").classList.toggle("show");
        }

        function detectRole() {
            const select       = document.getElementById("studentSelect");
            const selected     = select.options[select.selectedIndex];
            const role         = selected.getAttribute("data-role");
            const internshipId = selected.getAttribute("data-internship");
            const studentId    = selected.value;

            document.getElementById("hiddenStudentId").value    = studentId    || "";
            document.getElementById("hiddenInternshipId").value = internshipId || "";
            document.getElementById("hiddenRole").value         = role         || "";

            const roleDisplay = document.getElementById("roleDisplay");
            if (role === "lecturer") {
                roleDisplay.textContent = "Lecturer (Internal Assessor)";
            } else if (role === "supervisor") {
                roleDisplay.textContent = "Industry Supervisor (External Assessor)";
            } else {
                roleDisplay.textContent = "— detected automatically —";
            }

            document.getElementById("assessmentTable")
                    .setAttribute("data-internship", internshipId || "");

            if (role) {
                document.getElementById("assessmentTable").style.display = "table";
                document.getElementById("submitBtn").style.display       = "inline-block";
                buildAssessmentTable();
            } else {
                document.getElementById("assessmentTable").style.display = "none";
                document.getElementById("submitBtn").style.display       = "none";
            }
        }
    </script>
</body>
</html>