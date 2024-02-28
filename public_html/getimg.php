<?php
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

$snippet_id = ""; // Initialize snippet ID variable
$filename = ""; // Initialize filename variable
$file_data = ""; // Initialize file data variable
$upload_time = ""; // Initialize upload time variable

// If the form is submitted to get file data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['snippet_id'])) {
    $snippet_id = $_POST['snippet_id'];

    // Select file data and upload time from database based on snippet ID
    $sql = "SELECT filename, file_data, upload_time FROM files WHERE snippet_id = ?";
    
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

?>


<!DOCTYPE html>
<html>
<head>
    <title>Pathau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Canva+Sans&display=swap" rel="stylesheet">
     <link rel="icon" href="tgg.jpg" type="image/x-icon">
    <link href="https://icons.veryicon.com/png/o/miscellaneous/two-color-webpage-small-icon/switch-64.png" rel="stylesheet" sizes="80x80" >
    <link rel="stylesheet" type="text/css" href="style.css">

    <style>
                  *{
            margin: 0;
            padding: 0;
            display: fixed;
            
        }
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            background-color: #fff; ; /* Light grey background */
            color: #fff; /* Dark text color */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition for background and text color */
        }

        .header {
            background-color: #172b4d; 
            color: #fff; /* White text color */
 /* White text color */
            text-align: center;
            padding: 10px 0;
            font-family: 'Inter', sans-serif;

        }
        footer{
            background-color: #fff; ;
            text-align: center;
            color: #172b4d; /* White text color */

            
            
            text-align: center;
            

        }
    .web{
         width: 30px;
         height: 30px;
         margin-bottom: -4px;
        display: flex;



        }
          .slog{
            color: #fff; /* White text color */

            text-align: center;
            margin-top: 10px;
            display: flex;




        }
        .slo{
  display: flex;
  justify-content: center; /* Align content horizontally at the center */ /* Align content horizontally at the center */
  align-items: center; 

        }
        .prom{
            text-decoration: none;
            color: #172b4d; /* White text color */

        }
        p{
            color: #172b4d; /* White text color */

        }
       .container {
            height: 510px;
            width: 80%;
            margin: 20px auto;
            background-color: #fff; /* Light grey background */
 /* Gradient background */
            padding: 20px;
            overflow: hidden;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            position: relative;
            transition: box-shadow 0.3s; /* Smooth transition for box shadow */
            box-shadow: 0 4px 10px grey; /* Subtle box shadow */
        }
        h2{
            color: #172b4d; /* White text color */
        }

        .file-info {
            margin-bottom: 20px;
            color: #172b4d;
                        font-weight: bold;

        }

        .file-info span {
            color: #172b4d; /* Blue color for file info */
            font-weight: bold;
            margin-right: 10px;
        }

        .file-download {
            display: flex;
            background-color: #172b4d; /* Blue color for download button */
            color: #fff; /* White text color */
            padding: 10px 20px;
            
            margin-top: 10px;
            max-width: 140px;
            text-decoration: none;
            border: none;
            border-radius: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            
             /* Smooth transition for background color */
        }

         .back-btn {
            background-color: #fff; /* Button background color */
            color: #172b4d; /* Text color */
            padding: 10px 20px; /* Padding */
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
            margin-bottom: 20px;
        }
    .file-data {
    width: 50%;
    max-height: 20.6px;
    overflow: hidden; /* Change from 'fixed' to 'hidden' */
    border: 1px solid #555;
    border-radius: 10px;
    padding: 10px;
    background-color: #fff;
}
.copy{
    background-color: #fff;
    color:#172b4d;
    max-width: 110px;
     padding: 10px 20px;
    text-decoration: none;
    border: none;
    border-radius: 18px;
    cursor: pointer;
    transition: background-color 0.3s; 
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
          span.she{
            font-size:20px;
            color: red;
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
    <div class="back-btn-container">
            <a href="javascript:history.back()" class="back-btn">Back</a>
        </div>
       
       <br>
        <h2>File Data</h2>
       
        <br>
        <br>
        <div class="file-info">
            <span class="she">Share ID: <?php echo $snippet_id; ?></span>
        </div>
        <br>
        <div class="file-info">
            <span>Filename: <?php echo $filename; ?></span>
        </div>
        <!-- Display download link -->
        <br>
        <a href="download.php?snippet_id=<?php echo $snippet_id; ?>" class="file-download">Download File</a>
        <br>
            

        
    </div>
    
   
    <footer>© 2024 <a class="prom" href="https://t.me/trymeEN">Pathau</a> Tech. All rights reserved.</footer>
    <script>
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