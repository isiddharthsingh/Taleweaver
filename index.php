<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Taleweaver | Home</title>
<link rel="icon" href="">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/media.css">
</head>
<?php
include 'headerInfo.php';
	session_start();
	$headerInfo=new headerInfo();
?>
<body>
	<?php
	if(!isset($_SESSION['id'])) {
	?>
	<div class="talewearMainOuter">
		<div class="logoOuter">
			<a href="index.php">
				<img src="images/logo.png" alt="logo">
				<h1>Taleweaver</h1>
			</a>
		</div>
		<div class="registerOuter">
			<a href="login.php" class="loginBtn"><i class="fa-solid fa-arrow-right-to-bracket"></i> <span>Log In</span></a>
			<a href="sign-up.php" class="signupBtn"><i class="fa-solid fa-user-plus"></i> <span>Sign Up</span></a>
		</div>
	</div>
	<?php

	}else{
		$location=$headerInfo->getsiteUrl()."dashboard.php";
			
		header("Location: $location");
		exit;
	}
	?>
</body>
</html>
