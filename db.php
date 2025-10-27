<?php
$servername = "localhost"; // Assuming localhost, change if needed
$username = "uiumzmgo1eg2q";
$password = "kuqi5gwec3tv";
$dbname = "dbzzrc4fnnjrsg";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
