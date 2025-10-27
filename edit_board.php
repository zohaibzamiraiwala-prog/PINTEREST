<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$board_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
 
// Check ownership
$sql_check = "SELECT * FROM boards WHERE id = $board_id AND user_id = $user_id";
if ($conn->query($sql_check)->num_rows == 0) {
    echo "Not authorized.";
    exit;
}
 
$sql_board = "SELECT * FROM boards WHERE id = $board_id";
$board = $conn->query($sql_board)->fetch_assoc();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_private = isset($_POST['is_private']) ? 1 : 0;
 
    $sql_update = "UPDATE boards SET name = '$name', description = '$description', is_private = $is_private WHERE id = $board_id";
    if ($conn->query($sql_update) === TRUE) {
        echo "<script>window.location.href = 'view_board.php?id=$board_id';</script>";
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
    <title>Edit Board - Pinterest Clone</title>
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
        <h2>Edit Board</h2>
        <input type="text" name="name" value="<?php echo $board['name']; ?>" required>
        <textarea name="description"><?php echo $board['description']; ?></textarea>
        <label><input type="checkbox" name="is_private" <?php if($board['is_private']) echo 'checked'; ?>> Private</label>
        <button type="submit">Update</button>
    </form>
</body>
</html>
