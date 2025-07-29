<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include '../config/db.php';


$limit = 5;


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM students LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$total_pages = ceil($total_students / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin: 10px auto;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            width: 300px;
            display: block;
            text-align: left;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a, .pagination strong {
            padding: 5px 10px;
            margin: 2px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: black;
        }
        .pagination strong {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>Student List</h2>

<?php

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<h3>{$row['fullname']}</h3>";
        echo "<p><strong>Register No:</strong> {$row['registerno']}</p>";
        echo "<p><strong>Age:</strong> {$row['age']}</p>";
        echo "<p><strong>Email:</strong> {$row['email']}</p>";
        echo "<p><strong>Phone:</strong> {$row['phone']}</p>";
        echo "<p><strong>Course:</strong> {$row['course']}</p>";
        echo "</div>";
    }
} else {
    echo "<p>No students found</p>";
}
?>

<div class="pagination">
<?php
if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "'>Prev</a> ";
}

if ($page < $total_pages) {
    echo "<a href='?page=" . ($page + 1) . "'>Next</a>";
}

?>
</div>

</body>
</html>
