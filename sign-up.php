<?php
	
	include 'header.php';
	$headerInfo=new headerInfo();
	require_once 'GettingStoryFromImage.php';
	if(!isset($_SESSION['id'])) {
	if (isset($_POST['signup'])) {
		
		$cpassword=$email="";
		if(isset($_POST['email']) && !empty($_POST['email'])){
			$email=$_POST['email'];
		}
		if(isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
			$cpassword=$_POST['cpassword'];
		}
		$gettingStoryFromImage = new GettingStoryFromImage();
		if(!empty($email) && !empty($cpassword)){
			$checkEmail= $gettingStoryFromImage->checkEmail($email); 
			$massage=$checkEmail;
			if($checkEmail == "no"){
				 $registerUser= $gettingStoryFromImage->registerUser($email,$cpassword);
				 $massage=$registerUser['massage'];
			}
			echo '<script>Swal.fire({icon: "success",title: "'.$massage.'",showConfirmButton: true,}).then((result) => { if (result.isConfirmed) {window.location.href = "http://localhost/login.php"; }});</script>';
		}
	}
?>
<script>
        function validate_password() {
 
            var pass = document.getElementById('pass').value;
            var confirm_pass = document.getElementById('confirm_pass').value;
            if (pass != confirm_pass) {
                document.getElementById('wrong_pass_alert').style.color = 'red';
                document.getElementById('wrong_pass_alert').innerHTML
                    = "Passwords Don't Match";
                document.getElementById('create').disabled = true;
                document.getElementById('create').style.opacity = (0.4);
            } else {
            
                document.getElementById('wrong_pass_alert').innerHTML ='';
                document.getElementById('create').disabled = false;
                document.getElementById('create').style.opacity = (1);
            }
        }
 
        
    </script>
		<div class="signupCntnt">
			<div class="container">
				<div class="signupCntntInr">
					<h2>Create your free account</h2>
					<div class="registerFormOuter">
						<form action="<?php echo $headerInfo->getsiteUrl(); ?>/sign-up.php" method="post">
							<div class="fieldOuter">
								<input type="email" name="email" required id="email" placeholder="Email">
							</div>
							<div class="fieldOuter">
								<input type="password" name="password" required id="pass" placeholder="Password">
							</div>
							<div class="fieldOuter">
								<input type="password" name="cpassword" required id="confirm_pass" onkeyup="validate_password()" placeholder="Confirm Password">
							</div>
							<span id="wrong_pass_alert"></span>
							<div class="fieldOuter tcCheckbox">
								<input type="checkbox" id="termsCndition" name="terms" required value="" >
		  						<label for="termsCndition"><a href="#" target="_blank">Terms and Conditions</a></label><br>
							</div>
							<div class="fieldOuter submitBtnOuter">
								<button type="submit" class="themeBtn" name="signup">Sign up for free</button>
							</div>
						</form>
					</div>
					<div class="alredyAcc">
						<span>Already have an account?</span> <a href="login.php">Log In</a>
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
