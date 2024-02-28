<?php
// Start or resume session
session_start();

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

$snippet_id = ""; // Initialize snippet ID variable

// Check if snippet ID is already set in session
if (isset($_SESSION['snippet_id'])) {
    $snippet_id = $_SESSION['snippet_id'];
}

// If the form is submitted to add code snippet
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['share_code'])) {
    // Check if the code textarea is not empty
    if (!empty($_POST['code'])) {
        // Generate 3-digit unique ID
        $snippet_id = sprintf("%03d", mt_rand(1, 999)); // Generate a random 3-digit number
        
        // Set the snippet ID in session
        $_SESSION['snippet_id'] = $snippet_id;

        // Store snippet ID in session history
        if (!isset($_SESSION['snippet_history'])) {
            $_SESSION['snippet_history'] = array();
        }
        array_unshift($_SESSION['snippet_history'], $snippet_id);
        
        // Set the snippet ID as a cookie for the entire website
        setcookie('snippet_id', $snippet_id, time() + (86400 * 30), "/"); // Cookie will expire in 30 days
        
        // Insert code snippet into the database using prepared statement
        $code = $_POST['code'];
        $sql = "INSERT INTO snippets (code, snippet_id) VALUES (?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $code, $snippet_id);

        // Execute statement
        if ($stmt->execute()) {
            // Redirect to get_code.php with the generated snippet ID
            header("Location: get_code.php?snippet_id=$snippet_id");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $stmt->error; // Display error message if insertion fails
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Code textarea is empty."; // Display error message if code textarea is empty
    }
}

// Set the snippet ID in session
$_SESSION['snippet_id'] = $snippet_id;

// Set the snippet ID as a cookie for the entire website with a higher priority
setcookie('snippet_id', $snippet_id, [
    'expires' => time() + (86400 * 30), // Cookie will expire in 30 days
    'path' => '/', // Path accessible to the entire website
    'secure' => true, // Sends the cookie only over HTTPS
    'httponly' => true, // Makes the cookie accessible only through HTTP(S) requests, not JavaScript
    'samesite' => 'Strict' // Ensures the cookie is sent only to the same site in cross-site requests
]);

// Insert code snippet into the database using prepared statement

// Set the session snippet ID
$_SESSION['snippet_id'] = $snippet_id;
?>

<!DOCTYPE html>
<html>
<head>
    <title>PATHAU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
     <link rel="icon" href="tgg.jpg" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Canva+Sans&display=swap" rel="stylesheet">
    <link href="https://icons.veryicon.com/png/o/miscellaneous/two-color-webpage-small-icon/switch-64.png" rel="stylesheet" sizes="80x80" >
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
            box-shadow: 0 4px 8px #172b4d; /* Subtle box shadow */
        }
        h2{
            color: #172b4d; /* White text color */
        }

        .snippet-id {
            font-weight: bold;
            display: inline-block;
            background-color: #fff; ; /* Dark background for snippet ID */
            color: #172b4d; /* White text color */

            padding: 5px;
            border-radius: 3px;
            margin-bottom: 10px;
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

       

        .copy-btn {
            font-weight: bold;
            padding: 5px 10px;
            background-color: #fff; /* Light grey background */
            color: #172b4d; /* White text color */

            border: none;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s; /* Smooth transition for background color */
            margin-left: 10px;
            font-size: 16px;
            outline: none; /* Remove outline */
        }


        .copy-btn:hover {
           background-color: #fff; /* Light grey background */
            color: #172b4d; /* Darker color on hover */
        }

        textarea {
    font-family: 'Courier New', Courier, monospace; /* Code font */
    display: inline-block;
    padding: 80px 40px;
    background-color: #172b4d; 
    color: #fff; /* White text color */
    font-size: 16px;
    resize: none;
    overflow: hidden;
    border: none;
    outline: none;
    border-radius: 14px;
    margin-right: 10px;
    width: 60%;
    cursor: pointer;
    margin-top: 20px;
    margin-bottom: -13px;
}

textarea::placeholder {
    font-family: 'Courier New', Courier, monospace; /* Code font */
    color: #fff; /* White text color */
}



        textarea:focus {
            overflow: hidden;
            border: none;
            border-color: none; 
            outline: none;/* ark border color on focus */
            
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
            padding-bottom: 15px;
            
           background-color:transparent; 
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

        .search-container input[type="number"],
        .search-container button[type="submit"] {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: border-color 0.3s, background-color 0.3s, color 0.3s; /* Smooth transition for border, background, and text color */
        }

        .search-container input[type="number"]:focus,
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
        footer{
            background-color: #fff; ;
            text-align: center;
            color: #172b4d; /* White text color */

            
            
            text-align: center;
            

        }
         img{
            height: 30px;
            width: 30px;
            border: 1px;
            border-radius: 100px;
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

#fileLink img {
    transition: transform 0.5s ease;
}

#fileLink img:hover {
    transform: rotateY(180deg);
}
p{
            color: #172b4d;
            
             /* White text color */

        }
         .slog{
            color: #fff; /* White text color */

            text-align: center;
            margin-top: 10px;
            display: flex;




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
            color: #172b4d; /* White text color */

        }
            #ip-address {
            position: fixed;
            font-family: 'Courier New', Courier, monospace;
            top: 15px;
            right: 10px;
            background-color: transparent;
            color: #fff;
            padding: 5px 5px;
            border-radius: 5px;
            font-size: 9px;
            z-index: 9999;
        }
.copy-link-btn{
    color:black;
}
.all{
    margin-top: 40px;
}
label.file-input-label{
     margin-bottom: 20px;
     text-align: center;
     padding-top: 30px ;
    font-size: 12px;
     width: 150px;
     
     display:flex;
     padding-bottom: 10px;
     margin-left: 5px;
     color:  #172b4d;
}
.


button.choose-text-button {
    width: 140px;
    
    display: block; /* Make the button a block element */
    /* Other styles remain the same */
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
        <div style="position: absolute; left: -999999px;">
        <p>#Pathau 
        #sharecodeonline
#sharecodefree
#privatecodeshare
#1codeshare
#1fileshare
#1codeonline
#codecollaboration
#coderepository
#codingcommunity
#codercommunity
#developercommunity
#codingsolutions
#codingtips
#codingtutorials
#codingexamples
#codinglessons
#codinghelp
#codinginspiration
#codingmotivation
#programminghelp
#programmingtutorials
#programmingexamples
#programminglessons
#programmingmotivation
#programmerslife
#programmerscommunity
#developerlife
#developerslife
#codingisfun
#programmingisfun
#learntocode
#codingjourney
#programmingjourney
#codersofinstagram
#developerdiaries
#codingadventures
#programmingadventures
#codingskills
#programmingskills
#codingexperience
#programmingexperience
#codingchallenge
#programmingchallenge
#codelearning
#codingeducation
#programmingeducation
#codinglife
#programminglife
#coderlife
#developerlife
#codingmindset
#programmingmindset
#codinggoals
#programminggoals
#codergoals
#developergoals
#codingjourney
#programmingjourney
#coderjourney
#developerjourney
#codingsuccess
#programmingsuccess
#developersuccess
#codingchallengesolved
#programmingchallengesolved
#codercommunity
#developercommunity
#codingcommunitylove
#programmingcommunitylove
#codersunite
#developersunite
#codingtogether
#programmingtogether
#coderstogether
#developerstogether
#codingteam
#programmingteam
#coderteam
#developerteam
#codingnetwork
#programmingnetwork
#codernetwork
#developernetwork
#codingfriends
#programmingfriends
#coderfriends
#developerfriends
#codingbuddies
#programmingbuddies
#coderbuddies
#developerbuddies
#codebuddy
#codingsquad
#programmingsquad
#codersquad
#developersquad
#codingpartners
#programmingpartners
#coderpartners
#developerpartners
#codeexchange
#codebarter
#codeforcode
#codetrade
#codebartering
#codingexchange
#programmingexchange
#coderexchange
#developerexchange
#codeswap
#codingswap
#programmingswap
#coderswap
#developerswap
#codershare
#developershare
#codersharing
#developersharing
#codesharingcommunity
#filesharing
#codeshareplatform
#codingplatform
#codeupload
#codehosting
#codehost
#codinghosting
#programminghosting
#codecloud
#codingcloud
#programmingcloud
#codestorage
#codingstorage
#programmingsharing
#codingrepository
#programmingrepository
#codestore
#codingstore
#programmingstore
#codevault
#codingvault
#programmingvault
#codearchive
#codingarchive
#programmingarchive
#codebank
#codingbank
#programmingbank
#codecollection
#codingcollection
#programmingcollection
#codetreasure
#codingtreasure
#programmingtreasure
#codegold
#codinggold
#programminggold
#codegems
#codinggems
#programminggems
#codelibrary
#codinglibrary
#programminglibrary
#codehub
#codinghub
#programminghub
#codenest
#codingnest
#programmingnest
#codeden
#codingden
#programmingden
#codezone
#codingzone
#programmingzone
#codetown
#codingtown
#programmingtown
#codecity
#codingcity
#programmingcity
#codecountry
#codingcountry
#programmingcountry
#codeworld
#codingworld
#programmingworld
#codeverse
#codingverse
#programmingverse
#codeplanet
#codingplanet
#programmingplanet
#codegalaxy
#PathauWebsite #PathauFileShare #PathauNepal #PathauCodeShare #ShareCodeFree #ShareCodeOnline #FreeCodeSharing #PathauCommunity #TechSharing #ProgrammingTips #WebDevelopment #FileSharingPlatform #CodingCommunity #PathauUpdates #TechNews #ProgrammingResources #CodeSnippetExchange #PathauFBPage #PathauTelegram #Technology #Coding #Development #OpenSource #Programming #CodingLife #WebDesign #SoftwareDevelopment #DigitalInnovation #ProgrammingLanguages #TechSolutions #CodeSharing #CodeRepository #CodeSamples #CodeExamples #ProgrammingCommunity #WebDev #AppDevelopment #CodeSharingPlatform #CodeCollaboration #TechUpdates #DeveloperCommunity #CodeReview #CodeQuality #SoftwareEngineering #CodeLibrary #DevelopmentTools #CodingResources #TechSupport #ProgrammingHelp #CodeLearning #TechTips #SoftwareSolutions #CodeExperts #CodingChallenge #CodeDebugging #CodeOptimization #CodePatterns #TechForum #ProgrammingForum #CodeDiscussion #TechAdvice #WebDevelopmentTips #ProgrammingKnowledge #CodeExploration #TechnologyTrends #CodeInnovation #DigitalSolutions #CodingJourney #CodeSharingCommunity #DevelopmentCommunity #CodingResources #TechNewsUpdates #CodeSharingNetwork #SoftwareDevelopmentTips #CodeExchange #ProgrammingLearning #WebDevelopmentResources #ProgrammingTools #TechEducation #CodeDiscussionForum #DevelopmentSupport #CodeTutorial #TechDiscussions #ProgrammingHelpDesk #WebDevCommunity #CodingWorkshop #CodeLearningPlatform #TechInsights #SoftwareDevelopmentCommunity #CodeProblemSolving #DevelopmentInsights #CodingSolutions #TechInnovation #ProgrammingTechniques #CodeDevelopment #TechLearning #SoftwareEngineeringCommunity #CodeSharingHub #DigitalDevelopment</p>
    </div>
    <div class="container">
      <button class="history"onclick="toggleHistoryPopup()">History</button>

    <!-- History section popup -->
    <div id="historyPopup" class="history-section">
    <span class="close-button" onclick="toggleHistoryPopup()">X</span>
        <h2>Share History</h2>
        <ul>
            <?php
            // Display snippet history from session
            if(isset($_SESSION['snippet_history'])) {
                foreach($_SESSION['snippet_history'] as $history_snippet_id) {
                    echo "<li>$history_snippet_id</li>";
                }
            }
            ?>
        </ul>
    </div>
    
        <h2>Share Code</h2>
          <a href="file.php" id="fileLink">


          <img src="Files.jpg">
        </a>
         <form id="shareForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      


        <div class="up">
             <textarea id="note" name="code" placeholder="Enter your text" required></textarea>
           <br> <label for="file-input" class="file-input-label">Choose Text</label>
<input type="file" id="file-input" onchange="handleFileInput(event)" style="display: none;">
<button class="choose-text-button" onclick="document.getElementById('file-input').click()"></button>


        </div>
        <button class="share" type="submit" name="share_code" onclick="showQR()">Share Code</button><br>

    </form>
        <button onclick="showSearch()">Get Code</button>
        <button class="clean" onclick="clearHistory()"><img  src="gif.gif" /></button>
    </div>
    

    </div>

    <div class="search-bar" id="searchBar">
        <div class="search-container">
            <button class="close-btn" onclick="hideSearch()">x</button>
            <h2>Enter Unique ID</h2>
            <form action="get_code.php" method="post">
                <input type="number" name="snippet_id" placeholder="Enter Unique ID" required>
                <button type="submit">Get Code</button>
                

            </form>
        </div>
    </div><div id="telegramIcon" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
       <a href="https://t.me/PATHAUEN">
    <img src="https://imgs.search.brave.com/nfAx52GQ2Wy54jXO8JT5cx0YUaCjL7xcsBR3m3JqOXs/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMtMDAuaWNvbmR1/Y2suY29tL2Fzc2V0/cy4wMC90ZWxlZ3Jh/bS1pY29uLTUxMng1/MTItNHN6dGplcjgu/cG5n" alt="Telegram Icon" width="50" height="50">
</a>

    </div>
<footer>© 2024 <a class="prom" href="https://www.facebook.com/profile.php?id=61556481189298&mibextid=opq0tG">Pathau</a> Tech. All rights reserved.</footer>


    <script>
      
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
    
   // Get the textarea element
        var textarea = document.getElementById("note");

        // Add an event listener for the Enter key press event
        textarea.addEventListener("keydown", function(event) {
            // Check if the Enter key is pressed (key code 13)
            if (event.keyCode === 13) {
                // Prevent the default behavior of the Enter key (submitting the form)
                event.preventDefault();

                // Trigger a click event on the "Share Code" button
                document.querySelector(".share").click();
            }
        });
    
       function handleFileInput() {
    const fileInput = document.getElementById('file-input');
    const selectedFile = fileInput.files[0];

    if (selectedFile) {
        // Check if the file has a .txt extension
        if (selectedFile.name.endsWith('.txt')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('note').value = e.target.result;
            };
            reader.readAsText(selectedFile);
        } else {
            // Display an error message or alert if the selected file is not a .txt file
            alert("Please choose a .txt file.");
            fileInput.value = ''; // Clear the file input field
        }
    }
}

    function confirmShare() {
    // Check if textarea is empty
    if (document.querySelector('textarea[name="code"]').value.trim() === "") {
        alert("Please enter code before sharing.");
        return;
    }
    if (confirm("Ready to generate id?")) {
        document.getElementById("shareForm").submit();
    }
}

function showTelegramIcon() {
    document.getElementById('telegramIcon').style.display = 'block'; // Display the icon

    // Hide the icon after 5 seconds
    setTimeout(function() {
        document.getElementById('telegramIcon').style.display = 'none';
    }, 20000); // 5000 milliseconds = 5 seconds
}

// Call the function to show the Telegram icon
showTelegramIcon();

function showIPAddress() {
    fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('ip-address').innerText = ' ' + data.ip;
        })
        .catch(error => {
            console.error('Error fetching IP address:', error);
        });
}

// Call the function when the page loads
showIPAddress();

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

// Function to toggle history section popup
        function toggleHistoryPopup() {
            var historyPopup = document.getElementById('historyPopup');
            historyPopup.classList.toggle('visible'); // Toggle visibility
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
    </script>
</body>
</html>