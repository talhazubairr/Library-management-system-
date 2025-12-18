<?php
session_start();
include 'db_connection.php';

if ($_SESSION['role'] !== 'admin') { header("Location: dashboard.php"); exit(); }

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Get student ID (username) from students table
    $result = $conn->query("SELECT student_id FROM students WHERE id=$id");
    $row = $result->fetch_assoc();
    $student_id = $row['student_id'];

    // Check for active borrowed books
    $check = $conn->query("SELECT * FROM borrowed_books WHERE student_id='$student_id' AND returned_at IS NULL");
    if ($check->num_rows > 0) {
        // Cannot delete
        header("Location: students.php?error=borrowed");
        exit();
    } else {
        // Safe to delete
        $conn->query("DELETE FROM students WHERE id=$id");
        header("Location: students.php?success=deleted");
        exit();
    }
}

$students = $conn->query("SELECT * FROM students");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registered Students</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Registered Students</h2>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'borrowed'): ?>
            <p style="color:red;">Cannot delete: student has borrowed books.</p>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <p style="color:green;">Student deleted successfully.</p>
        <?php endif; ?>

        <?php while($s = $students->fetch_assoc()): ?>
            <p>
                <?= htmlspecialchars($s['student_id']) ?>
                <a href="?delete=<?= $s['id'] ?>"><button>Delete</button></a>
            </p>
        <?php endwhile; ?>

        <a href="dashboard.php"><button style="background:#3498db;">Back to Dashboard</button></a>
    </div>
</div>
</body>
</html>
