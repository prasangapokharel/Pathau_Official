<?php
$servername = "localhost";
$username = "pathauc1_fileshare";
$password = "dZkFnU7h3Teb7WjTWsDn";
$dbname = "pathauc1_fileshare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted to upload file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if an image file is uploaded
    if(isset($_FILES['fileToUpload'])) {
        // Check if there are any errors during file upload
        if ($_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
            die("Upload failed with error code " . $_FILES['fileToUpload']['error']);
        }
        
        $filename = $_FILES['fileToUpload']['name']; // Get the filename
        $file_data = file_get_contents($_FILES['fileToUpload']['tmp_name']); // Get the file data
    } else {
        $filename = null; // Set filename to null if no file is uploaded
        $file_data = null; // Set file data to null if no file is uploaded
    }

    // Insert file into the database using prepared statement
    $sql = "INSERT INTO files (filename, file_data) VALUES (?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sb", $filename, $file_data);

    // Execute statement
    if ($stmt->execute()) {
        $snippet_success_message = "File uploaded successfully."; // Set success message
    } else {
        $snippet_error_message = "Error: " . $sql . "<br>" . $stmt->error; // Set error message
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>