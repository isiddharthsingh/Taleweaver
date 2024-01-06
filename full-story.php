<?php 

include 'header.php';
$headerInfo=new headerInfo();
require_once 'GettingStoryFromImage.php';
if(isset($_SESSION['id'])) {
	$gettingStoryFromImage = new GettingStoryFromImage();
	$storiesItems = $gettingStoryFromImage->getStoriesItem($_GET['id']);
	
	
?>
	<div class="dashboardOuter fullStoryPage">
		<header id="headerOuter"></header>
		<div class="fullStoryCntnt">
			<div class="container">
			<?php foreach ($storiesItems['Items'] as $item) { $summarizedStory=explode("Summarized",$item['story']['S']);$showStory=$summarizedStory[0];$showSummarizedStory=$summarizedStory[1];?>
					<div class="fullStoryInr">
						<div class="fullImg">
							<img src="uploads/<?php echo $item['image_name']['S']; ?>" alt="<?php echo $item['image_name']['S']; ?>">
						</div>
						<div class="storySummary">
							<h2>Full Story</h2>
							<div class="dateNdTime">
								<span class="storyDate"><img src="images/calendar-icon.png" alt="calendar-icon"> <samp><?php echo $item['storyDate']['S']; ?></samp></span>
								
							</div>
							<div class="storySummaryCntnt">
								<p><?php echo $showStory; ?></p>	
							</div>
							<div class="fullSummaryCntntInr">
								<h2>Summarized Story</h2>
								<p><?php echo $showSummarizedStory; ?></p>	
								
							</div>
						</div>
					</div>
			<?php } ?>
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
