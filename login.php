<?php
session_start();
include 'db_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($role === 'admin') {
        $result = $conn->query("SELECT * FROM admins WHERE username='$username' AND password='$password'");
        if ($result->num_rows == 1) {
            $_SESSION['role'] = 'admin';
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid admin credentials!";
        }
    } else {
        $result = $conn->query("SELECT * FROM students WHERE student_id='$username' AND password='$password'");
        if ($result->num_rows == 1) {
            $_SESSION['role'] = 'student';
            $_SESSION['student_id'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid student credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Library Management System</h2>
        <form method="POST">
            <label>Login as:</label>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="student">Student</option>
            </select>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <?php if ($error) echo "<p class='error'>$error</p>"; ?>
            <button type="submit" name="login">Login</button>
        </form>
        <p>New student? <a href="signup.php">Sign up here</a></p>
    </div>
</div>
</body>
</html>