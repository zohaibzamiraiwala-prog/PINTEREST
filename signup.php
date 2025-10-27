<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_id'] = $conn->insert_id;
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: linear-gradient(to bottom, #f4f4f4, #ddd); }
        form { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #e60023; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #ff4d4d; transition: background 0.3s ease; }
        @media (max-width: 600px) { form { padding: 10px; } }
        /* Amazing CSS: Form glow on focus, smooth animations */
        input:focus { outline: none; box-shadow: 0 0 5px #e60023; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Signup</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Signup</button>
    </form>
    <script>
        // JS for redirection if needed, but handled in PHP echo
    </script>
</body>
</html>
