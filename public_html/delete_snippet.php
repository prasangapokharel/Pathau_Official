<?php
// Database connection
$servername = "localhost";
$username = "pathauc1_codeshare";
$password = "YBKH9PAYWzNp7jU5qHX9";
$dbname = "pathauc1_codeshare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the snippet ID is received
if (isset($_POST['snippet_id'])) {
    $snippet_id = $_POST['snippet_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM snippets WHERE snippet_id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $snippet_id);

    // Execute statement
    if ($stmt->execute()) {
        echo "Snippet deleted successfully.";
    } else {
        echo "Error deleting snippet: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
