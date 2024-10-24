<html>
<head>
<title>Contact Us</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="web_contactus.css">

</head>
<body>

<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
    
    <?php
    session_start();
    
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

</body>
</html>