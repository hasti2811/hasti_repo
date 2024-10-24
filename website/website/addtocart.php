<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header('Location: web_signuplogin.php');
    exit();
}

// Database connection with error handling
$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the logged-in user's ID (assuming user ID is stored in session after login)
$username = mysqli_real_escape_string($con, $_SESSION["user"]);
$query = mysqli_query($con, "SELECT contactus_id FROM web_signuplogin WHERE fname='$username'");
if (!$query) {
    die("Error fetching user data: " . mysqli_error($con));
}
$user = mysqli_fetch_assoc($query);
$user_id = intval($user['contactus_id']);

// Check if product_id is set
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Ensure it's an integer

    // Check if the product is already in the basket
    $check = mysqli_query($con, "SELECT * FROM basket WHERE contactus_id = $user_id AND p_id = $product_id");
    if (!$check) {
        die("Error checking basket: " . mysqli_error($con));
    }

    if (mysqli_num_rows($check) > 0) {
        // If product is already in the basket, update the quantity
        $update = mysqli_query($con, "UPDATE basket SET quantity = quantity + 1 WHERE contactus_id = $user_id AND p_id = $product_id");
        if (!$update) {
            die("Error updating basket: " . mysqli_error($con));
        }
    } else {
        // Insert the product into the basket
        $insert = mysqli_query($con, "INSERT INTO basket (contactus_id, p_id, quantity) VALUES ($user_id, $product_id, 1)");
        if (!$insert) {
            die("Error inserting into basket: " . mysqli_error($con));
        }
    }

    // Redirect to the basket page after processing
    header('Location: web_basket.php');
    exit();

} else {
    die("Product ID not set.");
}
?>
