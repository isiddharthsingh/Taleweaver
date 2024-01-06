<?php 
	include 'header.php';
	$headerInfo=new headerInfo();
	require_once 'GettingStoryFromImage.php';
	if(!isset($_SESSION['id'])) {
		if (isset($_POST['login'])) {
			
			$password=$email="";
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$email=$_POST['email'];
			}
			if(isset($_POST['password']) && !empty($_POST['password'])){
				$password=$_POST['password'];
			}
			if(!empty($email) && !empty($password)){
			
					$gettingStoryFromImage = new GettingStoryFromImage();
					if ($gettingStoryFromImage->loginUser($email, $password)) {
						$location=$headerInfo->getsiteUrl()."/dashboard.php";
						$massage="You are login successfully";
						$icon="success";
					} else {
						$massage="Login failed";
						$icon="error";
					}
				
					echo '<script>Swal.fire({icon: "'.$icon.'",title: "'.$massage.'",showConfirmButton: true,}).then((result) => { if (result.isConfirmed) {window.location.href = "'.$location.'"; }});</script>';
			}
		}
?>

<div class="signupCntnt">
	<div class="container">
		<div class="signupCntntInr">
			<h2>Log in</h2>
			<div class="registerFormOuter">
				<form action="<?php echo $headerInfo->getsiteUrl(); ?>/login.php" method="post">
					<div class="fieldOuter">
						<input type="text" name="email" id="" placeholder="Email">
					</div>
					<div class="fieldOuter">
						<input type="password" name="password" id="" placeholder="Password">
					</div>
					<div class="fieldOuter forgotPwdFld">
						<a href="forgot-password.php">Forgot Password</a>
					</div>
					<div class="fieldOuter submitBtnOuter">
						<button type="submit" class="themeBtn" name="login">Log in</button>
					</div>
				</form>
			</div>
			<div class="alredyAcc">
				<span>Need an account?</span> <a href="sign-up.php">Register</a>
			</div>
		</div>
	</div>
</div>
	
<?php

}else{
	$location=$headerInfo->getsiteUrl()."dashboard.php";
		
	header("Location: $location");
	exit;
}
?>
