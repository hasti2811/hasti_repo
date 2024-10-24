<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header('Location: web_signuplogin.php');
    exit();
}

$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Get the logged-in user's ID
$username = $_SESSION["user"];
$query = mysqli_query($con, "SELECT contactus_id FROM web_signuplogin WHERE fname='$username'");
$user = mysqli_fetch_assoc($query);
$user_id = $user['contactus_id'];

// Get the product ID and quantity from the form submission
$product_id = $_POST['product_id'];
$quantity_to_remove = (int)$_POST['quantity'];

// Check the current quantity of the product in the basket
$check = mysqli_query($con, "SELECT quantity FROM basket WHERE contactus_id = $user_id AND p_id = $product_id");
$row = mysqli_fetch_assoc($check);
$current_quantity = $row['quantity'];

// If the quantity to remove is less than the current quantity, update the quantity
if ($current_quantity > $quantity_to_remove) {
    mysqli_query($con, "UPDATE basket SET quantity = quantity - $quantity_to_remove WHERE contactus_id = $user_id AND p_id = $product_id");
} elseif ($current_quantity == $quantity_to_remove) {
    // If the quantity to remove is equal to the current quantity, remove the item from the basket
    mysqli_query($con, "DELETE FROM basket WHERE contactus_id = $user_id AND p_id = $product_id");
}

// Redirect to the basket page
header('Location: web_basket.php');
exit();
?>
