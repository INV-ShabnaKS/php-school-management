<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}
?>
<style>
    .header {
        background: #333;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between; /
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        box-sizing: border-box; 
    }
    .header h1 {
        margin: 0;
        font-size: 18px;
        white-space: nowrap; 
    }
    .header a {
        color: white;
        text-decoration: none;
        margin-left: 10px;
        padding: 6px 10px;
        background: #555;
        border-radius: 4px;
        font-size: 14px;
        white-space: nowrap;
    }
    .header a:hover {
        background: #777;
    }
    body {
        margin: 0;
        margin-top: 60px;
        font-family: Arial, sans-serif;
    }
  
    @media (max-width: 500px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
        }
        .header div {
            margin-top: 5px;
        }
    }
</style>

<div class="header">
    <h1>School Management</h1>
    <div>
        <a href="dashboard.php">Home</a>
        <a href="?logout=1">Logout</a>
    </div>
</div>
