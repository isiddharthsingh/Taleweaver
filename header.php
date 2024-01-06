<?php
include 'headerInfo.php';
	session_start();
?>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/media.css">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<div class="loaderOuter">
    <div class="loaderInr">
        <img src="images/loading.gif" alt="loading">
    </div>
</div>
<header id="headerOuter">
	<div class="container-fluid">
		<div class="row headerInr">
			<div class="logoOuter">
				<a href="index.php">
					<img src="images/logo.png" alt="logo">
					<h1>Taleweaver</h1>
				</a>
			</div>
			<?php
			if(isset($_SESSION['id'])) {
			?>
			<div class="headerRight">
				<div class="headerRightMenu">
					<a href="view-story.php" title="View Story" ><img src="images/story-icon.png" alt="story-icon"></a>
					<a href="dashboard.php" title="Home" ><img src="images/home-icon.png" alt="home-icon"></a>
					<a href="logout.php" title="Logout"><img src="images/logout.png" alt="logout"></a>
					<a href="my-account.php" title="My Account"><img src="images/user.png" alt="user"></a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</header>


