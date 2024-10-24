<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasti's Shop (UK)</title>
    <link rel="stylesheet" type="text/css" href="web_homepage.css">
</head>
<body>

<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
    
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        // Show Basket link only for regular users
        if ($_SESSION["user"] !== "admin") {
            echo '<li><a href="web_basket.php">Basket</a></li>';
        }
        // Show user info and logout link
        echo "<li style='float:right'><div class='user'>User: " . htmlspecialchars($_SESSION["user"]) . "</div>";
        echo "<a class='active' href='web_logout.php'>Log out</a>";
        
        // Show Admin Dashboard link only for admin
        if ($_SESSION["user"] === "admin") {
            echo '<li><a href="admin_dashboard.php">Admin Dashboard</a></li>';
        }
        echo '</li>';
    } else {
        // For guests
        echo '<li style="float:right"><a class="active" href="web_signuplogin.php">Sign up/ Log in</a></li>';
    }
    ?>
</ul>

<div class="FI">
    <h1>Featured Items..!</h1>
</div>

<div id="container">
    <div class="img-button-container">
        <div class="img-container" id="imgs1">
            <a href="new_search.php"><img class="img1" src="https://www.iphoned.nl/wp-content/uploads/2023/09/iphone-16-capture-1.jpg" alt="Featured Item 1"></a>
        </div>
        <div id="btns1">
            <a href="new_search.php"><button class="btn1">Buy</button></a>
        </div>
    </div>

    <div class="img-button-container">
        <div class="img-container" id="imgs2">
            <a href="new_search.php"><img class="img2" src="https://www.rollingstone.com/wp-content/uploads/2022/09/Apple-AirPods-Pro-2nd-gen-hero-220907.jpg?w=1600&h=900&crop=1" alt="Featured Item 2"></a>
        </div>
        <div id="btns2">
            <a href="new_search.php"><button class="btn2">Buy</button></a>
        </div>
    </div>

    <div class="img-button-container">
        <div class="img-container" id="imgs3">
            <a href="new_search.php"><img class="img3" src="https://th.bing.com/th/id/OIP.pubLRzuG07BgR28dxeqHeQAAAA?rs=1&pid=ImgDetMain" alt="Featured Item 3"></a>
        </div>
        <div id="btns3">
            <a href="new_search.php"><button class="btn3">Buy</button></a>
        </div>
    </div>
</div>

</body>
</html>


<!-- .img-container: You will be editing the styles for all three image containers (div elements with class img-container).
This means that any styles you apply to .img-container will be applied to all three image containers.

.img-button-container: You will be editing the styles for all three image-button containers (div elements with class img-button-container).
This means that any styles you apply to .img-button-container will be applied to all three image-button containers.

#imgs1, #imgs2, #imgs3: You will be editing the styles for each individual image container (div element with ID imgs1, imgs2, or imgs3).
This means that any styles you apply to #imgs1 will only be applied to the first image container, #imgs2 will only be applied to the second image container, and #imgs3 will only be applied to the third image container.

.img1, .img2, .img3: You will be editing the styles for each individual image (img element with class img1, img2, or img3).
This means that any styles you apply to .img1 will only be applied to the first image, .img2 will only be applied to the second image, and .img3 will only be applied to the third image.

#btns1, #btns2, #btns3: You will be editing the styles for each individual button container (div element with ID btns1, btns2, or btns3).
This means that any styles you apply to #btns1 will only be applied to the first button container, #btns2 will only be applied to the second button container, and #btns3 will only be applied to the third button container.

.btn1, .btn2, .btn3: You will be editing the styles for each individual button (button element with class btn1, btn2, or btn3).
This means that any styles you apply to .btn1 will only be applied to the first button, .btn2 will only be applied to the second button, and .btn3 will only be applied to the third button.


Here's a summary:

Classes (.img-container, .img-button-container, .img1, .img2, .img3, .btn1, .btn2, .btn3) apply styles to multiple elements with the same class.
IDs (#imgs1, #imgs2, #imgs3, #btns1, #btns2, #btns3) apply styles to a single element with a unique ID. -->
