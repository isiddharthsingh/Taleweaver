<?php 
	include 'header.php';
	$headerInfo=new headerInfo();
	require_once 'GettingStoryFromImage.php';
	if(isset($_SESSION['id'])) {
			$gettingStoryFromImage = new GettingStoryFromImage();
			$gettingStoryFromImage->logoutUser();
			$location=$headerInfo->getsiteUrl()."index.php";
				
			header("Location: $location");
			exit;
	}
?>
