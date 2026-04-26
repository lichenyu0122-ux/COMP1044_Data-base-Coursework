<?php
include '../db.php';

if (!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role_id'] != 1){
    die("Access denied");


}

$students = $conn->query("SELECT student_id, student_name FROM Students");
$assessors = $conn->query("SELECT user_id, full_name FROM Users WHERE role_id = 2");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $student_id = $_POST['student_id'];
    $assessor_id = $_POST['assessor_id'];
    $company = $_POST['company_name'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO Internships (student_id, assessor_id, company_name, internship_start_date, internship_end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $student_id, $assessor_id, $company, $start, $end);

    if ($stmt->execute()){
        header("Location: list.php");
        exit();
    } else {
        $error = "Insert failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Internship</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="container">

    <h2>Assign Internship</h2>

    <?php if(!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>



    <form method="POST">
        <label>Student:</label><br>
        <select name="student_id" required>
            <option value="">-- Select Student --</option>
            <?php while($row = $students->fetch_assoc()): ?>
                <option value="<?= $row['student_id'] ?>">
                    <?= $row['student_name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Assessor:</label><br>
        <select name="assessor_id" required>
            <option value="">-- Select Assessor --</option>
            <?php while($row = $assessors->fetch_assoc()): ?>
                <option value="<?= $row['user_id'] ?>">
                    <?= $row['full_name'] ?>
                </option> 
            <?php endwhile; ?>
        </select> <br><br>
        
        <label>Company Name:</label><br>
        <input type="text" name="company_name" required><br><br>

        <label>Start Date:</label><br>
        <input type="date" name="start_date"><br><br>

        <label>End Date:</label><br>
        <input type="date" name="end_date"><br><br>

        <button type="submit">Assign</button>
    </form>

    <br>
    <a href="list.php">Back to List</a>
    </div>
</body>
</html>