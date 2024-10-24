<html>
<head>
<title>Phones</title>
<link rel="stylesheet" type="text/css" href="web_phones.css">
</head>
<body>

<ul>
    <li><a href="web_homepage.php">Home</a></li>
    <li><a href="web_browse.php">Browse</a></li>
    <li><a href="web_contactus.php">Contact us</a></li>
	
    <li style="float:right">
	<?php
	session_start();
	if(isset($_SESSION["user"])){
	echo "<div class='user'>User: ".$_SESSION["user"]. "</div>";
	echo "<a class='active' href='web_logout.php'>Log out</a>";
	?>
	<li><a href="web_basket.php">Basket</a></li>
	<?php
	}
	else {
    ?>
	<a class="active" href="web_signuplogin.php">Sign up/ Log in</a>
	<?php
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


  <div class="phones1">
    <h1>Phones</h1>
  </div>

  <div id="box">
    <div class="img-button-container">
    </div>
  </div>

</body>
</html>