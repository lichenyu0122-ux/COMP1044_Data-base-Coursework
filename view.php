<?php
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$internship_id = isset($_GET['details']) ? (int)$_GET['details'] : null;

if ($internship_id) {
    $sql = "SELECT ar.score, ar.comments, ac.criteria_name 
            FROM Assessment_Results ar
            JOIN Assessment_Criteria ac ON ar.criteria_id = ac.criteria_id
            WHERE ar.internship_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $internship_id);
    $stmt->execute();
    $details = $stmt->get_result();
}

if ($role_id == 1) {
    $sql = "SELECT * FROM Final_Results";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT fr.* FROM Final_Results fr
            JOIN Internships i ON fr.internship_id = i.internship_id
            WHERE i.assessor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Results</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="container">
        <?php if ($internship_id && isset($details)): ?>
            <h2>Assessment Details (ID: <?= $internship_id ?>)</h2>
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Criteria</th>
                            <th>Score</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $details->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['criteria_name']) ?></td>
                            <td><?= $row['score'] ?></td>
                            <td><?= htmlspecialchars($row['comments'] ?: 'No comment') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <br><a href="view.php" class="btn-back">Back to Summary</a>
        <?php else: ?>
            <h2>Final Internship Results</h2>
            <div class="card">
                <?php if($result && $result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Internship ID</th>
                                <th>Student Name</th>
                                <th>Final Score</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['internship_id'] ?></td>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= number_format($row['final_score'], 2) ?></td>
                                <td>
                                    <strong><?= ($row['final_score'] >= 50) ? '<span style="color:green">PASS</span>' : '<span style="color:red">FAIL</span>'; ?></strong>
                                </td>
                                <td>
                                    <a href="view.php?details=<?= $row['internship_id'] ?>">View Details</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No records found.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <br>
        <a href="../dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>
</body>
</html>