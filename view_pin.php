<?php
session_start();
include 'db.php';

$pin_id = $_GET['id'];
$sql_pin = "SELECT p.*, u.username, c.name AS category FROM pins p JOIN users u ON p.user_id = u.id JOIN categories c ON p.category_id = c.id WHERE p.id = $pin_id";
$pin = $conn->query($sql_pin)->fetch_assoc();

// Update views
$conn->query("UPDATE pins SET views = views + 1 WHERE id = $pin_id");

// Fetch comments
$sql_comments = "SELECT cm.*, u.username FROM comments cm JOIN users u ON cm.user_id = u.id WHERE pin_id = $pin_id ORDER BY created_at DESC";
$result_comments = $conn->query($sql_comments);

// Handle like
if (isset($_POST['like']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_like_check = "SELECT * FROM likes WHERE user_id = $user_id AND pin_id = $pin_id";
    if ($conn->query($sql_like_check)->num_rows == 0) {
        $sql_like = "INSERT INTO likes (user_id, pin_id) VALUES ($user_id, $pin_id)";
        $conn->query($sql_like);
    }
}

// Handle comment
if (isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $sql_comment = "INSERT INTO comments (pin_id, user_id, comment) VALUES ($pin_id, $user_id, '$comment')";
    $conn->query($sql_comment);
    echo "<script>window.location.href = 'view_pin.php?id=$pin_id';</script>";
}

// Handle save to board
if (isset($_POST['save_to_board']) && isset($_SESSION['user_id'])) {
    $board_id = $_POST['board_id'];
    $sql_save_check = "SELECT * FROM board_pins WHERE board_id = $board_id AND pin_id = $pin_id";
    if ($conn->query($sql_save_check)->num_rows == 0) {
        $sql_save = "INSERT INTO board_pins (board_id, pin_id) VALUES ($board_id, $pin_id)";
        $conn->query($sql_save);
    }
}

// Fetch user's boards if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_boards = "SELECT * FROM boards WHERE user_id = $user_id";
    $result_boards = $conn->query($sql_boards);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pin - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .pin-detail { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        img { width: 100%; height: auto; border-radius: 10px; }
        .comments { margin-top: 20px; }
        .comment { border-bottom: 1px solid #ccc; padding: 10px 0; }
        form { margin-top: 10px; }
        textarea { width: 100%; padding: 10px; }
        button { background: #e60023; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background: #ff4d4d; }
        @media (max-width: 600px) { .pin-detail { padding: 10px; } }
        .pin-detail:hover { box-shadow: 0 6px 20px rgba(230,0,35,0.3); transition: box-shadow 0.3s ease; }
    </style>
</head>
<body>
    <div class="pin-detail">
        <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>">
        <h2><?php echo $pin['title']; ?></h2>
        <p><?php echo $pin['description']; ?></p>
        <p>By: <?php echo $pin['username']; ?> | Category: <?php echo $pin['category']; ?></p>
        <p>Views: <?php echo $pin['views']; ?></p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST">
                <button type="submit" name="like">Like</button>
            </form>
            <form method="POST">
                <select name="board_id">
                    <?php while($board = $result_boards->fetch_assoc()): ?>
                        <option value="<?php echo $board['id']; ?>"><?php echo $board['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="save_to_board">Save to Board</button>
            </form>
            <h3>Add Comment</h3>
            <form method="POST">
                <textarea name="comment" required></textarea>
                <button type="submit">Comment</button>
            </form>
        <?php endif; ?>
        <div class="comments">
            <h3>Comments</h3>
            <?php while($comment = $result_comments->fetch_assoc()): ?>
                <div class="comment">
                    <strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['comment']; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
