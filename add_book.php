<?php
session_start();
include 'db_connection.php';
if ($_SESSION['role'] !== 'admin') { header("Location: dashboard.php"); exit(); }

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = intval($_POST['quantity']);
    if ($quantity < 1) $quantity = 1;
    $conn->query("INSERT INTO books (title, author, quantity) VALUES ('$title','$author',$quantity)");
    $msg = "Book added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book</title>

<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Add New Book</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="number" name="quantity" placeholder="Quantity" min="1" required>
            <button type="submit">Add Book</button>
        </form>
        <p style="color:green"><?= $msg ?></p>
        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</div>
</body>
</html>
