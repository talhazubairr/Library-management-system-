<?php
include 'db_connection.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $check = $conn->query("SELECT * FROM students WHERE student_id='$student_id'");
    if ($check->num_rows > 0) {
        $error = "Student ID already exists!";
    } else {
        $conn->query("INSERT INTO students (student_id, password) VALUES ('$student_id', '$password')");
        $success = "Signup successful! <a href='login.php'>Login now</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Signup</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Student Signup</h2>
        <form method="POST">
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>
        <?php if ($success) echo "<p style='color:green'>$success</p>"; ?>
        <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</div>
</body>
</html>
