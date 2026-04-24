<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: LoginMenu.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: markentry.php");
    exit();
}

$assessor_id   = (int) $_SESSION['user'];
$internship_id = (int) ($_POST['internship_id'] ?? 0);
$role          = trim($_POST['role'] ?? '');
$total_score   = (float) ($_POST['total_score'] ?? 0);

if ($internship_id === 0 || $role === '') {
    $_SESSION['error'] = "Missing internship or role. Please select a student.";
    header("Location: markentry.php");
    exit();
}

//scores[0..7] and notes [0..7] from assessor.js
$scores = $_POST['scores'] ?? [];
$notes  = $_POST['notes']  ?? [];

if (count($scores) < 8) {
    $_SESSION['error'] = "Incomplete scores submitted. Please fill in all criteria.";
    header("Location: markentry.php");
    exit();
}

// Weights matching SHARED_CRITERIA order in assessor.js
$weights = [10, 10, 10, 15, 10, 15, 15, 15];

$weighted = [];
$calc_total = 0;
for ($i = 0; $i < 8; $i++) {
    $score = (float) ($scores[$i] ?? 0);
    $w     = ((float) $weights[$i] / 5) * $score;
    $weighted[$i] = $w;
    $calc_total  += $w;
}

// Map to named DB columns
$undertaking_projects       = $weighted[0];
$health_safety_requirements = $weighted[1];
$knowledge                  = $weighted[2];
$report                     = $weighted[3];
$language_clarity           = $weighted[4];
$lifelong_activities        = $weighted[5];
$project_management         = $weighted[6];
$time_management            = $weighted[7];
   
$comment_parts = [];
for ($i = 0; $i < 8; $i++) {
    $note = trim($notes[$i] ?? '');
    if ($note !== '') {
        $comment_parts[] = $note;
    }
}
$comments = implode(" ", $comment_parts);


$check = mysqli_prepare($conn,
    "SELECT COUNT(*) FROM assessments WHERE internship_id = ? AND assessor_id = ?");
mysqli_stmt_bind_param($check, "ii", $internship_id, $assessor_id);
mysqli_stmt_execute($check);
mysqli_stmt_bind_result($check, $count);
mysqli_stmt_fetch($check);
mysqli_stmt_close($check);


if ($count > 0) {
    $stmt = mysqli_prepare($conn, "
        UPDATE assessments SET
            undertaking_projects       = ?,
            health_safety_requirements = ?,
            knowledge                  = ?,
            report                     = ?,
            language_clarity           = ?,
            lifelong_activities        = ?,
            project_management         = ?,
            time_management            = ?,
            total_score                = ?,
            comments                   = ?
        WHERE internship_id = ? AND assessor_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "dddddddddsii",
        $undertaking_projects, $health_safety_requirements, $knowledge,
        $report, $language_clarity, $lifelong_activities,
        $project_management, $time_management,
        $calc_total, $comments,
        $internship_id, $assessor_id
    );
} else {
    $stmt = mysqli_prepare($conn, "
        INSERT INTO assessments
            (internship_id, assessor_id,
             undertaking_projects, health_safety_requirements, knowledge,
             report, language_clarity, lifelong_activities,
             project_management, time_management,
             total_score, comments)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiddddddddds",
        $internship_id, $assessor_id,
        $undertaking_projects, $health_safety_requirements, $knowledge,
        $report, $language_clarity, $lifelong_activities,
        $project_management, $time_management,
        $calc_total, $comments
    );
}

if (mysqli_stmt_execute($stmt)) {
    $assessment_id = ($count > 0)
        ? (function() use ($conn, $internship_id, $assessor_id) {
            $q = mysqli_prepare($conn, "SELECT assessment_id FROM assessments WHERE internship_id = ? AND assessor_id = ?");
            mysqli_stmt_bind_param($q, "ii", $internship_id, $assessor_id);
            mysqli_stmt_execute($q);
            mysqli_stmt_bind_result($q, $id);
            mysqli_stmt_fetch($q);
            mysqli_stmt_close($q);
            return $id;
          })()
        : mysqli_insert_id($conn);

    $_SESSION['success'] = "Assessment saved! (ID: $assessment_id, Total: " . number_format($calc_total, 2) . ")";
    header("Location: markentry.php");
} else {
    $_SESSION['error'] = "Failed to save: " . mysqli_stmt_error($stmt);
    header("Location: markentry.php");
}
exit();
