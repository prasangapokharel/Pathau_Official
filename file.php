<?php
session_start();

if (!isset($_SESSION['snippet_history'])) {
    $_SESSION['snippet_history'] = array();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathauc1_fileshare";

$conn = new mysqli($servername, $username, $password, $dbname);
ini_set('max_execution_time', 300);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$snippet_id = "";
$snippet_success_message = "";
$snippet_error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $snippet_id = sprintf("%03d", mt_rand(1, 999));

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        if ($_FILES['fileToUpload']['size'] > 50 * 1024 * 1024) { // Adjusted the size to 50MB
            $snippet_error_message = "Error: File size exceeds the maximum limit of 50MB.";
        } else {
            $filename = $_FILES['fileToUpload']['name'];
            $filedata = file_get_contents($_FILES['fileToUpload']['tmp_name']);
            if (!empty($filedata)) {
                // Move uploaded file to the desired location
                $targetPath = "/home/pathauc1/domains/pathau.com/public_html/Storage/" . $filename;
                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetPath)) {
                    // File moved successfully, now insert file path into database
                    $sql = "INSERT INTO files (filename, file_path, snippet_id) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $filename, $targetPath, $snippet_id);
            
                    if ($stmt->execute()) {
                        // File path inserted successfully, now update snippet history
                        $_SESSION['snippet_history'][] = $snippet_id;
            
                        // Redirect to getimg.php with parameters
                        header("Location: https://www.pathau.com/getimg.php?snippet_id=$snippet_id&filename=" . urlencode($filename));
                        exit(); // Ensure script stops here
                    } else {
                        $snippet_error_message = "Error: Failed to insert file path into database.";
                    }
            
                    $stmt->close();
                } else {
                    $snippet_error_message = "Error: Failed to move the uploaded file.";
                }
            } else {
                $snippet_error_message = "Error: No file provided.";
            }
        }
    } else {
        $snippet_error_message = "Error: No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PATHAU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="icon" href="tgg.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/file.css">
</head>
<body>
    <div class="header">
        <h1>PATHAU</h1>
        <div class="slo">
            <img class="web" src="https://www.freeiconspng.com/thumbs/secure-icon-png/lock-icon--20.png">
            <p class="slog">Now, share program and files privately.</p>
        </div>
    </div>

    <div class="container">
        <button class="history" onclick="toggleHistoryPopup()">History</button>

        <!-- History section popup -->
        <div id="historyPopup" class="history-section">
            <span class="close-button" onclick="toggleHistoryPopup()">X</span>
            <h2 class="htry">Share History</h2>
            <ul>
                <?php
                // Display snippet history from session
                if (isset($_SESSION['snippet_history'])) {
                    foreach ($_SESSION['snippet_history'] as $history_snippet_id) {
                        echo "<li>$history_snippet_id</li>";
                    }
                }
                ?>
            </ul>
        </div>
        <h2>Share Code</h2>
        <a href="index.php" id="fileLink">
            <img src="codeon.jpg">
        </a>
        <form id="shareForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="up" id="dropZone">
                <span class="file-name" id="fileName"></span>
                <label for="fileToUpload" class="upload-btn">Upload File<br><p>Supported formats: PNG, JPG, PDF, PPT</p></label><br>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <br><button class="share" type="submit" onclick="confirmShare()">Share File</button>
        </form>

        <button onclick="showSearch()">Get File</button>
    </div>

    <div class="search-bar" id="searchBar">
        <div class="search-container">
            <button class="close-btn" onclick="hideSearch()">Close</button>
            <h2>Enter Unique ID</h2>
            <form action="getimg.php" method="get">
                <input type="text" name="snippet_id" placeholder="Enter Unique ID" required>
                <button type="submit">Get File</button>
            </form>
        </div>
    </div>

    <footer>© 2024 <a class="prom" href="https://t.me/PATHAUEN">Pathau</a> Tech. All rights reserved.</footer>

    <!-- Loading overlay HTML -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Function to toggle history popup visibility
        function toggleHistoryPopup() {
            var historyPopup = document.getElementById('historyPopup');
            if (historyPopup.classList.contains('visible')) {
                historyPopup.classList.remove('visible');
            } else {
                historyPopup.classList.add('visible');
            }
        }

        // Function to show search modal
        function showSearch() {
            document.getElementById('searchBar').classList.add('active');
        }

        // Function to hide search modal
        function hideSearch() {
            document.getElementById('searchBar').classList.remove('active');
        }

        // Function to update file name display
        document.getElementById('fileToUpload').addEventListener('change', function() {
            document.getElementById('fileName').innerText = this.files[0].name;
        });

        // Function to show loading spinner
        function showLoadingSpinner() {
            document.getElementById('loadingOverlay').style.display = 'block';
        }

        // Function to hide loading spinner
        function hideLoadingSpinner() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Attach the showLoadingSpinner function to the form submission event
        document.getElementById('shareForm').addEventListener('submit', function() {
            showLoadingSpinner();
        });

        // Confirm share function
        function confirmShare() {
            if (confirm("Ready to generate id?")) {
                document.getElementById("shareForm").submit();
            }
        }
    </script>
</body>
</html>
