<html>
<head>
<title></title></head>
<body>
<?php
session_start();
if(isset($_SESSION["user"])){
echo "User: ".$_SESSION["user"];
?>
<form action = web_logout.php method = "post">


<input type="submit" value="Log out">
</form>

<?php

}
else{
?>
<div class="sessions">
  <form action="web_login.php">

	<label for="username">Username</label>
	<input type="text" id="username" name="username" placeholder="Enter username..">
	
	<label for="password">Password</label>
	<input type="password" id="password" name="password" placeholder="Enter password..">

    <input type="submit" value="Submit">
  </form>
</div>
<?php
}
?>
</body>
</html>