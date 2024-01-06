<?php 

include 'header.php';
$headerInfo=new headerInfo();
require_once 'GettingStoryFromImage.php';
if(isset($_SESSION['id'])) {
	$gettingStoryFromImage = new GettingStoryFromImage();
	$storiesItems = $gettingStoryFromImage->getStoriesItems($_SESSION['id']);
	
?>
	<div class="dashboardOuter storyPage">
		<div class="storyCntnt">
			<div class="container">
				<div class="storyCntntInr">
					<div class="storyGrid">
						<?php
						
						if(empty($storiesItems['Items'])){
							echo '<span style="padding: 23px 10px 10px 10px;text-align: center;display: inline-block;" >No any story found</span>';
							}
						do {
							foreach ($storiesItems['Items'] as $item) { ?>
											<div class="storyItem">
												<div class="storyItemInr">
													<div class="storyImg">
														<img src="uploads/<?php echo $item['image_name']['S']; ?>" alt="<?php echo $item['image_name']['S']; ?>">
													</div>
													<div class="storyInfo">
														<a class="viewStoryLink" href="full-story.php?id=<?php echo $item['story_id']['S'];?>">View Story</a>
													</div>
												</div>
											</div>
							<?php }
							$params['ExclusiveStartKey'] = $storiesItems['LastEvaluatedKey'];

						} while ($storiesItems['LastEvaluatedKey']);
						?>


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
