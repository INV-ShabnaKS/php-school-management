<?php
session_start(); 

if (isset($_GET['logout'])) {
    session_destroy(); 
    header("Location: login.html");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0; 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            flex-direction: column; 
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        a button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

    <a href="addstudent.php"><button>Add Student</button></a>
    <a href="viewstudent.php"><button>View Students</button></a>
    <a href="?logout=1"><button>Logout</button></a>

</body>
</html>
