<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathauc1_codeshare";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$snippet_id = ""; // Initialize snippet ID variable
$code = ""; // Initialize code variable

// If snippet ID is provided via GET request
if(isset($_GET['snippet_id'])) {
    $snippet_id = $_GET['snippet_id'];

    // Select code snippet from database based on snippet ID
    $sql = "SELECT code FROM snippets WHERE snippet_id = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $snippet_id);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($code);

    // Fetch result
    $stmt->fetch();

    // Close statement
    $stmt->close();
}

// If code snippet is available, initiate file download
if(!empty($code)) {
    // Output code as file for download
    header('Content-Description: File Transfer');
    header('Content-Type: text/plain'); // Set content type as plain text
    header('Content-Disposition: attachment; filename="pathau.com.txt"'); // Set filename for download
    echo $code; // Output the code
    exit;
} else {
    echo "Code snippet not found.";
}
?>
