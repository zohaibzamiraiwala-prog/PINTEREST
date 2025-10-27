<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$user_id = $_SESSION['user_id'];
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_private = isset($_POST['is_private']) ? 1 : 0;
 
    $sql = "INSERT INTO boards (user_id, name, description, is_private) VALUES ($user_id, '$name', '$description', $is_private)";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href = 'profile.php';</script>";
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
    <title>Create Board - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        form { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #e60023; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background: #ff4d4d; }
        @media (max-width: 600px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Create Board</h2>
        <input type="text" name="name" placeholder="Board Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <label><input type="checkbox" name="is_private"> Private</label>
        <button type="submit">Create</button>
    </form>
</body>
</html>
