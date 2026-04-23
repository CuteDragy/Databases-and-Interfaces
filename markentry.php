<?php
session_start(); // THIS MUST BE THE FIRST LINE
include("config.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginMenu.php");
    exit();
}

$assessor_id = $_SESSION['user_id'];

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
// DEBUGGING CODE - Remove once fixed
echo "Logged in User ID: " . $assessor_id . "<br>";
if (count($students) === 0) {
    echo "No students found in DB matching this Assessor ID.";
} else {
    echo "Found " . count($students) . " students.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Internship Management</title>
    <link rel="stylesheet" href="markentry.css">
</head>
<body>

    <div class="sidebar" id="mySidebar">
        <p class="sidebar-greeting">Hello, <?= htmlspecialchars($_SESSION['name'] ?? 'Guest') ?></p>
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

    <div id="main">
        <header>
            <div style="display: flex; align-items: center;">
                <button type="button" class="menu-btn" onclick="toggleSidebar()">&#9776;</button>
                <h1 style="margin: 0; padding-left: 10px;">Internship Assessment</h1>
            </div>
        </header>

        <div class="content">

            <!-- ═══════════════════════════════════════════════
                 FORM: submits all visible + hidden data to
                 submitAssessment.php via POST
            ════════════════════════════════════════════════ -->
            <form id="assessmentForm" action="submitAssessment.php" method="POST">

                <!-- Hidden fields populated by JS when a student is selected -->
                <input type="hidden" name="assessor_id"    id="hiddenAssessorId"    value="<?= $assessor_id ?>">
                <input type="hidden" name="student_id"     id="hiddenStudentId"     value="">
                <input type="hidden" name="internship_id"  id="hiddenInternshipId"  value="">
                <input type="hidden" name="role"           id="hiddenRole"          value="">

                <div class="identification-card">
                    <div class="card-header">
                        <span class="card-icon">&#128100;</span>
                        <h2>Assessment Identification</h2>
                    </div>
                    <div class="card-body">
                        <div class="field-group">
                            <label>ROLE</label>
                            <div id="roleDisplay" style="font-size:15px; padding: 12px 16px; background:#f0f2f5; border-radius:8px;">
                                — detected automatically —
                            </div>
                        </div>
                        <div class="field-group">
                            <label for="studentSelect">SELECT STUDENT</label>
                            <!-- Not named — selection drives hidden fields only -->
                            <select id="studentSelect" onchange="detectRole()">
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

                <!--
                    Assessment table is built dynamically by buildAssessmentTable().
                    Each <tr> will contain:
                      <input type="number" name="scores[<component_key>]" ...>
                      <textarea name="notes[<component_key>]" ...></textarea>
                    so they are submitted as arrays keyed by component.
                -->
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
                    <tbody id="assessmentBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total Score</td>
                            <td id="totalScore">0.00</td>
                            <td></td>
                        </tr>
                        <!-- Hidden total so PHP can read the final computed value -->
                        <tr style="display:none;">
                            <td colspan="5">
                                <input type="hidden" name="total_score" id="hiddenTotalScore" value="0">
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="submit-area">
                    <button type="button" onclick="resetForm()">Reset</button>
                    <button type="submit" id="submitBtn" style="display:none;"
                            onclick="return validateAndSync()">
                        Save Assessment
                    </button>
                </div>

            </form>
            <!-- end #assessmentForm -->

        </div>
    </div>

    <script src="assessor.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById("mySidebar").classList.toggle("show");
            document.getElementById("overlay").classList.toggle("show");
        }

        /* ─── detectRole ───────────────────────────────────────────────
           Runs when a student is chosen. Populates hidden fields and
           triggers the table build.
        ──────────────────────────────────────────────────────────────── */
        function detectRole() {
            const select     = document.getElementById("studentSelect");
            const selected   = select.options[select.selectedIndex];
            const role        = selected.getAttribute("data-role");
            const internshipId = selected.getAttribute("data-internship");
            const studentId   = selected.value;

            // ── Populate hidden fields ──
            document.getElementById("hiddenStudentId").value    = studentId    || "";
            document.getElementById("hiddenInternshipId").value = internshipId || "";
            document.getElementById("hiddenRole").value         = role         || "";

            // ── Role display ──
            const roleDisplay = document.getElementById("roleDisplay");
            if (role === "lecturer") {
                roleDisplay.textContent = "Lecturer";
            } else if (role === "supervisor") {
                roleDisplay.textContent = "Industry Supervisor";
            } else {
                roleDisplay.textContent = "— detected automatically —";
            }

            // Keep data-internship on the table (used by assessor.js if needed)
            document.getElementById("assessmentTable")
                    .setAttribute("data-internship", internshipId || "");

            if (role) {
                document.getElementById("assessmentTable").style.display = "table";
                document.getElementById("submitBtn").style.display = "inline-block";
                buildAssessmentTable();   // defined in assessor.js — must produce named inputs
            } else {
                document.getElementById("assessmentTable").style.display = "none";
                document.getElementById("submitBtn").style.display = "none";
            }
        }

        /* ─── validateAndSync ──────────────────────────────────────────
           Called on submit-button click (before the form posts).
           Copies the live total into the hidden total_score field and
           performs basic validation.
           Returns false to cancel submit if validation fails.
        ──────────────────────────────────────────────────────────────── */
        function validateAndSync() {
            // Guard: student must be selected
            if (!document.getElementById("hiddenStudentId").value) {
                alert("Please select a student before saving.");
                return false;
            }

            // Guard: all score inputs must be filled
            const scoreInputs = document.querySelectorAll(
                "#assessmentBody input[name^='scores[']"
            );
            for (const inp of scoreInputs) {
                if (inp.value === "" || isNaN(inp.value)) {
                    alert("Please enter a score for every component.");
                    inp.focus();
                    return false;
                }
            }

            // Sync computed total into hidden field
            const totalText = document.getElementById("totalScore").textContent;
            document.getElementById("hiddenTotalScore").value = parseFloat(totalText) || 0;

            return true; // allow form submission
        }
    </script>
</body>
</html>