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
if ($conn->query($sql_check)->num_rows == 0) {
    echo "Not authorized.";
    exit;
}

$sql_pin = "SELECT * FROM pins WHERE id = $pin_id";
$pin = $conn->query($sql_pin)->fetch_assoc();

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'];

    $sql_update = "UPDATE pins SET title = '$title', description = '$description', category_id = $category_id WHERE id = $pin_id";
    if ($conn->query($sql_update) === TRUE) {
        // Handle image update if uploaded
        if ($_FILES["image"]["name"]) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
                $sql_image = "UPDATE pins SET image_url = '$image_url' WHERE id = $pin_id";
                $conn->query($sql_image);
            }
        }
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
    <title>Edit Pin - Pinterest Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        form { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #e60023; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background: #ff4d4d; }
        img { width: 100%; height: auto; margin-bottom: 10px; }
        @media (max-width: 600px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Edit Pin</h2>
        <img src="<?php echo $pin['image_url']; ?>" alt="Current Image">
        <input type="text" name="title" value="<?php echo $pin['title']; ?>" required>
        <textarea name="description"><?php echo $pin['description']; ?></textarea>
        <select name="category_id" required>
            <?php while($cat = $result_categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $pin['category_id']) echo 'selected'; ?>><?php echo $cat['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update</button>
    </form>
</body>
</html>
