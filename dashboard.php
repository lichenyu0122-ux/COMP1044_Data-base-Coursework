<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['full_name']); ?>!</h1>
        <p>Your Role: <strong><?= ($_SESSION['role_id'] == 1) ? 'Administrator' : 'Assessor'; ?></strong></p>
    </div>

    <hr>

    <?php if ($_SESSION['role_id'] == 1): ?>
        <h3>Admin Panel</h3>
        <div class="card">
            <ul>
                <li><a href="students/list.php">Manage Students</a></li>
                <li><a href="internships/list.php">Manage Internships</a></li>
                <li><a href="assessments/view.php">View All Results</a></li>
            </ul>
        </div>
    <?php else: ?>
        <h3>Assessor Panel</h3>
        <div class="card">
            <ul>
                <li><a href="assessments/enter.php">Enter Student Assessments</a></li>
                <li><a href="assessments/view.php">View Assigned Students Results</a></li>
            </ul>  
        </div> 
    <?php endif; ?>

    <div class="card">
        <p>Use the navigation menu above to access system features.</p>
    </div>
</div>
    
</body>
</html>