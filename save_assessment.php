<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

// Debug — remove after fixing
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received', 'raw' => $raw]);
    exit();
}

if (!isset($data['criteria']) || !is_array($data['criteria'])) {
    echo json_encode(['success' => false, 'message' => 'Missing criteria', 'data' => $data]);
    exit();
}

$assessor_id   = $_SESSION['user_id'];
$internship_id = intval($data['internship_id']);
$role          = $data['role'];
$total_score   = floatval($data['total_score']);
$criteria_json = json_encode($data['criteria']);

$comments = implode(" | ", array_map(function($c) {
    return $c['component'] . ': ' . ($c['notes'] ?? '');
}, $data['criteria']));

// Check if assessment already exists
$check = mysqli_prepare($conn, "SELECT COUNT(*) FROM assessments WHERE internship_id = ? AND assessor_id = ?");
mysqli_stmt_bind_param($check, "ii", $internship_id, $assessor_id);
mysqli_stmt_execute($check);
mysqli_stmt_bind_result($check, $count);
mysqli_stmt_fetch($check);
mysqli_stmt_close($check);

if ($count > 0) {
    // Update existing
    $stmt = mysqli_prepare($conn, "
        UPDATE assessments SET
            role         = ?,
            total_score  = ?,
            criteria     = ?,
            comments     = ?
        WHERE internship_id = ? AND assessor_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "sdssii",
        $role, $total_score, $criteria_json, $comments,
        $internship_id, $assessor_id
    );
} else {
    // Insert new
    $stmt = mysqli_prepare($conn, "
        INSERT INTO assessments
            (internship_id, assessor_id, role, total_score, criteria, comments)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iisdss",
        $internship_id, $assessor_id, $role, $total_score, $criteria_json, $comments
    );
}

if (mysqli_stmt_execute($stmt)) {
    if ($count > 0) {
        $id_query = mysqli_prepare($conn, "SELECT assessment_id FROM assessments WHERE internship_id = ? AND assessor_id = ?");
        mysqli_stmt_bind_param($id_query, "ii", $internship_id, $assessor_id);
        mysqli_stmt_execute($id_query);
        mysqli_stmt_bind_result($id_query, $assessment_id);
        mysqli_stmt_fetch($id_query);
        mysqli_stmt_close($id_query);
    } else {
        $assessment_id = mysqli_insert_id($conn);
    }

    echo json_encode([
        'success'       => true,
        'message'       => 'Assessment saved successfully',
        'assessment_id' => $assessment_id,
        'total_score'   => $total_score,
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save: ' . mysqli_error($conn)]);
}
?>