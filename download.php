<?php
// Check if snippet ID is provided in the URL
if (isset($_GET['snippet_id'])) {


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathauc1_fileshare";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch file path from the database based on the snippet ID
    $snippet_id = $_GET['snippet_id'];
    $sql = "SELECT file_path FROM files WHERE snippet_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $snippet_id);
    $stmt->execute();
    $stmt->bind_result($file_path);

    if ($stmt->fetch()) {
        // Output appropriate headers for file download
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"");

        // Prevent caching of the file
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Output the file by reading it from its path
        readfile($file_path);
    } else {
        // Handle the case when snippet ID is not found or no file path is associated with it
        echo "File not found!";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case when snippet ID is not provided in the URL
    echo "Snippet ID not provided!";
}
?>
