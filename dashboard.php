	<?php 
	include 'header.php';
	$headerInfo=new headerInfo();
	require_once 'GettingStoryFromImage.php';
	if(isset($_SESSION['id'])) {
		$showSummarizedStory=$showStory=$getLabels=$getStory=$imgName=$imgpath="";
		$imgpath="images/dash-left-img.jpg";
		if (isset($_POST['generateStory'])) {
			$gettingStoryFromImage = new GettingStoryFromImage();
			$imgName=$_POST['uploadImage']."";
			$imgpath="uploads/".$_POST['uploadImage']."";
			if(!empty($_POST['uploadImage'])){
				$getLabels = $gettingStoryFromImage->getLabelsFromImage($_POST['uploadImage']);
				$getStory = $gettingStoryFromImage->getStoryFromOpenAI($getLabels);
				$summarizedStory=explode("Summarized",$getStory);
				$showSummarizedStory=$summarizedStory[1];
				$showStory=$summarizedStory[0];
				$gettingStoryFromImage->insertStory($_SESSION['id'],$_SESSION['email'],$imgName,$getStory);
			}
			
			
		}
?>
	<div class="dashboardOuter">
		<div class="dashCntntOuter">
			<div class="container">
				<div class="dashCntntInr">
					<div class="dashLeftImg">
						<img id="leftImg" src="<?php echo $imgpath;?>" alt="<?php echo $imgName;?>">
					</div>
					<div class="dashRightCntnt">
						<div class="uploadedDataBlk">
							<?php if(empty($showSummarizedStory)){ ?>
							<span class="emptyMsg">The Generated Story will be displayed here</span>
							<?php }else{ ?>
								<div class="uploadedCntntOuter">
								<div class="uploadedCntntInr">
									<h2>Full Story</h2>
									<div class="uploadedData">
										<p><?php echo $showStory;?></p>
									</div>
									<div class="sumarisedDataOuter">
										<h2>Summarized Story</h2>
										<div class="sumarisedData">
											<p><?php echo $showSummarizedStory;?></p>
										</div>
									</div>
								</div>
							</div>
							<?php }?>
							
						</div>
					</div>
					<div class="uploadStoryOuter">
						<div class="generateCntnt">
							<form action="<?php echo $headerInfo->getsiteUrl(); ?>dashboard.php" method="post" enctype="multipart/form-data">
							  <span>Select image to upload: </span>
							  <input type="file" name="fileToUpload" id="fileToUpload" onchange="handleFileUpload()">
							  <input type="submit" value="Generate Story" name="generateStory" id="generateStoryCheck">
							  <input type="hidden" value="" name="uploadImage" id="uploadImage">
							  <button type="button" name="Generate Summary" class="themeBtn" id="sumarisedDataOuterButton">Generate Summary</button>
							</form>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php

}else{
	$location=$headerInfo->getsiteUrl()."index.php";
		
	header("Location: $location");
	exit;
}
			?>	
