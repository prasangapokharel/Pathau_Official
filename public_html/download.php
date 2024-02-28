<?php
// Check if snippet ID is provided in the URL
if (isset($_GET['snippet_id'])) {
// Database connection
$servername = "localhost";
$username = "pathauc1_fileshare";
$password = "dZkFnU7h3Teb7WjTWsDn";
$dbname = "pathauc1_fileshare";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch filename and file data from the database based on the snippet ID
    $snippet_id = $_GET['snippet_id'];
    $sql = "SELECT filename, file_data FROM files WHERE snippet_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $snippet_id);
    $stmt->execute();
    $stmt->bind_result($filename, $file_data);
    
    if ($stmt->fetch()) {
        // Output appropriate headers
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

        // Prevent caching of the file
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Output the file data
        echo $file_data;
    } else {
        // Handle the case when snippet ID is not found
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
