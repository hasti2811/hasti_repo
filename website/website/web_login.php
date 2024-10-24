<?php
// Start session
session_start();

// Connect to the database
$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Check for connection error
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query to find the user in the database
    $login_query = mysqli_query($con, "SELECT * FROM web_signuplogin WHERE username='$username'");
    
    if (mysqli_num_rows($login_query) == 1) {
        // Fetch the user's information
        $user = mysqli_fetch_assoc($login_query);

        // Check if the password matches (in case you implement password hashing later)
        if ($user['password'] === $password) {
            // Store user info in the session
            $_SESSION["user"] = $user['username'];
            $_SESSION["contactus_id"] = $user['id']; // Assuming 'id' is the user ID in the DB

            // Check if the user is admin
            if ($username === "admin" && $password === "123") {
                $_SESSION["is_admin"] = true; // Set admin session variable if necessary
            }

            // Redirect to the homepage
            header('Location: web_homepage.php');
            exit();
        } else {
            echo "<p class='error'>Invalid password.</p>";
        }
    } else {
        echo "<p class='error'>Invalid username or password.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="web_signuplogin.css">
</head>
<body>
<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
    <li style="float:right">
        <a class="active" href="web_login.php">Log in</a>
    </li>
</ul>

<h3>Log In</h3>
<div class="container4">
    <form method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Your username.." required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password.." required>
        
        <input type="submit" value="Log In">
    </form>
</div>

<p><a href="web_signuplogin.php">Don't have an account? Create an Account!</a></p>

</body>
</html>
