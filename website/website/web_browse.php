<?php
// Start the session
session_start();

// Connect to the database
$con = mysqli_connect("localhost", "tlevel_hasti", "Password_123", "tlevel_hasti");

// Handle adding products to the basket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_basket'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']); // Get the quantity

    // Initialize basket if it doesn't exist
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }

    // Add or update the quantity of the product in the basket
    if (isset($_SESSION['basket'][$product_id])) {
        $_SESSION['basket'][$product_id] += $quantity; // Update quantity
    } else {
        $_SESSION['basket'][$product_id] = $quantity; // Add new product
    }

    echo "Item added to your basket!";
}

// Query the products from the database
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
if ($search) {
    // Search for products matching the search term
    $result = mysqli_query($con, "SELECT * FROM products WHERE p_name LIKE '%$search%'");
} else {
    // Fetch all products if no search query is present
    $result = mysqli_query($con, "SELECT * FROM products");
}

?>

<html>
<head>
<title>Browse Products</title>
<link rel="stylesheet" type="text/css" href="web_browse.css">
</head>
<body>

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

<div id="vertical-nav">
  <ul>
    <li><a href="web_phones.php">Phones</a></li>
    <li><a href="web_tablets.php">Tablets</a></li>
    <li><a href="web_audio.php">Audio</a></li>
    <li><a href="web_laptops.php">Laptops</a></li>
    <li><a href="web_tvs.php">TVs</a></li>
  </ul>
</div>  

<div class="container2">
    <form action="" method="get">
        <div class="searchform">
            <input style="search" name="search" required></input>
        </div>
        <div class="buttons">
            <button>Search for products</button>
        </div>
    </form>
  
<?php if ($search): ?>
<h1>Search result on products: <?php echo htmlspecialchars($search); ?></h1>
<?php else: ?>
<h1>Browse All Products</h1>
<?php endif; ?>

<br>

<!-- Display Products -->
<table id="products">
  <tr>
    <th>Name</th>
    <th>Description</th>
    <th>Image</th>
    <th>Price</th>
    <th>Available Stock</th>
    <th>Quantity</th>
    <th>Action</th>
  </tr>
  <?php while($row = mysqli_fetch_array($result)): ?>
  <tr>
    <td><?php echo htmlspecialchars($row['p_name']); ?></td>
    <td><?php echo htmlspecialchars($row['p_desc']); ?></td>
    <td><img src="<?php echo htmlspecialchars($row['p_pic']); ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" height="100" width="100"></td>
    <td>£<?php echo number_format(floatval($row['p_price']), 2); ?></td>
    <td><?php echo htmlspecialchars($row['quantity']); ?> in stock</td> <!-- Display available stock -->
    <td>
      <form action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['p_id']; ?>">
        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($row['quantity']); ?>" style="width: 50px;"> <!-- Max set to available stock -->
    </td>
    <td>
        <button type="submit" name="add_to_basket">Add to Basket</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>


</div>

</body>
</html>
