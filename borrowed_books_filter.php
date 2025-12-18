<?php
session_start();
include 'db_connection.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books Record by Filter</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url('library-bg.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 850px;
            background: white;
            padding: 20px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        input[type="date"], button {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #aaa;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .scroll-table {
            max-height: 400px;
            overflow-y: scroll;
            overflow-x: auto;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .scroll-table table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .scroll-table th, .scroll-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
        }

        .scroll-table th {
            background-color: #007BFF;
            color: white;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .back-btn {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .back-btn:hover {
            background: #b52a34;
        }

        .back-container {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Books Record by Filter</h2>

    <!-- Date Filter Form -->
    <form method="GET" action="">
        <label>From:</label>
        <input type="date" name="from_date" required>
        <label>To:</label>
        <input type="date" name="to_date" required>
        <button type="submit">Filter</button>
    </form>

    <!-- Back Button (moved above table) -->
    <div class="back-container">
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

    <!-- Scrollable Table -->
    <div class="scroll-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Book Title</th>
                    <th>Borrowed At</th>
                    <th>Returned At</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                $from = $_GET['from_date'];
                $to = $_GET['to_date'];

                $stmt = $conn->prepare("
                    SELECT bb.id, s.student_id, b.title AS book_title, bb.borrowed_at, bb.returned_at
                    FROM borrowed_books bb
                    JOIN students s ON bb.student_id = s.student_id
                    JOIN books b ON bb.book_id = b.id
                    WHERE DATE(bb.borrowed_at) BETWEEN ? AND ?
                    ORDER BY bb.borrowed_at ASC
                ");
                $stmt->bind_param("ss", $from, $to);
            } else {
                $stmt = $conn->prepare("
                    SELECT bb.id, s.student_id, b.title AS book_title, bb.borrowed_at, bb.returned_at
                    FROM borrowed_books bb
                    JOIN students s ON bb.student_id = s.student_id
                    JOIN books b ON bb.book_id = b.id
                    ORDER BY bb.borrowed_at DESC
                ");
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $i = 1;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['book_title']}</td>
                            <td>{$row['borrowed_at']}</td>
                            <td>" . ($row['returned_at'] ?? '-') . "</td>
                        </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
