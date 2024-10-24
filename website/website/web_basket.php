<?php
session_start();

// Connect to the database
$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header('Location: web_signuplogin.php');
    exit();
}

// Handle placing an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $basket = $_SESSION['basket']; // Assuming basket is stored in session

    // Iterate over items in the basket
    foreach ($basket as $p_id => $quantity) {
        // Insert order into orders table
        $insert_order = "INSERT INTO orders (user_id, p_id, quantity) VALUES ('$user_id', '$p_id', '$quantity')";
        mysqli_query($con, $insert_order);
        
        // Decrease the product quantity
        $update_quantity = "UPDATE products SET quantity = quantity - $quantity WHERE p_id = '$p_id'";
        mysqli_query($con, $update_quantity);
    }

    // Clear the basket
    unset($_SESSION['basket']);
    echo "Order placed successfully!";
}

// Fetch user's basket items
$basket_items = $_SESSION['basket'] ?? [];

// Handle removal of items from basket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_basket'])) {
    $p_id = $_POST['p_id'];
    $remove_quantity = $_POST['remove_quantity'];

    // Update the basket session
    if (isset($basket_items[$p_id])) {
        $basket_items[$p_id] -= $remove_quantity;

        // Remove item if quantity reaches zero
        if ($basket_items[$p_id] <= 0) {
            unset($basket_items[$p_id]);
        }
    }

    $_SESSION['basket'] = $basket_items; // Update session basket
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Basket</title>
    <link rel="stylesheet" type="text/css" href="web_basket.css">
    <style>
        /* Table styling for better alignment */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            text-align: left; /* Align text to the left */
            padding: 10px; /* Add some padding */
            border: 1px solid #ddd; /* Add border */
        }
        th {
            background-color: #f2f2f2; /* Light grey background for headers */
        }
        /* Style for total cost */
        h2 {
            text-align: right; /* Align total cost to the right */
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
    
    <?php
    // Display the Basket link only for regular users
    if (isset($_SESSION["user"]) && $_SESSION["user"] !== "admin") {
        echo '<li><a href="web_basket.php">Basket</a></li>';
    }
    ?>
    
    <li style="float:right">
        <?php
        if (isset($_SESSION["user"])) {
            echo "<div class='user'>User: " . htmlspecialchars($_SESSION["user"]) . "</div>";
            echo "<a class='active' href='web_logout.php'>Log out</a>";
            
            // Check if the logged-in user is the admin
            if ($_SESSION["user"] === "admin") {
                echo '<li><a href="admin_dashboard.php">Admin Dashboard</a></li>';
            }
        } else {
            echo '<a class="active" href="web_signuplogin.php">Sign up/ Log in</a>';
        }
        ?>
    </li>
</ul>

<!-- Basket Content -->
<h1>Your Basket</h1>

<?php if (empty($basket_items)): ?>
    <p>Your basket is empty.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Remove Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
        <?php
        $total_cost = 0; // Initialize total cost

        foreach ($basket_items as $p_id => $quantity) {
            // Fetch product details, including price
            $product_query = mysqli_query($con, "SELECT p_name, p_price FROM products WHERE p_id='$p_id'");
            $product = mysqli_fetch_assoc($product_query);

            // Calculate total price for the current product
            $total_price = $product['p_price'] * $quantity;
            $total_cost += $total_price; // Update total cost

            echo "<tr>
                    <td>" . htmlspecialchars($product['p_name']) . "</td>
                    <td>" . htmlspecialchars($quantity) . "</td>
                    <td>
                        <form action='' method='post'>
                            <input type='hidden' name='p_id' value='$p_id'>
                            <input type='number' name='remove_quantity' value='1' min='1' max='$quantity'>
                            <button type='submit' name='remove_from_basket'>Remove</button>
                        </form>
                    </td>
                    <td>£" . htmlspecialchars($product['p_price']) . "</td>
                    <td>£" . number_format($total_price, 2) . "</td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Total Cost: £<?php echo number_format($total_cost, 2); ?></h2>
<?php endif; ?>

<a href="web_homepage.php">Back to Homepage</a>

</body>
</html>
