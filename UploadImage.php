<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class UploadImage
{
	private $awsAccessKey;
    private $awsSecretKey;
    private $openAIKey;
    private $awsVersion;
    private $awsRegion;
	public function __construct() {
		$this->awsAccessKey = 'youraccesskey';
		$this->awsSecretKey = 'yoursecretkey';
		$this->openAIKey = 'your openai key';
		$this->awsVersion = 'latest';
		$this->awsRegion = 'us-east-1';
	}
	public function uploadImages($imageName,$imagePath){
		// Set your AWS credentials and S3 bucket information
		
		$s3 = new S3Client([
			'version' => $this->awsVersion,
			'region' => $this->awsRegion,
			'credentials' => [
				'key' => $this->awsAccessKey,
				'secret' => $this->awsSecretKey,
			],
		]);

		$bucketName = 'btwo';
		

		try {
			// Upload the image to S3
			$result = $s3->putObject([
				'Bucket' => $bucketName,
				'Key' => $imageName,
				'Body' => fopen($imagePath, 'r'),
				
			]);

			return 'uploaded';
			
		} catch (AwsException $e) {
			// Handle exceptions
			return 'Error uploading image to S3: ' . $e->getMessage() . PHP_EOL;
		}
	}

}
$time = time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/'; 
    $uploadedFile = $uploadDir .$time. basename($_FILES["fileToUpload"]["name"]); 

    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadedFile)) {
		$name=$time.$_FILES["fileToUpload"]["name"];
		  $uploadImage = new UploadImage();
		  $uploadImages=$uploadImage->uploadImages($name,$uploadedFile);
		  if($uploadImages =="uploaded"){
			echo json_encode(['status' => 'success', 'name'=>''.$name.'','message' => 'File uploaded successfully.']);
		  }
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
