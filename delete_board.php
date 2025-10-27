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
if ($conn->query($sql_check)->num_rows > 0) {
    // Delete board_pins first
    $conn->query("DELETE FROM board_pins WHERE board_id = $board_id");
    // Delete board
    $sql = "DELETE FROM boards WHERE id = $board_id";
    $conn->query($sql);
}
echo "<script>window.location.href = 'profile.php';</script>";
?>
