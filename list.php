<?php
include '../db.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if ($role_id == 1){
    $sql = "
    SELECT i.*, s.student_name, u.full_name AS assessor_name
    FROM Internships i
    JOIN Students s ON i.student_id = s.student_id
    LEFT JOIN Users u ON i.assessor_id = u.user_id
    ";
    $result = $conn->query($sql);

}else{
    $sql = "
    SELECT i.*, s.student_name, u.full_name AS assessor_name
    FROM Internships i
    JOIN Students s ON i.student_id = s.student_id
    LEFT JOIN Users u ON i.assessor_id = u.user_id
    WHERE i.assessor_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Internships List</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js"></script>
</head>
<body>

<div class="container">

<h2>Internships List</h2>

<?php if ($role_id ==1): ?>
    <a href="assign.php">+ Assign New Internship</a><br><br>
<?php endif; ?>


<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Company</th>
        <th>Assessor</th>
        <th>Start</th>
        <th>End</th>
    </tr>

  <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['internship_id'] ?></td>
        <td><?= $row['student_name'] ?></td>
        <td><?= $row['company_name'] ?></td>
        <td><?= $row['assessor_name'] ?></td>
        <td><?= $row['internship_start_date'] ?></td>
        <td><?= $row['internship_end_date'] ?></td>
    </tr>
    <?php endwhile; ?>

</table>

<br>
<a
href="../dashboard.php">Back</a>
</body>
</html>