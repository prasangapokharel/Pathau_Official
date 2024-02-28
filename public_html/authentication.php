<?php
session_start();

// Check if the user is already logged in, if yes, redirect them to jaya.php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: jaya.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$adminid = $password = "";
$adminid_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if admin ID is empty
    if(empty(trim($_POST["adminid"]))){
        $adminid_err = "Please enter admin ID.";
    } else{
        $adminid = trim($_POST["adminid"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($adminid_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, adminid, password FROM admin WHERE adminid = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_adminid);

            // Set parameters
            $param_adminid = $adminid;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if admin ID exists, if yes then verify password
                if($stmt->num_rows == 1){
                    // Bind result variables
                    $stmt->bind_result($id, $adminid, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["adminid"] = $adminid;

                            // Redirect user to jaya.php page
                            header("location: jaya.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if admin ID doesn't exist
                    $adminid_err = "No account found with that admin ID.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div <?php echo (!empty($adminid_err)) ? 'has-error' : ''; ?>">
                <label>Admin ID</label>
                <input type="text" name="adminid" class="form-control" value="<?php echo $adminid; ?>">
                <span><?php echo $adminid_err; ?></span>
            </div>    
            <div <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>    
</body>
</html>
