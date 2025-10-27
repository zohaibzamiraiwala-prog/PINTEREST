<?php
session_start();
include 'db.php';
 
// Fetch trending pins (e.g., most liked in last 7 days)
$sql_trending = "SELECT p.*, u.username, COUNT(l.pin_id) AS like_count 
                 FROM pins p 
                 JOIN users u ON p.user_id = u.id 
                 LEFT JOIN likes l ON p.id = l.pin_id 
                 WHERE p.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) 
                 GROUP BY p.id 
                 ORDER BY like_count DESC, p.created_at DESC 
                 LIMIT 20";
$result_trending = $conn->query($sql_trending);
 
// Fetch popular pins (all-time likes)
$sql_popular = "SELECT p.*, u.username, COUNT(l.pin_id) AS like_count 
                FROM pins p 
                JOIN users u ON p.user_id = u.id 
                LEFT JOIN likes l ON p.id = l.pin_id 
                GROUP BY p.id 
                ORDER BY like_count DESC 
                LIMIT 20";
$result_popular = $conn->query($sql_popular);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #e60023; color: white; padding: 10px; text-align: center; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; padding: 20px; }
        .pin { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .pin img { width: 100%; height: auto; }
        .pin-info { padding: 10px; }
        .pin-info h3 { margin: 0; font-size: 16px; }
        .pin-info p { margin: 5px 0; font-size: 14px; color: #666; }
        nav { background: #fff; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav a { margin: 0 10px; text-decoration: none; color: #e60023; }
        @media (max-width: 600px) { .grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); } }
        /* Amazing CSS: Smooth hover effects, gradients, and responsive scaling */
        .pin:hover { transform: scale(1.05); transition: transform 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        header { background: linear-gradient(to right, #e60023, #ff4d4d); }
    </style>
</head>
<body>
    <header>
        <h1>Pinterest Clone</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="upload_pin.php">Upload Pin</a>
                <a href="create_board.php">Create Board</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="signup.php">Signup</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <a href="search.php">Search</a>
        </nav>
    </header>
    <section>
        <h2>Trending Images</h2>
        <div class="grid">
            <?php while($row = $result_trending->fetch_assoc()): ?>
                <div class="pin">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
                    <div class="pin-info">
                        <h3><?php echo $row['title']; ?></h3>
                        <p>By: <?php echo $row['username']; ?></p>
                        <a href="view_pin.php?id=<?php echo $row['id']; ?>">View</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <section>
        <h2>Popular Images</h2>
        <div class="grid">
            <?php while($row = $result_popular->fetch_assoc()): ?>
                <div class="pin">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
                    <div class="pin-info">
                        <h3><?php echo $row['title']; ?></h3>
                        <p>By: <?php echo $row['username']; ?></p>
                        <a href="view_pin.php?id=<?php echo $row['id']; ?>">View</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <script>
        // JS for any client-side needs, but no redirection here
    </script>
</body>
</html>
