<?php
date_default_timezone_set('Asia/Kolkata');
include '../config/db.php';
include 'header.php';


function failed_attempt($conn, $username) {
    $stmt = $conn->prepare("SELECT * FROM login_attempts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $attempt_result = $stmt->get_result();

    if ($attempt_result->num_rows > 0) {
        $attempt_row = $attempt_result->fetch_assoc();
        $attempts = $attempt_row['attempts'] + 1;

        if ($attempts >= 3) {
            $blocked_until = date('Y-m-d H:i:s', time() + 60);
            $stmt = $conn->prepare("UPDATE login_attempts SET attempts = ?, blocked_until = ? WHERE username = ?");
            $stmt->bind_param("iss", $attempts, $blocked_until, $username);
        } else {
            $stmt = $conn->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW() WHERE username = ?");
            $stmt->bind_param("is", $attempts, $username);
        }
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO login_attempts (username, attempts, last_attempt) VALUES (?, 1, NOW())");
        $stmt->bind_param("s", $username);
        $stmt->execute();
    }
}

function reset_attempts($conn, $username) {
    $stmt = $conn->prepare("DELETE FROM login_attempts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM login_attempts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $block_result = $stmt->get_result();

    if ($block_result->num_rows > 0) {
        $row = $block_result->fetch_assoc();
        $current_time = time();

        if (!empty($row['blocked_until'])) {
            $blocked_until = strtotime($row['blocked_until']);

            if ($current_time < $blocked_until) {
                echo "You are blocked until: " . $row['blocked_until'];
                exit();
            } else {
                reset_attempts($conn, $username);
            }
        }
    }

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();

        if ($password === $user['password']) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");

            reset_attempts($conn, $username);
        } else {
            echo "Invalid password.";
            failed_attempt($conn, $username);
        }
    } else {
        echo "User not found.";
        failed_attempt($conn, $username);
    }
}
?>
