<?php
session_start();
include 'db.php';
 
$board_id = $_GET['id'];
 
// Fetch board details
$sql_board = "SELECT b.*, u.username FROM boards b JOIN users u ON b.user_id = u.id WHERE b.id = $board_id";
$board = $conn->query($sql_board)->fetch_assoc();
 
// Check if private and not owner
if ($board['is_private'] && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $board['user_id'])) {
    echo "Private board.";
    exit;
}
 
// Fetch pins in board
$sql_pins = "SELECT p.* FROM pins p JOIN board_pins bp ON p.id = bp.pin_id WHERE bp.board_id = $board_id";
$result_pins = $conn->query($sql_pins);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Board - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .board { max-width: 800px; margin: auto; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .pin { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .pin img { width: 100%; height: auto; }
        .pin-info { padding: 10px; }
        @media (max-width: 600px) { .grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); } }
        .pin:hover { transform: scale(1.05); transition: transform 0.3s ease; }
    </style>
</head>
<body>
    <div class="board">
        <h2><?php echo $board['name']; ?> by <?php echo $board['username']; ?></h2>
        <p><?php echo $board['description']; ?></p>
        <div class="grid">
            <?php while($pin = $result_pins->fetch_assoc()): ?>
                <div class="pin">
                    <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>">
                    <div class="pin-info">
                        <h3><?php echo $pin['title']; ?></h3>
                        <a href="view_pin.php?id=<?php echo $pin['id']; ?>">View</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $board['user_id']): ?>
        <a href="edit_board.php?id=<?php echo $board_id; ?>">Edit Board</a>
        <a href="delete_board.php?id=<?php echo $board_id; ?>" onclick="return confirm('Delete?');">Delete Board</a>
    <?php endif; ?>
</body>
</html>
