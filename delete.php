<?php
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role_id'] != 1){
    die("Access denied");

}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM Students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
exit();
?>