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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="icon" href="tgg.jpg" type="image/x-icon">
    <title>Get Code</title>
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
    background-color: #fff;   /* Light grey background */
    padding: 20px;
    overflow: hidden;
    border-radius: 10px;
    font-family: 'Inter', sans-serif;
    position: relative;
    transition: box-shadow 0.3s; /* Smooth transition for box shadow */
    box-shadow: 0 4px 10px #172b4d; /* Subtle box shadow */
}
 footer {
            background-color: #fff; /* Light grey background */

            text-align: center;
            color: #172b4d;
            text-align: center;
        }
 img{
    height: 30px;
    width: 30px;
    border: 1px;
    border-radius: 100px;
}
        .code-wrapper {
            overflow: hidden;
            max-height: 300px;
            border: 1px solid #555;
            border-radius: 5px;
            padding: 10px 5px;
            margin: 20px 0px;
            background-color: #333; /* Dark code wrapper background color */
            color: #fff;  /* Text color */
        }

        .copy-btn {
            position: absolute;
            top: 10px;
             margin-top: 9px;
            right: 10px;
            padding: 5px;
            background-color: #fff;  /* Button background color */
            color: #172b4d; 
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .copy-btn:hover {
            background-color: #fff;
            color: #172b4d; /* Button hover background color */
        }

        button {
            padding: 10px 20px;
            background-color: #172b4d;  /* Button background color */
            color: #fff; 
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        button:hover {
           background-color: #172b4d;  /* Button background color */
            color: #fff;  Button hover background color */
        }

     

        input[type="text"] {
            
            width: 100%;
            padding: 10px;
            border: 1px solid #172b4d; /* Border color */
            border-radius: 5px;
            background-color: #172b4d; /* Dark background color */
            color: #fff; /* Text color */
            margin-bottom: 20px; /* Add margin bottom */
            box-sizing: border-box; /* Ensure padding and border are included in element's total width and height */
        }

        input[type="text"]:focus {
            border-color: #007bff; /* Focus border color */
        }
        .copy-success-message {
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff; /* Green background color */
            color: #172b4d;
            padding: 10px 10px; /* Padding */
            border-radius: 18px; /* Rounded corners */
            opacity: 0; /* Initially hidden */
            transition: opacity 0.5s; /* Fade in/out transition */
            z-index: 9999; /* Ensure it's above other elements */
        }
            .back-btn {
            background-color: #fff;  /* Button background color */
            color: #172b4d;  /* Text color */
            padding: 5px 5px; /* Padding */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Cursor style */
            transition: background-color 0.3s; /* Smooth transition for background color */
            text-decoration: none; /* Remove default underline */
            display: inline-block; /* Display as inline-block to adjust size */
        }

        .back-btn:hover {
            background-color: #fff; /* Darker background color on hover */
        }

        /* Adjust margin for the back button */
        .back-btn-container {
            margin-bottom: 10px;
        }
        .code-wrapper {
            
    overflow: hidden;
    max-height: 180px;
    border: 0px solid #172b4d;
    border-radius: 8px;
    padding: 10px 5px;
    margin: 20px 0px;
    background-color: #fff;  
    color: #172b4d;  /* Text color */
}
.web{
    width: 30px;
    height: 30px;
    margin-bottom: -4px;
   display: flex;
   }
   .slo{
display: flex;
justify-content: center; /* Align content horizontally at the center */ /* Align content horizontally at the center */
align-items: center; 

   }
   .prom{
       text-decoration: none;
      color: #172b4d;
   }
   p{
    color: #fff;
}
.slog{
    font-weigth: bold;
}
    span.qr-icon {
            width: 60px;
            height: 30px;
            cursor: pointer;
            margin-left: 5px;
            padding-left:5px;
            font-size: 16px;
            display:inline;
            color: #172b4d;
            border-radius: 2px;
            margin-bottom: -6px; /* Adjust as needed */
        }

        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
            z-index: 9999; /* Ensure it's on top of other elements */
        }

        .popup-content {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: transparent;
            padding: 20px;
            border-radius: 10px;
            box-shadow:#172b4d; /* Add shadow for better visibility */
        }

        .close-btn {
            position: absolute;
            top: 10px;
            padding-left: 5px;
            right: 10px;
            color:#fff;
            border: 5px;
            border-radius: 10px;
            background-color:transparent;
            cursor: pointer;
        }

        /* Additional styles for QR code image in the popup */
        #popup-qr-code-img {
            width: auto; /* Adjust the size as needed */
            height: auto;
            color: #fff;
               background-color: rgba(0, 0, 0, 0.5);
            border-radius: 6px;
            border: 15px solid #fff; /* Add border for better visibility */
        }
 .delete-btn{
             background-color: red; 
            border: none;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 10px;
        }
         .delete-btn:hover {
           background-color: red;  /* Button background color */
            color: #fff;  Button hover background color */
        }
        .key{
             font-size:20px;
            color: red;
            font-weight: bold;
        }
.keyd{
    margin-top:20px;
}
.timer{
    margin-top:40px;
    color:#172b4d;
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
     <div class="back-btn-container">
            <a href="javascript:history.back()" class="back-btn">Back</a>
        </div>
        <div class="keyd">
         <p class="key"> Share ID: <?php echo $snippet_id; ?></p>
        </div>
        <br>
        <div class="code-wrapper" id="code-wrapper">
            <?php echo $code; ?>
        </div>
       
        <button onclick="generateAndCopyLink()">Share Link</button>
        <button class="copy-btn" onclick="copyCode()">Copy Code</button>
        <div class="copy-success-message" id="copy-success-message">Code copied to clipboard!</div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <br>
          
        </form>
        <span class="qr-icon" onclick="showQRPopup()" style="margin-top: 15px;" "cursor: pointer;">Show QR</span>
            
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


    <script>
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
    var apiEndpoint = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(downloadUrl);

    // Update the QR code image source
    document.getElementById('popup-qr-code-img').src = apiEndpoint;
}

// Function to show QR code popup
function showQRPopup() {
    // Get the snippet ID
    var snippetId = "<?php echo $snippet_id; ?>";

    // Generate QR code
    generateQRCode(snippetId);

    // Show QR code popup
    document.getElementById('qr-popup').style.display = 'block';
}

// Function to hide QR code popup
function hideQRPopup() {
    // Hide QR code popup
    document.getElementById('qr-popup').style.display = 'none';
}
// Function to handle keydown event
document.addEventListener("keydown", function(event) {
    // Check if Ctrl key and C key are pressed simultaneously
    if (event.ctrlKey && event.key === 'c') {
        // Call the copyCode() function
        copyCode();
    }
});

  function copyCode() {
    // Get the code content from the code wrapper div as text
    var codeContent = document.getElementById('code-wrapper').textContent;

    // Create a temporary textarea element
    var tempTextarea = document.createElement('textarea');
    tempTextarea.value = codeContent;

    // Append the textarea to the body
    document.body.appendChild(tempTextarea);

    // Select the text inside the textarea
    tempTextarea.select();

    // Execute the copy command
    document.execCommand('copy');

    // Remove the temporary textarea
    document.body.removeChild(tempTextarea);

    var copySuccessMessage = document.getElementById('copy-success-message');
        copySuccessMessage.style.opacity = 1;

        // Hide the copy success message after 2 seconds
        setTimeout(function() {
            copySuccessMessage.style.opacity = 0;
        }, 2000);
}

    </script>
</body>
</html>