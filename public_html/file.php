<?php
// Start PHP session
session_start();

// Check if the snippet history session variable is not set for the current page
if (!isset($_SESSION['snippet_history'])) {
    // Initialize snippet history session variable for the current page
    $_SESSION['snippet_history'] = array();
}

// Database connection
$servername = "localhost";
$username = "pathauc1_fileshare";
$password = "dZkFnU7h3Teb7WjTWsDn";
$dbname = "pathauc1_fileshare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
ini_set('max_execution_time', 300); // Set to 5 minutes (300 seconds)

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$snippet_id = ""; // Initialize snippet ID variable
$snippet_success_message = ""; // Initialize snippet success message variable
$snippet_error_message = ""; // Initialize snippet error message variable

// If the form is submitted to add code snippet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate 3-digit unique ID
    $snippet_id = sprintf("%03d", mt_rand(1, 999)); // Generate a random 3-digit number

    // Check if a file was uploaded
    if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        // Check file size
        if ($_FILES['fileToUpload']['size'] > 50 * 1024 * 1024) {
            $snippet_error_message = "Error: File size exceeds the maximum limit of 50MB.";
        } else {
            // Read the uploaded file
            $filename = $_FILES['fileToUpload']['name'];
            $filedata = file_get_contents($_FILES['fileToUpload']['tmp_name']);

            // Insert code snippet into the database using prepared statement
            if(!empty($filedata)) {
                $sql = "INSERT INTO files (filename, file_data, snippet_id) VALUES (?, ?, ?)";

                // Prepare statement
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param("sss", $filename, $filedata, $snippet_id);

                // Execute statement
                if ($stmt->execute()) {
                    // Add snippet ID to session history for the current page
                    $_SESSION['snippet_history'][] = $snippet_id;

                    // Redirect to the file download page with the snippet ID in the URL
                    header("Location: https://www.pathau.com/getimg.php?snippet_id=$snippet_id&filename=$filename");
                    exit(); // Ensure that subsequent code is not executed after redirection
                } else {
                    $snippet_error_message = "Error: " . $sql . "<br>" . $stmt->error; // Set error message
                }

                // Close statement
                $stmt->close();
            } else {
                $snippet_error_message = "Error: No file provided."; // Set error message if no file is provided
            }
        }
    } else {
        $snippet_error_message = "Error: No file uploaded."; // Set error message if no file is uploaded
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
    <style>
  * {
            margin: 0;
            padding: 0;
            display: fixed;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff; /* Light grey background */
            color: #f2f2f; /* Dark text color */
            overflow: hidden;
            transition: background-color 0.3s, color 0.3s; /* Smooth transition for background and text color */
        }

        .header {
            background-color: #172b4d; 
            color: #fff; /* White text color */
            text-align: center;
            padding: 10px 0;
            font-family: 'Inter', sans-serif;
        }

        .container {
            height: 510px;
            width: 80%;
            margin: 20px auto;
            background: #fff; /* Gradient background */
            padding: 20px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            position: relative;
            transition: box-shadow 0.3s; /* Smooth transition for box shadow */
            box-shadow: 0 4px 10px grey; /* Subtle box shadow */
        }
        h2 {
            color: #172b4d;
        }

        .snippet-id {
            font-weight: bold;
            display: inline-block;
            background-color: #fff; /* Light grey background */
 /* Dark background for snippet ID */
            color: #172b4d;
            padding: 5px;
            border-radius: 3px;
            margin-bottom: 10px;
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

        .copy-btn {
            font-weight: bold;
            padding: 5px 10px;
            background-color: #fff; /* Light grey background */
            color: #172b4d;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s; /* Smooth transition for background color */
            margin-left: 10px;
            font-size: 16px;
            outline: none; /* Remove outline */
        }

        .copy-btn:hover {
            background-color: #172b4d; 
            color: #fff; /* Darker color on hover */
        }

        input[type="file"] {
            display: none; /* Hide the file input initially */
        }

        .search-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            transition: opacity 0.3s; /* Smooth transition for opacity */
            opacity: 0;
        }

        .search-bar.active {
            display: block;
            opacity: 1;
        }

        .search-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:#172b4d;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px;
            background-color: transparent; 
 /* Dark background for close button */
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

        .close-btn:hover {
            color: #fff;/* Darker color on hover */
        }

        .search-container h2 {
            margin-top: 0;
            color: #fff;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .search-container input[type="text"],
        .search-container button[type="submit"] {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: border-color 0.3s, background-color 0.3s, color 0.3s; /* Smooth transition for border, background, and text color */
        }

        .search-container input[type="text"]:focus,
        .search-container button[type="submit"]:hover {
            border-color: #2f2f2f; /* Dark border color on focus */
            background-color: #172b4d; 
            color: #fff; /* White text color on hover */
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
        footer {
            background-color: #fff; /* Light grey background */

            text-align: center;
            color: #172b4d;
            text-align: center;
        }

        .upload-btn {
            display: inline-block;
             padding: 80px 40px;
             background-color: #172b4d; 
            color: #fff;
            font-size: 15px;
            border: 1px solid #172b4d;
            display: fixed;
           margin-right: 10px;
            width:60%;
            cursor: pointer;
            margin-top: 20px;
            margin-bottom: 20px;
            font-family: 'Inter', sans-serif;

            border-radius: 18px;
            outline: none; /* Remove outline */
        }

        .up {
            padding: 10px;
            height: 250px;
            max-width: 100%;
            border: 1px;
            text-align: center;
            border-radius: 8px;
            background-color: #fff; /* Light grey background */

        }

        .upload-btn:hover {
            background-color: #172b4d; 
            color: #fff;
        }

        /* Hide the default file input */
        input[type="file"] {
            display: none;
        }  

        button {
            display: inline;
            padding: 10px 20px;
            background-color: #fff;
            color: #172b4d;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            outline: none;
        }

      
        button.share{
               display: inline;
            padding: 10px 20px;
            background-color: #172b4d;
            color: #fff;
            font-size: 16px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            outline: none;
        }

        img {
            height: 30px;
            width: 30px;
            border: 1px;
            border-radius: 100px;
        }
#fileLink img {
    transition: transform 0.5s ease;
}

#fileLink img:hover {
    transform: rotateY(180deg);
}
        .slog {
            color: #fff;
            text-align: center;
            margin-top: 10px;
            display: flex;
        }

        .web {
            width: 30px;
            height: 30px;
            margin-bottom: -4px;
            display: flex;
        }

        .slo {
            display: flex;
            justify-content: center; /* Align content horizontally at the center */ /* Align content horizontally at the center */
            align-items: center; 
        }

        .prom {
            text-decoration: none;
            color: #172b4d;
        }

        /* Loading animation styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid #f3f3f3; /* Light grey border */
            border-top: 4px solid #3498db; /* Blue border for spinner */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 0.5s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .file-name {
            color: #172b4d;
            font-size: 14px;
            margin-top: 10px;
            display: block;
        }
        p{
            color: #fff;
            font-weight: bold;
        }
        .code{
            border:none;
            border-radius: 2px;
        }
        
        .history-section {
            background-color: #fff; /* Light red background */
            padding: 10px;
            border:3px solid #172b4d;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none; /* Initially hide the history section */
            position: fixed; /* Position the popup */
            top: 52%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Center the popup */
            z-index: 9999; /* Ensure the popup is above other elements */
        }

        .history-section.visible {
            display: block; /* Display the history section when it's visible */
        }

        .history-section ul {
            list-style-type: none; /* Remove bullet points */
            padding-left: 0; /* Remove default padding */
        }

        .history-section ul li {
            border: 3px;
            border-radius: 8px;
            padding: 5px 10px;
            background-color:#172b4d;
            color: #fff; /* Set text color to black */
            margin-bottom: 5px;
            text-align:center; /* Add some space between list items */
        }
.history{
    padding-left: 0px;
    font-weight:bold;
    
}
  .close-button {
      font-weight: bold;
            position: absolute;
            top: 5px;
            color:#172b4d;
            right: 5px;
            cursor: pointer;
            font-size: 10px;
        }
    .clean {
    position: absolute;
    bottom: 20px; /* Adjust this value as needed */
    right: 20px; /* Adjust this value as needed */
    display: flex;
    align-items: center;
}

/* Adjust the image width */
.clean img {
    height:40px;
    width: 40px;
    /* Adjust the width as needed */
     /* Maintain aspect ratio */
}
.htry{
margin-bottom: 5px;
}

    </style>
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
            <div class="up">
            <span class="file-name" id="fileName"></span>
                <label for="fileToUpload" class="upload-btn">Upload File<br><p>we accept png, jpg, pdf, ppt only!</label><br>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
               <br><button class="share" type="submit" onclick="confirmShare()">Share File</button>
        </form>

        <button onclick="showSearch()">Get File</button>
        <button class="clean" onclick="clearHistory()"><img class="gf" src="gif.gif" /></button>
    </div>
  
    <div class="search-bar" id="searchBar">
        <div class="search-container">
            <button class="close-btn" onclick="hideSearch()">Close</button>
            <h2>Enter Unique ID</h2>
            <form action="getimg.php" method="post">
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

        // Function to clear session history
        function clearHistory() {
            // Send an AJAX request to clear the session history
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'clear_history.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Reload the page after clearing the history
                    location.reload();
                }
            };
            xhr.send();
        }
         // Register service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.error('ServiceWorker registration failed: ', err);
                });
            });
        }

        // Cache resources
        self.addEventListener('install', function(event) {
            event.waitUntil(
                caches.open('v1').then(function(cache) {
                    return cache.addAll([
                        // Add URLs of resources to cache here
                    ]);
                })
            );
        });

        // Fetch resources from cache
        self.addEventListener('fetch', function(event) {
            event.respondWith(
                caches.match(event.request).then(function(response) {
                    return response || fetch(event.request);
                })
            );
        });

        // Store data in localStorage
        function storeData(key, value) {
            localStorage.setItem(key, value);
        }

        // Retrieve data from localStorage
        function getData(key) {
            return localStorage.getItem(key);
        }

      document.getElementById('fileToUpload').addEventListener('change', function() {
            document.getElementById('fileName').innerText = this.files[0].name;
        });

        // Attach event listener for Enter key press inside file upload input
        document.getElementById('fileToUpload').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent default form submission
                document.getElementById('shareForm').submit(); // Trigger form submission
            }
        });
    function showSearch() {
    // Construct the URL with snippet ID and filename parameters
    const snippetID = "<?php echo $snippet_id; ?>";
    const filename = "<?php echo $filename; ?>"; // Assuming $filename is defined elsewhere in your code
    const url = `https://www.pathau.com/getimg.php?snippet_id=${snippetID}&filename=${filename}`;

    // Redirect the user to the getimg.php page
    window.location.href = url;
}



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

        // You can hide the loading spinner once the upload is complete using your existing logic
        function confirmShare() {
            if (confirm("Ready to generate id?")) {
                document.getElementById("shareForm").submit();
            }
        }

        function showSearch() {
            document.getElementById('searchBar').classList.add('active');
        }

        function hideSearch() {
            document.getElementById('searchBar').classList.remove('active');
        }

        function copySnippetID() {
            const snippetID = "<?php echo $snippet_id; ?>";
            navigator.clipboard.writeText(snippetID);
        }
    </script>
</body>
</html>
