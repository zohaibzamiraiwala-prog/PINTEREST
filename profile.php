<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$user = $conn->query($sql_user)->fetch_assoc();

// Fetch user's boards
$sql_boards = "SELECT * FROM boards WHERE user_id = $user_id";
$result_boards = $conn->query($sql_boards);

// Fetch user's pins
$sql_pins = "SELECT * FROM pins WHERE user_id = $user_id";
$result_pins = $conn->query($sql_pins);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_bio'])) {
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $sql_update = "UPDATE users SET bio = '$bio' WHERE id = $user_id";
    $conn->query($sql_update);
    echo "<script>window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .profile { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        img { border-radius: 50%; width: 100px; height: 100px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .item { background: #fff; padding: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        form { margin-top: 20px; }
        textarea { width: 100%; padding: 10px; }
        button { background: #e60023; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background: #ff4d4d; }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } }
        /* Amazing CSS: Profile glow, animations */
        .profile:hover { box-shadow: 0 6px 20px rgba(230,0,35,0.3); transition: box-shadow 0.3s ease; }
    </style>
</head>
<body>
    <div class="profile">
        <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Pic">
        <h2><?php echo $user['username']; ?></h2>
        <p>Email: <?php echo $user['email']; ?></p>
        <p>Bio: <?php echo $user['bio']; ?></p>
        <form method="POST">
            <textarea name="bio" placeholder="Update Bio"><?php echo $user['bio']; ?></textarea>
            <button type="submit" name="update_bio">Update Bio</button>
        </form>
        <h3>Your Boards</h3>
        <div class="grid">
            <?php while($board = $result_boards->fetch_assoc()): ?>
                <div class="item">
                    <h4><?php echo $board['name']; ?></h4>
                    <a href="view_board.php?id=<?php echo $board['id']; ?>">View</a>
                </div>
            <?php endwhile; ?>
        </div>
        <h3>Your Pins</h3>
        <div class="grid">
            <?php while($pin = $result_pins->fetch_assoc()): ?>
                <div class="item">
                    <img src="<?php echo $pin['image_url']; ?>" alt="<?php echo $pin['title']; ?>" style="width:100%; height:auto;">
                    <h4><?php echo $pin['title']; ?></h4>
                    <a href="view_pin.php?id=<?php echo $pin['id']; ?>">View</a>
                    <a href="edit_pin.php?id=<?php echo $pin['id']; ?>">Edit</a>
                    <a href="delete_pin.php?id=<?php echo $pin['id']; ?>" onclick="return confirm('Delete?');">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
