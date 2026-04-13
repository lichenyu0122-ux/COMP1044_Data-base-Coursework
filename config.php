<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'comp1044_cw_g26';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Kuala_Lumpur');

function checkRole($allowed_roles) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
    
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header('Location: unauthorized.php');
        exit();
    }
}

function calculateTotalScore($student_id, $conn) {
    $query = "SELECT ar.score, ac.weightage 
              FROM assessment_results ar 
              JOIN assessment_criteria ac ON ar.criteria_id = ac.criteria_id 
              WHERE ar.student_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $total += ($row['score'] * $row['weightage'] / 100);
    }
    
    return $total;
}
?>