<?php
// Start or resume session
session_start();

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
    <title>PATHAU- Code Share</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8549584572005502"
     crossorigin="anonymous"></script>
     <meta name="google-adsense-account" content="ca-pub-8549584572005502">
<meta name="description" content="Discover seamless and secure code and file sharing at Pathau.com. Explore our platform developed by visionary Sir Prasanga for hassle-free transmission of files. Join our vibrant community today!">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
     <link rel="icon" href="tgg.jpg" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Canva+Sans&display=swap" rel="stylesheet">
    <link href="https://icons.veryicon.com/png/o/miscellaneous/two-color-webpage-small-icon/switch-64.png" rel="stylesheet" sizes="80x80" >
        <link  rel="stylesheet" href="./css/index.css" >

</head>
<body>
    <div class="header">
              <a class="tit"href="https://www.pathau.com/"><h1>PATHAU</h1></a>

        <div class="slo">
        <img class="web" src="https://www.freeiconspng.com/thumbs/secure-icon-png/lock-icon--20.png">
        <p class="slog">Now, share program and files privately.</p>    
        </div>
          <div class="hidden-logo">
    <img src="tgg.jpg" alt="Your Logo Alt Text">
</div>

    </div>
        <div style="position: absolute; left: -9999999px;">
        <p>
Welcome to Pathau.com, your premier destination for seamless and secure file transfer! Developed by the visionary Sir Prasanga, our platform delivers a hassle-free experience for transmitting files of any size or format.<br>
#pathau.com
#www.pathau.com
#Pathau 
#sharecodeonline
#codeshareonlineplatform
#freecodesharecommunity
#pathauonlinecodesharing
#1codeshareusaforum
#1shareglobalnetwork
#codeshareplatformcommunity
#freecodesharehub
#pathauonlinecodesharingplatform
#1codeshareusacompany
#1shareglobalnetworking
#codeshareonlineservice
#freecodeshareforum
#pathauonlinecodesharecommunity
#1codeshareusaforum
#1shareglobalnetworkhub
#codesharingonlinecommunity
#freecodesharenetwork
#pathauonlinecodesharehub
#1codeshareusaplatform
#1shareglobalnetworkcommunity
#codeshareonlineforum
#freecodeshareplatform
#pathauonlinecodesharingforum
#1codeshareusahub
#1shareglobalnetworkplatform
#codesharonlineservice
#freecodesharediscussion
#pathauonlinecodeshareplatform
#1codeshareusacommittee
#1shareglobalnetworkforum
#codeshareonlinecommunity
#freecodeshareboard
#pathauonlinecodesharingforum
#1codeshareusaassociation
#1shareglobalnetworkcommittee
#codeshareonlinesolution
#freecodeshareexchange
#pathauonlinecodesharingcommunity
#1codeshareusaboard
#1shareglobalnetworkassociation
#codeshareonlinenetwork
#freecodesharecommittee
#pathauonlinecodeshareexchange
#1codeshareusacommission
#1shareglobalnetworkexchange
#codeshareonlineexchange
#freecodeshareassociation
#pathauonlinecodesharecommittee
#1codeshareusacommittee
#1shareglobalnetworkcommission
#codeshareonlinetechnology
#freecodesharetechnology
#pathauonlinecodeshareassociation
#1codeshareusasociety
#1shareglobalnetworksolution
#codeshareonlineplatformsolution
#freecodeshareplatformsolution
#pathauonlinecodesharingtechnology
#1codeshareusainstitute
#1shareglobalnetworktechnology
#codeshareonlineserviceexchange
#freecodeshareexchangehub
#pathauonlinecodeshareplatformsolution
#1codeshareusacommunity
#1shareglobalnetworkingassociation
#codeshareonlinesolutionforum
#freecodesharetechnologyforum
#pathauonlinecodesharingtechnologyhub
#1codeshareusatechnology
#1shareglobalnetworksolutionhub
#codeshareonlinetechnologysolution
#freecodeshareplatformexchange
#pathauonlinecodesharecommunityexchange
#1codeshareusainstitutesolution
#1shareglobalnetworktechnologyforum
#codeshareonlineservicehub
#freecodeshareexchangeplatform
#pathauonlinecodesharingtechnologysolution
#1codeshareusacommunityboard
#1shareglobalnetworkingplatform
#codeshareonlinetechnologyforum
#freecodeshareplatformexchange
#pathauonlinecodesharecommunityexchangehub
#1codeshareusatechnologysolution
#1shareglobalnetworksolutionexchange
#codeshareonlineserviceplatform
#freecodeshareexchangesolution
#pathauonlinecodesharingtechnologysolutionforum
#1codeshareusainstitutehub
#1shareglobalnetworktechnologyexchange
#codeshareonlinetechnologyservice
#freecodeshareplatformexchangehub
#pathauonlinecodesharecommunityexchangesolution
#1codeshareusacommunityhub
#1shareglobalnetworkingplatformforum
#codeshareonlinetechnologyserviceforum
#freecodeshareexchangesolutionhub
#pathauonlinecodesharecommunityexchangesolutionforum
#1codeshareusatechnologysolutionhub
#1shareglobalnetworksolutionexchangeplatform
#codeshareonlinetechnologyservicesolution
#freecodeshareplatformexchangehub
#pathauonlinecodesharecommunityexchangesolutionsolution
#1codeshareusacommunityhub
#1shareglobalnetworkingplatformforum
#codeshareonlinetechnologyserviceforum
#freecodeshareexchangesolutionhub
#pathauonlinecodesharecommunityexchangesolutionforum
#1codeshareusatechnologysolutionhub
#1shareglobalnetworksolutionexchangeplatform
#codeshareonlinetechnologyservicesolution
#freecodeshareplatformexchangehub
#pathauonlinecodesharecommunityexchangesolutionsolution
#1codeshareusacommunityhub
#1shareglobalnetworkingplatformforum
#codeshareonlinetechnologyserviceforum
#freecodeshareexchangesolutionhub
#pathauonlinecodesharecommunityexchangesolutionforum
#1codeshareusatechnologysolutionhub
#1shareglobalnetworksolutionexchangeplatform
#codeshareonlinetechnologyservicesolution
#freecodeshareplatformexchangehub
#pathauonlinecodesharecommunityexchangesolutionsolution
#1codeshareusacommunityhub
#1shareglobalnetworkingplatformforum
#codeshareonlinetechnologyserviceforum
#freecodeshareexchangesolutionhub
#pathauonlinecodesharecommunityexchangesolutionforum
#1codeshareusatechnologysolutionhub
#1shareglobalnetworksolutionexchangeplatform
#codeshareonlinetechnologyservicesolution
#freecodeshareplatformexchangehub
#pathauonlinecodesharecommunityexchangesolutionsolution
#1codeshareusacommunityhub
#1shareglobalnetworkingplatformforum
#codeshareonlinetechnologyserviceforum
#freecodeshareexchangesolutionhub
#pathauonlinecodesharecommunity
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
#NepalDigital
#NepalCodeshare
#NepalPathau
#1NepalDigital
#1NepalCodeshare
#1NepalPathau
#DigitalInNepal
#CodeshareInNepal
#PathauInNepal
#NepaliTechCommunity
#TechInNepal
#NepalTechnology
#NepalProgramming
#NepaliCoders
#NepaliDevelopers
#NepalTechHub
#NepalCodeHub
#NepalTechScene
#NepalDevCommunity
#TechSolutionsNepal
#CodeSharingNepal
#ProgrammingInNepal
#NepalWebDevelopment
#NepalSoftwareDevelopment
#NepaliProgramming
#NepalDigitalInnovation
#NepalOpenSource
#NepalTechUpdates
#NepalProgrammingResources
#NepalCodeReview
#NepalSoftwareEngineering
#NepalCodingResources
#NepalTechSupport
#NepalProgrammingHelp
#NepalCodeLearning
#NepalTechTips
#NepalSoftwareSolutions
#NepalCodeExperts
#NepalCodingChallenge
#NepalTechForum
#NepalProgrammingForum
#NepalCodeDiscussion
#NepalTechAdvice
#NepalProgrammingKnowledge
#NepalCodeInnovation
#NepalDigitalSolutions
#NepalCodingJourney
#NepalDevelopmentCommunity
#NepalCodingResources
#NepalTechNewsUpdates
#NepalCodeSharingNetwork
#NepalSoftwareDevelopmentTips

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
          <a class="swap" href="file.php" id="fileLink">


          <img class="chg" src="Files.jpg">
        </a>
         <form id="shareForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      


        <div class="up">
             <textarea id="note" name="code" placeholder="Enter your code" required></textarea>
                      <!-- <br> <label for="file-input" class="file-input-label">Choose Text</label> -->

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
    </div>
    <div id="resetPopup" class="popup">
  <div class="popup-content">
    <span class="close-button" onclick="hideResetPopup()">&times;</span>
    <p>History successfully reset.</p>
  </div>
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
 /// Get the Share Code button
var shareButton = document.querySelector(".share");

// Add an event listener for the keydown event on the document
document.addEventListener("keydown", function(event) {
    // Get the currently focused element
    var focusedElement = document.activeElement;

    // Check if the Enter key (keyCode 13) is pressed
    if (event.keyCode === 13) {
        // Check if the Ctrl key (keyCode 17) is pressed simultaneously
        if (event.ctrlKey) {
            // Prevent the default behavior of the Enter key
            event.preventDefault();

            // Insert a line break at the current cursor position if the focused element is a textarea
            if (focusedElement.tagName.toLowerCase() === 'textarea') {
                var cursorPosition = focusedElement.selectionStart;
                var currentValue = focusedElement.value;
                var newValue =
                    currentValue.substring(0, cursorPosition) +
                    "\n" +
                    currentValue.substring(cursorPosition, currentValue.length);

                // Update the textarea value with the new value including the line break
                focusedElement.value = newValue;

                // Update the cursor position to be after the inserted line break
                focusedElement.selectionStart = cursorPosition + 1;
                focusedElement.selectionEnd = cursorPosition + 1;
            }
        } else {
            // Prevent the default behavior of the Enter key (submitting the form)
            event.preventDefault();

            // Trigger a click event on the "Share Code" button if the focused element is not a textarea
            if (focusedElement.tagName.toLowerCase() !== 'textarea') {
                shareButton.click();
            }
        }
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