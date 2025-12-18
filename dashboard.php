<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2><?= ucfirst($role) ?> Dashboard</h2>
        <?php if ($role === 'admin'): ?>
            <a href="add_book.php"><button>Add Book</button></a>
            <a href="available_books.php"><button>Available Books</button></a>
            <a href="borrowed_books.php"><button>Borrowed Books</button></a>
            <a href="students.php"><button>Registered Students</button></a>
            <a href="borrowed_books_filter.php"><button class="button">Books Record by Filter</button></a>
        <?php else: ?>
            <a href="available_books.php"><button>Available Books</button></a>
            <a href="borrowed_books.php"><button>My Borrowed Books</button></a>
        <?php endif; ?>
        <a href="logout.php"><button class="logout-btn">Logout</button></a>
    </div>
</div>
</body>
</html>
