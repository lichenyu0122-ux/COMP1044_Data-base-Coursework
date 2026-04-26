<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<div class="navbar">
    <h2>Internship System</h2>

    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="nav-links">
        <a href="/COMP1044_SRC/dashboard.php">Dashboard</a>

        <?php if ($_SESSION['role_id'] == 1): ?>
            <a href="/COMP1044_SRC/students/list.php">Students</a>
            <a href="/COMP1044_SRC/internships/list.php">Internships</a>
        <?php endif; ?>

        <a href="/COMP1044_SRC/assessments/enter.php">Enter Assessment</a>
        <a href="/COMP1044_SRC/assessments/view.php">Results</a>
        <a href="/COMP1044_SRC/logout.php" style="color: #f87171;">Logout</a>
    </div>
    <?php endif; ?>
</div>