<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>student registration</title>
        <style>
            body{
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height:100vh;
            }
        </style>
    </head>
    <body>
        
        <form action= "register.php" method= "POST">
            <h2>Add Student</h2>
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required ><br>
            <label for="registerno">Register Number:</label>
            <input type="text" id="registerno" name="registerno" required ><br>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="18" max="25" required><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required ><br>
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required ><br>
            <label for="course">Course:</label>
            <select id="course" name="course" required><br>
                <option value="">-- Select Course --</option>
                    <?php
                    $allowed_courses = ["B.Tech", "B.Sc", "BCA", "MCA"];
                    foreach ($allowed_courses as $c) {
                        echo "<option value='$c'>$c</option>";
                    }
                    ?>
                </select>

  
            </select><br><br>
            <button type="submit">Add Student</button>

        </form>
    </body>
</html>
