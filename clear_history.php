<?php
// Start PHP session
session_start();

// Clear the snippet history session variable
$_SESSION['snippet_history'] = array();

// Respond with a success message
http_response_code(200);
?>
