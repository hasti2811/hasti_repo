<?php
// Start session
session_start();

// Connect to the database
$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Check for connection error
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle sign up form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form validation for sign up
    $errors = [];
    $fname = $_POST['firstname'] ?? '';
    if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
        $errors[] = "First name must contain only letters.";
    }
    
    $lname = $_POST['lastname'] ?? '';
    if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
        $errors[] = "Last name must contain only letters.";
    }

    // Validate email
    $email = $_POST['email'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate phone number
    $phonenumber = $_POST['phonenumber'] ?? '';
    if (!preg_match("/^[0-9]{10,15}$/", $phonenumber)) {
        $errors[] = "Phone number must be between 10 and 15 digits.";
    }

    // Validate username
    $username = $_POST['username'] ?? '';
    if (strlen($username) < 10) {
        $errors[] = "Username must be at least 10 characters long.";
    }

    // Validate password
    $password = $_POST['password'] ?? '';
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // If no errors, proceed with database storage
    if (empty($errors)) {
        // Insert new user into database
        $insert_query = "INSERT INTO web_signuplogin (fname, lname, email, phonenumber, username, password) 
                         VALUES ('$fname', '$lname', '$email', '$phonenumber', '$username', '$password')";
        if (mysqli_query($con, $insert_query)) {
            // Get the last inserted user ID
            $user_id = mysqli_insert_id($con);

            // Store user details in session
            $_SESSION['contactus_id'] = $user_id;
            $_SESSION['user'] = $username;

            // Redirect to a welcome page or homepage
            header('Location: web_homepage.php');
            exit();
        } else {
            echo "<p class='error'>Sign up failed: " . mysqli_error($con) . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="web_signuplogin.css">
</head>
<body>
<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
    <li style="float:right">
        <a class="active" href="web_signuplogin.php">Sign up</a>
    </li>
</ul>

<h3>Sign up</h3>
<div class="container4">
    <form method="post" onsubmit="return checkPass()">
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="firstname" placeholder="Your name.." required>

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Your email address.." required>
        
        <label for="phonenumber">Phone Number</label>
        <input type="text" id="phonenumber" name="phonenumber" placeholder="Your phone number.." required>
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Your username.." required> 
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password.." required>
        
		<label for="confpassword">Confirm Password</label>
        <input type="password" id="confpassword" name="confpassword" placeholder="Confirm password.." required>
		
        <!--<input type="submit" value="Sign Up">-->
		<button onclick="checkPass()">Submit</button>
    </form>
</div>
<p><a href="web_login.php">Already have an account? Log in!</a></p>
<p id= "output"></p>
   <script>
      function checkPass() {
	     var pass=document.getElementById("password").value;
		 var confpass=document.getElementById("confpassword").value;
		 
		 if(pass==confpass){
		 document.getElementById("output").innerHTML = ""
	     }
    else{
	document.getElementById("output").innerHTML = "Error - password does not match. Try again.";
	return false
	   }
         if(pass.length >7){
		 document.getElementById("output").innerHTML = ""	 
		 }
	else{
	document.getElementById("output").innerHTML = "Error - password is less than 8 characters long. Try again.";
    return false		
	   }
	     if(/[A-Z]/.test(pass) && /[a-z]/.test(pass)){
		 document.getElementById("output").innerHTML = ""	 
		 }
	else{
	document.getElementById("output").innerHTML = "Error - password must contain at least one uppercase and one lowercase character. Try again.";
    return false		
	   }
 	     if(/[0-9]/.test(pass)){
		 document.getElementById("output").innerHTML = ""	  
		 }   
	else{
	document.getElementById("output").innerHTML = "Error - password must contain at least 1 number. Try again.";
    return false		
	   }
	}
    	
	
   </script>
</body>
</html>


