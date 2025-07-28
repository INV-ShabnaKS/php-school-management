<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $look = $conn->prepare("SELECT * FROM login_attempts WHERE username = ?");
    $look->bind_param("s", $username);
    $look->execute();
    $attemptResult = $look->get_result();
    $attemptData = $attemptResult->fetch_assoc();

    $current_time = date("Y-m-d H:i:s");

    if ($attemptData && $attemptData['blocked_until'] > $current_time) {
        echo "You are blocked from logging in until " . $attemptData['blocked_until'];
        exit();
    }

    $look = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $look->bind_param("ss", $username, $password);
    $look->execute();
    $userResult = $look->get_result();

    if ($userResult->num_rows > 0) {
        
        echo "Login successful! Welcome, $username";

        
        $look = $conn->prepare("DELETE FROM login_attempts WHERE username = ?");
        $look->bind_param("s", $username);
        $look->execute();
    } else {
     
        if ($attemptData) {
            $attempts = $attemptData['attempts'] + 1;

            if ($attempts >= 3) {
                $blocked_until = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                $look = $conn->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = ?, blocked_until = ? WHERE username = ?");
                $look->bind_param("isss", $attempts, $current_time, $blocked_until, $username);
                $look->execute();
                echo "Too many failed attempts. You are blocked for 5 minutes.";
            } else {
                $look = $conn->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = ? WHERE username = ?");
                $look->bind_param("iss", $attempts, $current_time, $username);
                $look->execute();
                echo "Login failed. Attempt $attempts of 3.";
            }
        } else {
           
            $look = $conn->prepare("INSERT INTO login_attempts (username, attempts, last_attempt) VALUES (?, 1, ?)");
            $look->bind_param("ss", $username, $current_time);
            $look->execute();
            echo "Login failed. Attempt 1 of 3.";
        }
    }
}
?>
