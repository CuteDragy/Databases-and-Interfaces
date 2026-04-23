<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$assessor_id    = $_SESSION['user_id'];
$internship_id  = intval($data['internship_id']);
$total_score    = floatval($data['total']);
$comments       = implode(" | ", array_column($data['assessment'], 'comment'));

// Map scores from JS array order to DB columns
$scores = array_column($data['assessment'], 'score');

$undertaking        = intval($scores[0]);
$health_safety      = intval($scores[1]);
$knowledge          = intval($scores[2]);
$report             = intval($scores[3]);
$language_clarity   = intval($scores[4]);
$lifelong           = intval($scores[5]);
$project_mgmt       = intval($scores[6]);
$time_mgmt          = intval($scores[7]);

// Check if assessment already exists for this internship and assessor
$check = mysqli_prepare($conn, "SELECT assessment_id FROM assessments WHERE internship_id = ? AND assessor_id = ?");
mysqli_stmt_bind_param($check, "ii", $internship_id, $assessor_id);
mysqli_stmt_execute($check);
$checkResult = mysqli_stmt_get_result($check);

if (mysqli_num_rows($checkResult) > 0) {
    // Update existing
    $row = mysqli_fetch_assoc($checkResult);
    $stmt = mysqli_prepare($conn, "
        UPDATE assessments SET
            undertaking_projects = ?,
            health_safety_requirements = ?,
            knowledge = ?,
            report = ?,
            language_clarity = ?,
            lifelong_activities = ?,
            project_management = ?,
            time_management = ?,
            total_score = ?,
            comments = ?
        WHERE assessment_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "iiiiiiiiisi",
        $undertaking, $health_safety, $knowledge, $report,
        $language_clarity, $lifelong, $project_mgmt, $time_mgmt,
        $total_score, $comments, $row['assessment_id']
    );
} else {
    // Insert new
    $stmt = mysqli_prepare($conn, "
        INSERT INTO assessments 
        (internship_id, assessor_id, undertaking_projects, health_safety_requirements,
        knowledge, report, language_clarity, lifelong_activities,
        project_management, time_management, total_score, comments)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iiiiiiiiiids",
        $internship_id, $assessor_id, $undertaking, $health_safety,
        $knowledge, $report, $language_clarity, $lifelong,
        $project_mgmt, $time_mgmt, $total_score, $comments
    );
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Assessment saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save: ' . mysqli_error($conn)]);
}
?>