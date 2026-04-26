<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT i.internship_id, s.student_name
        FROM Internships i
        JOIN Students s ON i.student_id = s.student_id
        WHERE i.assessor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$internships = $stmt->get_result();

$criteria = $conn->query("SELECT * FROM Assessment_Criteria");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $internship_id = $_POST['internship_id'];

    foreach ($_POST['score'] as $criteria_id => $score) {
        $comment = $_POST['comments'][$criteria_id];

        $check = $conn->prepare("SELECT result_id FROM Assessment_Results WHERE internship_id = ? AND criteria_id = ?");
        $check->bind_param("ii", $internship_id, $criteria_id);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE Assessment_Results SET score = ?, comments = ? WHERE internship_id = ? AND criteria_id = ?");
            $stmt->bind_param("dsii", $score, $comment, $internship_id, $criteria_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO Assessment_Results (internship_id, criteria_id, score, comments) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iids", $internship_id, $criteria_id, $score, $comment);
        }
        $stmt->execute();
    }
    
    $success = "Assessment saved successfully! Total score is auto-calculated by the system.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Assessment</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h2>Enter Assessment</h2>
        
        <?php if(isset($success)): ?>
            <div class="card" style="border-left: 5px solid #10b981; background: #ecfdf5;">
                <p style="color: #065f46;"><?= $success ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateScores()">
            <label>Select Student:</label>
            <select name="internship_id" required>
            <?php while($row = $internships->fetch_assoc()): ?>
                <option value="<?= $row['internship_id'] ?>">
                    <?= htmlspecialchars($row['student_name']) ?>
                </option>
            <?php endwhile; ?>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Weight (%)</th>
                        <th>Score (0-100)</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $criteria->data_seek(0);
                    while($row = $criteria->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['criteria_name']) ?></td>
                        <td><?= $row['weightage'] ?>%</td>
                        <td>
                            <input type="number" name="score[<?= $row['criteria_id'] ?>]" min="0" max="100" step="0.01" required>
                        </td>
                        <td>
                            <input type="text" name="comments[<?= $row['criteria_id'] ?>]">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <button type="submit">Save Assessment</button>
                <a href="../dashboard.php" style="margin-left: 10px;">Back</a>
            </div>
        </form>
    </div>
</body>
</html>