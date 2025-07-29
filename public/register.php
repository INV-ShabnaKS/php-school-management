<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include '../config/db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $registerno = $_POST['registerno'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    if (!preg_match("/^[A-Za-z ]{2,}$/", $fullname)) {
        die("Invalid name: must be at least 2 letters and contain only alphabets.");
    }

    
    if (!preg_match("/^REG-[0-9]{4}-[0-9]{4}$/", $registerno)) {
        die("Invalid registration number: format should be REG-YYYY-NNNN.");
    }

    
    if ($age < 18 || $age > 25) {
        die("Invalid age: must be between 18 and 25.");
    }

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

   
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        die("Invalid phone number: must be exactly 10 digits.");
    }

    
    $allowed_courses = ["B.Tech", "B.Sc", "BCA", "MCA"];
    if (!in_array($course, $allowed_courses)) {
        die("Invalid course selection.");
    }

    
    $stmt = $conn->prepare("INSERT INTO students (fullname, registerno, age, email, phone, course) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $fullname, $registerno, $age, $email, $phone, $course);

    if ($stmt->execute()) {
        echo "Student added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>