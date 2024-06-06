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

// Initialize the code variable
$code = "";
$snippet_id = "";

// Check if snippet ID is available in POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['snippet_id'])) {
    $snippet_id = $_POST['snippet_id'];
}

// Check if snippet ID is available in URL parameters
if (isset($_GET['snippet_id'])) {
    $snippet_id = $_GET['snippet_id'];
}

// Query to retrieve code snippet based on the snippet ID
$sql = "SELECT code, upload_time FROM snippets WHERE snippet_id = ?";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("s", $snippet_id);

// Execute statement
$stmt->execute();

// Store result
$result = $stmt->get_result();

// Check if code snippet is found
if ($result->num_rows > 0) {
    // Fetch code snippet and upload time
    $row = $result->fetch_assoc();
    $code = htmlspecialchars($row["code"]);
    
    // Calculate time left for deletion
    $upload_time = new DateTime($row["upload_time"]);
    $current_time = new DateTime();
    $diff = $current_time->diff($upload_time);
    $hours_left = 24 - $diff->h;
    $minutes_left = 60 - $diff->i;
    $seconds_left = 60 - $diff->s;
} else {
    // Code snippet not found
    $code = "Code not available.";
}

// Close statement
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8549584572005502" crossorigin="anonymous"></script>
    <meta name="google-adsense-account" content="ca-pub-8549584572005502">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="icon" href="tgg.jpg" type="image/x-icon">
    <link rel="stylesheet" href="./css/get_code.css">
    <title>Get Code</title>
</head>
<body>
    <div class="header">
        <a class="tit" href="https://www.pathau.com/"><h1>PATHAU</h1></a>
        <div class="slo">
            <img class="web" src="https://www.freeiconspng.com/thumbs/secure-icon-png/lock-icon--20.png">
            <p class="slog">Now, share program and files privately.</p>    
        </div>
    </div>

    <div class="container">
        <div class="back-btn-container">
            <a href="javascript:history.back()" class="back-btn">Back</a>
        </div>
        <div class="keyd">
            <p class="key">Share ID: <?php echo $snippet_id; ?></p>
        </div>
        <br>
        <div class="code-wrapper" id="code-wrapper">
            <?php echo $code; ?>
        </div>
        <button onclick="generateAndCopyLink()">Share Link</button>
        <button class="copy-btn" onclick="copyCode()">Copy Code</button>
        <div class="copy-success-message" id="copy-success-message">Code copied to clipboard!</div>
        <span class="qr-icon" onclick="showQRPopup()" style="margin-top: 15px; cursor: pointer;">Show QR</span>
        <div class="timer">Your code deletion time left: <span id="countdown"></span></div>

        <!-- QR code section -->
        <div id="qr-popup" class="popup">
            <div class="popup-content">
                <!-- Close button -->
                <span class="close-btn" onclick="hideQRPopup()">x</span>
                <!-- QR code image -->
                <img id="popup-qr-code-img" src="" >
            </div>
        </div>

        <!-- Container to display generated link -->
        <div id="shareLinkContainer"></div>
    </div>
    <footer>© 2024 <a class="prom" href="https://www.facebook.com/profile.php?id=61556481189298&mibextid=opq0tG">Pathau</a> Tech. All rights reserved.</footer>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8549584572005502" crossorigin="anonymous"></script>
    <script>
        // Function to handle keydown event
        document.addEventListener("keydown", function(event) {
            // Check if the pressed key is the backspace key
            if (event.key === 'Backspace') {
                // Call the function you want to execute when the backspace key is pressed
                backButtonFunction();
            }
        });

        // Define the function to execute when the backspace key is pressed
        function backButtonFunction() {
            // Add your code here to execute when the backspace key is pressed
            // For example, you can redirect the user to the previous page
            window.history.back();
        }

        // Function to handle keydown event
        document.addEventListener("keydown", function(event) {
            // Check if Ctrl key and A key are pressed simultaneously
            if (event.ctrlKey && event.key === 'a') {
                // Prevent the default browser behavior (text selection)
                event.preventDefault();
            }
        });

        // Function to update the countdown
        function updateCountdown(hoursLeft, minutesLeft, secondsLeft) {
            // Display the countdown in the specified span element
            document.getElementById('countdown').textContent = `${hoursLeft}h ${minutesLeft}m ${secondsLeft}s`;
        }

        // Function to calculate and update the countdown every second
        function startCountdown(hoursLeft, minutesLeft, secondsLeft) {
            // Update the countdown initially
            updateCountdown(hoursLeft, minutesLeft, secondsLeft);
            
            // Update the countdown every second
            setInterval(function() {
                // Decrease the remaining time by one second
                secondsLeft--;
                
                // If seconds reach zero, decrease minutes and reset seconds
                if (secondsLeft < 0) {
                    minutesLeft--;
                    secondsLeft = 59;
                }
                
                // If minutes reach zero, decrease hours and reset minutes
                if (minutesLeft < 0) {
                    hoursLeft--;
                    minutesLeft = 59;
                }
                
                // If hours reach zero, stop the countdown
                if (hoursLeft < 0) {
                    clearInterval(countdownInterval);
                    updateCountdown(0, 0, 0);
                } else {
                    // Update the countdown display
                    updateCountdown(hoursLeft, minutesLeft, secondsLeft);
                }
            }, 1000); // Update every second (1000 milliseconds)
        }

        // Call the function to start the countdown with the calculated values
        startCountdown(<?php echo $hours_left; ?>, <?php echo $minutes_left; ?>, <?php echo $seconds_left; ?>);

        function generateAndCopyLink() {
            // Replace '$snippet_id' with the actual PHP variable containing the snippet ID
            var snippetId = '<?php echo $snippet_id; ?>';
            
            // Construct the shareable link
            var shareableLink = 'https://www.pathau.com/get_code.php?snippet_id=' + snippetId;

            // Create a temporary input element
            var tempInput = document.createElement("input");
            tempInput.value = shareableLink;

            // Append the input element to the body
            document.body.appendChild(tempInput);

            // Select the value of the input element
            tempInput.select();

            // Copy the selected text to the clipboard
            document.execCommand("copy");

            // Remove the temporary input element
            document.body.removeChild(tempInput);
        }

        function deleteSnippet(snippetId) {
            if (confirm("Are you sure you want to delete this code snippet?")) {
                // Send an AJAX request to delete the snippet
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    }
                };
                xhttp.open("POST", "delete_snippet.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("snippet_id=" + snippetId);
            }
        }

        // Function to generate QR code
        function generateQRCode(snippetId) {
            // URL for downloading the code snippet
            var downloadUrl = "https://www.pathau.com/codedownload.php?snippet_id=" + snippetId;

            // API endpoint for generating QR code
            var qrCodeApiUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" + encodeURIComponent(downloadUrl) + "&size=150x150";

            // Return the generated QR code URL
            return qrCodeApiUrl;
        }

        function showQRPopup() {
            var snippetId = '<?php echo $snippet_id; ?>';
            var qrCodeUrl = generateQRCode(snippetId);
            var qrPopup = document.getElementById('qr-popup');
            var qrImage = document.getElementById('popup-qr-code-img');
            qrImage.src = qrCodeUrl;
            qrPopup.style.display = 'block';
        }

        function hideQRPopup() {
            var qrPopup = document.getElementById('qr-popup');
            qrPopup.style.display = 'none';
        }

        // Function to copy code
        function copyCode() {
            // Get the code content
            var code = document.getElementById('code-wrapper').textContent;

            // Create a temporary input element
            var tempInput = document.createElement('textarea');
            tempInput.value = code;

            // Append the input element to the body
            document.body.appendChild(tempInput);

            // Select the value of the input element
            tempInput.select();

            // Copy the selected text to the clipboard
            document.execCommand('copy');

            // Remove the temporary input element
            document.body.removeChild(tempInput);

            // Show success message
            var successMessage = document.getElementById('copy-success-message');
            successMessage.style.display = 'block';

            // Hide success message after 3 seconds
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
