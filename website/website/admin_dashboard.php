<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "admin") {
    header('Location: web_signuplogin.php');
    exit();
}

$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Handle add item form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = mysqli_real_escape_string($con, $_POST['item_name']);
    $item_price = mysqli_real_escape_string($con, $_POST['item_price']);
    $item_desc = mysqli_real_escape_string($con, $_POST['item_desc']);
    $item_quantity = mysqli_real_escape_string($con, $_POST['item_quantity']); // Get item quantity
    
    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate the image
    $check = getimagesize($_FILES["item_image"]["tmp_name"]);
    if ($check === false || $_FILES["item_image"]["size"] > 5000000 || !in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $uploadOk = 0;
        echo "Sorry, your file was not uploaded. Please check the file type and size.";
    }

    // Attempt to move the uploaded file if the validation passes
    if ($uploadOk && move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
        // Insert product details, including quantity
        $insert_query = "INSERT INTO products (p_name, p_price, p_desc, p_pic, quantity) 
                         VALUES ('$item_name', '$item_price', '$item_desc', '$target_file', '$item_quantity')";
        mysqli_query($con, $insert_query);
        echo "Item added successfully.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Handle remove item form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $item_id = mysqli_real_escape_string($con, $_POST['item_id']);
    
    // Fetch image path
    $image_query = mysqli_query($con, "SELECT p_pic FROM products WHERE p_id='$item_id'");
    $image_row = mysqli_fetch_assoc($image_query);
    $image_path = $image_row['p_pic'];

    // Delete product and image
    $delete_query = "DELETE FROM products WHERE p_id='$item_id'";
    mysqli_query($con, $delete_query);
    if (file_exists($image_path)) unlink($image_path);
}

// Fetch current products
$product_query = mysqli_query($con, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="web_basket.css">
</head>
<body>

    <!-- Navigation Bar -->
    <ul>
        <li><a href="web_homepage.php">Home</a></li>
        <li><a href="web_browse.php">Browse</a></li>
        <li><a href="web_contactus.php">Contact us</a></li>
        <li style="float:right">
            <?php if (isset($_SESSION["user"])): ?>
                <div class='user'>User: <?php echo htmlspecialchars($_SESSION["user"]); ?></div>
                <a class='active' href='web_logout.php'>Log out</a>
            <?php else: ?>
                <a class="active" href="web_signuplogin.php">Sign up/ Log in</a>
            <?php endif; ?>
        </li>
    </ul>

    <h1>Admin Dashboard</h1>
    
    <h2>Add New Item</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" required>
        <label for="item_price">Item Price (£):</label>
        <input type="number" name="item_price" step="0.01" required>
        <label for="item_desc">Product Description:</label>
        <textarea name="item_desc" required></textarea>
        <label for="item_quantity">Quantity:</label> <!-- Added quantity input -->
        <input type="number" name="item_quantity" required>
        <label for="item_image">Item Image:</label>
        <input type="file" name="item_image" accept="image/*" required>
        <button type="submit" name="add_item">Add Item</button>
    </form>

    <h2>Current Products</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Quantity</th> <!-- New quantity column -->
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
        while ($product = mysqli_fetch_assoc($product_query)) {
            $price = floatval($product['p_price']);
            echo "<tr>
                    <td>" . htmlspecialchars($product['p_name']) . "</td>
                    <td>£" . number_format($price, 2) . "</td>
                    <td>" . htmlspecialchars($product['p_desc']) . "</td>
                    <td>" . htmlspecialchars($product['quantity']) . "</td> <!-- Display quantity -->
                    <td><img src='" . htmlspecialchars($product['p_pic']) . "' alt='" . htmlspecialchars($product['p_name']) . "' style='width: 100px; height: auto;'></td>
                    <td>
                        <form action='' method='post'>
                            <input type='hidden' name='item_id' value='" . $product['p_id'] . "'>
                            <button type='submit' name='remove_item'>Remove</button>
                        </form>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <a href="web_homepage.php">Back to Homepage</a>
</body>
</html>
