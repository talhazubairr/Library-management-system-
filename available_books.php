<?php
session_start();
include 'db_connection.php';

$role = $_SESSION['role'];
$student_id = $_SESSION['student_id'] ?? null;
$error = "";

if ($role === 'student' && isset($_GET['borrow'])) {
    $book_id = $_GET['borrow'];

    // NEW: check if student already has this book borrowed and not returned
    $dup = $conn->query("SELECT * FROM borrowed_books WHERE student_id='$student_id' AND book_id=$book_id AND returned_at IS NULL");
    if ($dup->num_rows > 0) {
        $error = "You already borrowed this book. Return it before borrowing again.";
    } else {
        // Check quantity
        $q = $conn->query("SELECT quantity FROM books WHERE id=$book_id")->fetch_assoc();
        if ($q['quantity'] > 0) {
            $conn->query("INSERT INTO borrowed_books (student_id, book_id) VALUES ('$student_id', $book_id)");
            $conn->query("UPDATE books SET quantity = quantity - 1 WHERE id=$book_id");
        } else {
            $error = "Cannot borrow: no copies available.";
        }
    }
}

$books = $conn->query("SELECT * FROM books");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Books</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2>Available Books</h2>
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
        <?php while($b = $books->fetch_assoc()): ?>
            <p>
                <?= htmlspecialchars($b['title']) ?> by <?= htmlspecialchars($b['author']) ?> 
                (Available: <?= $b['quantity'] ?>)
                <?php if ($role === 'student' && $b['quantity'] > 0): ?>
                    <a href="?borrow=<?= $b['id'] ?>"><button>Borrow</button></a>
                <?php endif; ?>
                <?php if ($role === 'admin'): ?>
                    <a href="update_book.php?id=<?= $b['id'] ?>"><button style="background:#f39c12;">Update</button></a>
                <?php endif; ?>
            </p>
        <?php endwhile; ?>
        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</div>
</body>
</html>
