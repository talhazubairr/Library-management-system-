<?php
session_start();
include 'db_connection.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: available_books.php");
    exit();
}

// Fetch book details
$book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = intval($_POST['quantity']);
    if ($quantity < 0) $quantity = 0;
    $conn->query("UPDATE books SET title='$title', author='$author', quantity=$quantity WHERE id=$id");
    $msg = "Book updated successfully!";
    // Refresh data
    $book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Book</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Update Book</h2>
        <form method="POST">
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            <input type="number" name="quantity" value="<?= $book['quantity'] ?>" min="0" required>
            <button type="submit">Save Changes</button>
        </form>
        <?php if ($msg) echo "<p style='color:green;'>$msg</p>"; ?>
        <a href="available_books.php"><button style="background:#3498db;">Back</button></a>
    </div>
</div>
</body>
</html>
