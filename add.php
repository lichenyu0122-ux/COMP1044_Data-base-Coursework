<?php
include("../db.php");

if (!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role_id'] != 1){
    die("Access denied");


}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $code = $_POST['student_code'];
    $name = $_POST['student_name'];
    $programme = $_POST['programme'];

    $sql ="INSERT INTO Students (student_code, student_name, programme)
           VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $code, $name, $programme);
    $stmt->execute();

    header("Location: list.php");
    exit();


    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container">


<h2>Add Student</h2>

<form method = "POST">
    Student Code: <input type = "text" name = "student_code" required><br><br>
    Name: <input type = "text" name = "student_name" required><br><br>
    Programme: <input type="text" name="programme" required><br><br>

    <button type="submit">Save</button>
</form>

<br>
<a href="list.php">Back</a>

</div>
</body>
</html>


