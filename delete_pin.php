<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$pin_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check ownership
$sql_check = "SELECT * FROM pins WHERE id = $pin_id AND user_id = $user_id";
if ($conn->query($sql_check)->num_rows > 0) {
    // Delete from board_pins, likes, comments first
    $conn->query("DELETE FROM board_pins WHERE pin_id = $pin_id");
    $conn->query("DELETE FROM likes WHERE pin_id = $pin_id");
    $conn->query("DELETE FROM comments WHERE pin_id = $pin_id");
    // Delete pin
    $sql = "DELETE FROM pins WHERE id = $pin_id";
    $conn->query($sql);
}
echo "<script>window.location.href = 'profile.php';</script>";
?>
