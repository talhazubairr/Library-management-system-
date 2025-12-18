<?php
session_start();
include 'db_connection.php';

$role = $_SESSION['role'];
$student_id = $_SESSION['student_id'] ?? null;

if ($role === 'student' && isset($_GET['return'])) {
    $book_id = $_GET['return'];
    $conn->query("UPDATE borrowed_books SET returned_at=NOW() WHERE student_id='$student_id' AND book_id=$book_id AND returned_at IS NULL");
    $conn->query("UPDATE books SET quantity = quantity + 1 WHERE id=$book_id");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Borrowed Books</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="overlay">
    <div class="container">
        <h2><?= $role === 'admin' ? "All Borrowed Books" : "My Borrowed Books" ?></h2>
        <?php if ($role === 'admin'): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $conn->query("SELECT bb.student_id, b.title, b.author 
                    FROM borrowed_books bb JOIN books b ON bb.book_id = b.id 
                    WHERE bb.returned_at IS NULL 
                    ORDER BY bb.student_id");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['author']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No borrowed books currently.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php
            $books = $conn->query("SELECT b.id, b.title, b.author 
                FROM borrowed_books bb 
                JOIN books b ON bb.book_id=b.id 
                WHERE bb.student_id='$student_id' AND bb.returned_at IS NULL");
            if ($books->num_rows > 0) {
                while($b = $books->fetch_assoc()) {
                    echo "<p>".htmlspecialchars($b['title'])." by ".htmlspecialchars($b['author'])."
                    <a href='?return={$b['id']}'><button>Return</button></a></p>";
                }
            } else {
                echo "<p>No books borrowed.</p>";
            }
            ?>
        <?php endif; ?>
        <a href="dashboard.php"><button style="background:#3498db;">Back to Dashboard</button></a>
    </div>
</div>
</body>
</html>
