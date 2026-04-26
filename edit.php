<?php
include("../db.php");

if (!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role_id'] != 1){
    die("Access denied");
}
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Students WHERE student_id = ? ");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $code = $_POST['student_code'];
    $name = $_POST['student_name'];
    $programme = $_POST['programme'];

    $sql = "UPDATE Students 
            SET student_code=?, student_name=?, programme=?
            WHERE student_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $code, $name, $programme, $id);
    $stmt->execute();

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
<?php include '../header.php'; ?>

<div class="container">

<h2>Edit Student</h2>

<form method="POST">
    Student Code:
    <input type="text" name="student_code" value="<?php echo $data['student_code']; ?>" required><br><br>

    Name:
    <input type="text" name="student_name" value="<?php echo $data['student_name']; ?>" required><br><br>

    Programme:
    <input type="text" name="programme" value="<?php echo $data['programme']; ?>" required><br><br>

    <button type="submit">Update</button>
</form>

<br>
<a href="list.php">Back</a>
</div>

</body>
</html>