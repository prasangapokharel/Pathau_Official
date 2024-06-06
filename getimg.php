<?php
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

$snippet_id = ""; // Initialize snippet ID variable
$filename = ""; // Initialize filename variable
$file_data = ""; // Initialize file data variable
$upload_time = ""; // Initialize upload time variable

// If the form is submitted to get file data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['snippet_id'])) {
    $snippet_id = $_POST['snippet_id'];

    // Select file data and upload time from database based on snippet ID
    $sql = "SELECT filename, file_data, upload_time FROM code WHERE snippet_id = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $snippet_id);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($filename, $file_data, $upload_time);

    // Fetch result
    $stmt->fetch();

    // Close statement
    $stmt->close();

    // Calculate time left for deletion
    $upload_time = new DateTime($upload_time);
    $current_time = new DateTime();
    $diff = $current_time->diff($upload_time);
    $hours_left = 24 - $diff->h;
    $minutes_left = 60 - $diff->i;
    $seconds_left = 60 - $diff->s;
}

// If snippet ID and filename are not provided via form submission, try to get them from URL parameters
if (empty($snippet_id) && isset($_GET['snippet_id'])) {
    $snippet_id = $_GET['snippet_id'];
}
if (empty($filename) && isset($_GET['filename'])) {
    $filename = $_GET['filename'];
}

// Send email if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recipient_email'])) {
    $recipient_email = $_POST['recipient_email'];
    $download_link = "https://pathau.com/download.php?snippet_id=" . $snippet_id;
    $subject = "Your Download Link from Pathau";
$message = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Download Link from Pathau</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #0044cc;
            text-align: center;
        }
        p {
            margin-bottom: 20px;
        }
        .download-btn {
            display: inline-block;
            background-color: #0044cc;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        .footer p {
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Download Link from Pathau</h1>
        <p>Hello,</p>
        <p>Your download link is:</p>
        <p><a class='download-btn' href='https://www.pathau.com/getimg.php?snippet_id=$snippet_id&filename=$filename'>Download Now</a></p>
        <p>Best regards,<br>Pathau Tech</p>
    </div>
    <div class='footer'>
        <p>This email was sent from Pathau. If you have any questions, please contact us.</p>
    </div>
</body>
</html>
";


    
    // Set additional headers
    $headers = "From: file@pathau.com\r\n";
    $headers .= "Reply-To: file@pathau.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // Send email
    if (mail($recipient_email, $subject, $message, $headers)) {
        $email_status = "Email sent successfully!";
    } else {
        $email_status = "Failed to send email. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pathau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <style>
        body {
            font-family: 'Canva Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #172b4d; 
            color:  #172b4d;
        }

        .header {
            background-color: #172b4d; 
            color: #fff; /* White text color */
 /* White text color */
            text-align: center;
            padding: 10px 0;
            font-family: 'Inter', sans-serif;

        }
       
 .she{
    font-size: 22px;
    font-weight: bold;
}
        .container {
            max-width: 1100px;
            margin: 2em auto;
            padding: 1em;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .back-btn-container {
            text-align: right;
        }

        .back-btn {
            color:  #172b4d;
            text-decoration: none;
            font-weight: bold;
        }

        h2, h3 {
            color:  #172b4d;
        }

        .file-info {
            margin: 3em 0;
        }

        .file-download {
                        width: 20%;

            display: inline-block;
            padding: 0.5em 1em;
            background-color: #172b4d; 
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin: 0.5em %;
        }
        a.file-download {
            text-align: center;
            float: center;
        }

        form {
            margin-top: 1em;
        }

        form label {
            display: block;
            margin-bottom: 0.5em;
        }

        form input[type="email"] {
            width: 98%;
            padding: 9px;
            margin-bottom: 0.5em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button,.copy {
            padding: 0.5em 1em;
            width: 100%;
            background-color: #172b4d; 
            color: white;
            border: none;
            margin: 10px auto;
            border-radius: 12px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #003399;
        }

        footer {
            text-align: center;
            padding: 1em 0;
            background-color: #172b4d; 
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer .prom {
            color: white;
            text-decoration: none;
        }

        #feedback {
            color: green;
        }
        .fln{
          text-align: center; 
        }
        span.fln{
                      text-align: center; 

        }
    </style>
</head>
<body>

 <div class="header">
        <h1>PATHAU</h1>
      
    </div>

<div class="container">
    <div class="back-btn-container">
        <a href="javascript:history.back()" class="back-btn">Back</a>
    </div>
    
    <div class="file-info">
        <span class="she">Share ID: <?php echo $snippet_id; ?></span>
    </div>
    <div class="file-info">
        <span class="fln">Filename: <?php echo $filename; ?></span>
    </div>
    <a href="download.php?snippet_id=<?php echo $snippet_id; ?>" class="file-download">Download</a>

    <form method="post" action="">
     
        <input type="email" id="recipient_email" name="recipient_email"  placeholder="Enter recipient email"required>
        <button type="submit">Send</button>
    </form>
    <?php if(isset($email_status)) echo "<p>$email_status</p>"; ?>

    <button id="copyButton" class="copy">Copy Link</button>
    <p id="feedback" style="display:none;">Link copied to clipboard!</p>
</div>

<footer>© 2024 <a class="prom" href="https://t.me/trymeEN">Pathau</a> Tech. All rights reserved.</footer>
<script>
    

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

    document.getElementById('copyButton').addEventListener('click', function() {
        // Get the current URL
        var currentUrl = window.location.href;

        // Create a temporary input element
        var tempInput = document.createElement('input');
        tempInput.value = currentUrl;

        // Append the input element to the body
        document.body.appendChild(tempInput);

        // Select the input element's content
        tempInput.select();

        // Copy the selected content
        document.execCommand('copy');

        // Remove the temporary input
        document.body.removeChild(tempInput);

        // Show feedback message
        var feedbackElement = document.getElementById('feedback');
        feedbackElement.style.display = 'block';

        // Hide feedback message after 2 seconds
        setTimeout(function() {
            feedbackElement.style.display = 'none';
        }, 2000);
    });
</script>
</body>
</html>
