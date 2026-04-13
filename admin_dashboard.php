<?php
require_once 'config.php';
checkRole(['admin']);

$success = "";
$error = "";

if (isset($_GET['delete'])) {
    $student_id = intval($_GET['delete']);
    $query = "DELETE FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_dashboard.php?msg=deleted");
        exit();
    } else {
        $error = "Delete failed: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $student_code = mysqli_real_escape_string($conn, $_POST['student_code']);
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $programme = mysqli_real_escape_string($conn, $_POST['programme']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $assessor_id = intval($_POST['assessor_id']);
    
    $query = "INSERT INTO students (student_code, student_name, programme, company_name, assessor_id) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $student_code, $student_name, $programme, $company_name, $assessor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Student added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $success = "Student record deleted successfully!";
}

$students_query = "SELECT s.*, u.full_name as assessor_name 
                   FROM students s 
                   LEFT JOIN users u ON s.assessor_id = u.user_id";
$students_result = mysqli_query($conn, $students_query);

$assessors_query = "SELECT user_id, full_name FROM users WHERE role = 'assessor'";
$assessors_result = mysqli_query($conn, $assessors_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; color: #333; }
        .navbar { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar h1 { font-size: 1.2rem; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .logout-btn { background: #e74c3c; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background 0.3s; }
        .logout-btn:hover { background: #c0392b; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; border: 1px solid #e1e4e8; }
        .card-header { background: #3498db; color: white; padding: 15px 20px; font-size: 1.1rem; font-weight: 600; }
        .card-body { padding: 25px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        .btn-submit { background: #27ae60; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        table th { background: #f8f9fa; color: #666; text-transform: uppercase; font-size: 0.85rem; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .action-btns { display: flex; gap: 8px; }
        .delete-btn { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.8rem; }
        .view-btn { background: #3498db; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.8rem; }
        .search-box { margin-bottom: 20px; }
        .search-box input { width: 100%; max-width: 400px; padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Internship Result Management System</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">Add New Student Profile</div>
            <div class="card-body">
                <form method="POST" action="admin_dashboard.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Student ID *</label>
                            <input type="text" name="student_code" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="student_name" required>
                        </div>
                        <div class="form-group">
                            <label>Programme *</label>
                            <input type="text" name="programme" required>
                        </div>
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" name="company_name">
                        </div>
                        <div class="form-group">
                            <label>Assign Assessor *</label>
                            <select name="assessor_id" required>
                                <option value="">-- Select --</option>
                                <?php while ($assessor = mysqli_fetch_assoc($assessors_result)): ?>
                                    <option value="<?php echo $assessor['user_id']; ?>">
                                        <?php echo htmlspecialchars($assessor['full_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_student" class="btn-submit">Add Student Record</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Student Management Records</div>
            <div class="card-body">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()">
                </div>
                <table id="studentTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Programme</th>
                            <th>Company</th>
                            <th>Assessor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($students_result) > 0): ?>
                            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_code']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['programme']); ?></td>
                                <td><?php echo htmlspecialchars($student['company_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($student['assessor_name'] ?? 'Unassigned'); ?></td>
                                <td class="action-btns">
                                    <a href="view_student.php?id=<?php echo $student['student_id']; ?>" class="view-btn">View</a>
                                    <a href="admin_dashboard.php?delete=<?php echo $student['student_id']; ?>" 
                                       class="delete-btn" 
                                       onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">No student records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function searchTable() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toUpperCase();
            let table = document.getElementById('studentTable');
            let tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                let tdId = tr[i].getElementsByTagName('td')[0];
                let tdName = tr[i].getElementsByTagName('td')[1];
                if (tdId || tdName) {
                    let idVal = tdId.textContent || tdId.innerText;
                    let nameVal = tdName.textContent || tdName.innerText;
                    if (idVal.toUpperCase().indexOf(filter) > -1 || nameVal.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
