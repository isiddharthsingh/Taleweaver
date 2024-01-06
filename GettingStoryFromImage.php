<?php

require 'vendor/autoload.php';

use Aws\DynamoDb\Marshaler;

use Aws\DynamoDb\DynamoDBAttribute;
use Aws\DynamoDb\DynamoDBService;
use Aws\DynamoDb\DynamoDbClient;

use function AwsUtilities\testable_readline;



class GettingStoryFromImage
{	
		private $dynamoDbClient;
		private $awsAccessKey;
		private $awsSecretKey;
		private $openAIKey;
		private $awsVersion;
		private $awsRegion;
		
		public function __construct() {
			$this->awsAccessKey = 'your access key';
			$this->awsSecretKey = 'your secret key';
			$this->openAIKey = 'your openai key';
			$this->awsVersion = 'latest';
			$this->awsRegion = 'us-east-1';
			 
			$credentials = [
				'key'    => $this->awsAccessKey,
				'secret' => $this->awsSecretKey,
				'region' => $this->awsRegion, 
			];

			$this->dynamoDbClient = new DynamoDbClient([
				'credentials' => $credentials,
				'version'     => $this->awsVersion,
				'region'      => $this->awsRegion, 
			]);
			
		}
		
		public function getLabelsFromImage($imgname){
			$client = new Aws\Rekognition\RekognitionClient([
				'version' => $this->awsVersion,
				'region' => $this->awsRegion,
				'credentials' =>[
					'key' =>$this->awsAccessKey,
					'secret' => $this->awsSecretKey,
				]
			]);

			$result = $client->detectLabels([
				'Image' => [ // REQUIRED
						'S3Object' => [
						'Bucket' => 'btwo',
						'Name' => $imgname,
						],
					],
					'MaxLabels' =>10,
					'MinConfidence' =>20,
				]);

			if(isset($result['Labels'])){
				$allLabels=array();
				foreach($result['Labels'] as $labels){
					$allLabels[]=$labels['Name'];
				}
			}
			  return $labelsStr = implode(",", $allLabels); 
			 

		}
		
		public function getStoryFromOpenAI($labels){
			
			$client = OpenAI::client($this->openAIKey);

			$result = $client->chat()->create([
				'model' => 'gpt-3.5-turbo',
				'messages' => [
					['role' => 'user', 'content' => 'Craft an engaging narrative incorporating the following keywords: - '.$labels.' story one comprehensive and a summarized one	'],
				],
				'temperature' => 1.0,
				'max_tokens' => 4000,
				'frequency_penalty' => 0,
				'presence_penalty' => 0,
			]);
			return $result->choices[0]->message->content; 
		}

		private function createUserTable(){
			
			$client =$this->dynamoDbClient;

			$tableName = 'users';

			$params = [
				'TableName' => $tableName,
				'AttributeDefinitions' => [
					['AttributeName' => 'username', 'AttributeType' => 'S'],

				],
				'KeySchema' => [
					['AttributeName' => 'username', 'KeyType' => 'HASH'], // Partition key
				],
				'ProvisionedThroughput' => [
						'ReadCapacityUnits'  => 5, // Adjust based on your expected read throughput
						'WriteCapacityUnits' => 5, // Adjust based on your expected write throughput
					],
				];

			try {
				$result = $client->createTable($params);
				echo "Table creation successful.\n";
			} catch (Exception $e) {
				echo "Unable to create table: {$e->getMessage()} (Code: {$e->getCode()})\n";
			}


		}
		
		public function listTables(){
			$client = $this->dynamoDbClient;
			try {
				$result = $client->listTables();
				$tableNames = $result->get('TableNames');

				echo "List of tables:\n";
				foreach ($tableNames as $tableName) {
					echo $tableName . "\n";
				}
			} catch (Exception $e) {
				echo "Unable to list tables: {$e->getMessage()}\n";
			}
		}
		
		public function getStoriesItems($userid){
			$client = $this->dynamoDbClient;

			$marshaler = new Marshaler();
			$tableName = "stories";

			

			try {
					$result = $client->scan([
						'TableName' => $tableName,
						'FilterExpression' => 'user_id = :user_id',  // Assuming 'Email' is an attribute
						'ExpressionAttributeValues' => [
							':user_id' => ['S' => $userid],
						],
					]);
					return $result;

					
			} catch (Exception $e) {
				echo "Unable to scan table: {$e->getMessage()}\n";
			}

		}
		
		public function getStoriesItem($storyId){
			$client = $this->dynamoDbClient;

			$marshaler = new Marshaler();
			$tableName = "stories";

			

			try {
					$result = $client->scan([
						'TableName' => $tableName,
						'FilterExpression' => 'story_id = :story_id',  // Assuming 'Email' is an attribute
						'ExpressionAttributeValues' => [
							':story_id' => ['S' => $storyId],
						],
					]);
					return $result;

					
			} catch (Exception $e) {
				echo "Unable to scan table: {$e->getMessage()}\n";
			}

		}
		
		public function insertStory($user_id,$email_Id,$image_name,$story){
			$uuid = uniqid();
			$partitionKey = $uuid;
			$client = $this->dynamoDbClient;
			$marshaler = new Marshaler();
			$tableName = "stories";
			$item = [
				'story_id' => $partitionKey,
				'email_Id' => $email_Id,
				'image_name' => $image_name,
				'story' => $story,
				'storyDate' => date('Y-m-d H:i:s'),
				'user_id' => $user_id,
			];

			$params = [
				'TableName' => $tableName,
				'Item'      => $marshaler->marshalItem($item),
			];
			try {
				$result = $client->putItem($params);
			return  "story added successfully.\n"; 
			} catch (Exception $e) {
				echo "Unable to add item: {$e->getMessage()}\n"; die;
			}

		}
		
		public function loginUser($email, $password) {
		   $dynamoDb = $this->dynamoDbClient;
			
			try {
				$tableName = "users";
				$result = $dynamoDb->scan([
					'TableName' => $tableName,
					'FilterExpression' => 'email = :email',  
					'ExpressionAttributeValues' => [
						':email' => ['S' => $email],
					],
				]);
				
				if (!empty($result['Items'])) {
					foreach ($result['Items'] as $item) {
						
						$user_id = $item['user_id'];
						$email = $item['email'];
						if (password_verify($password, $item['password']['S'])) {
							
							$_SESSION['id'] = $item['user_id']['S'];
							$_SESSION['email'] = $item['email']['S'];
							return true;
						}
					}
				} 
			} catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
				echo "DynamoDB Exception: " . $e->getMessage() . "\n";
				echo "AWS Error Code: " . $e->getAwsErrorCode() . "\n";
				echo "HTTP Status Code: " . $e->getStatusCode() . "\n";
				
			}

			

			// Login failed
			return false;
		}
		
		public function registerUser($email,$password){
				
			$uuid = uniqid();
			$partitionKey = $uuid;
			
			$client = $this->dynamoDbClient;
			$marshaler = new Marshaler();
			$tableName = "users";

			$item = [
				'user_id' => $partitionKey,
				'email' => $email,
				'password' => $hashedPassword = password_hash($password, PASSWORD_BCRYPT),
			];

			$params = [
				'TableName' => $tableName,
				'Item'      => $marshaler->marshalItem($item),
			];
			try {
				$result = $client->putItem($params);
				return array("status"=>true,"massage"=>"User registered successfully");
			} catch (Exception $e) {
				return array("status"=>false,"massage"=>"{$e->getMessage()}");
			}

		}
		
		public function checkEmail($email){
			
			
			$dynamoDb = $this->dynamoDbClient;
			
			
			try {
				$tableName = "users";
				$result = $dynamoDb->scan([
					'TableName' => $tableName,
					'FilterExpression' => 'email = :email',  
					'ExpressionAttributeValues' => [
						':email' => ['S' => $email],
					],
				]);
				
				if (!empty($result['Items'])) {
					foreach ($result['Items'] as $item) {
						return "Email already exists";
						
					}
				} else {
					return "no";
				}
			} catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
				echo "DynamoDB Exception: " . $e->getMessage() . "\n";
				echo "AWS Error Code: " . $e->getAwsErrorCode() . "\n";
				echo "HTTP Status Code: " . $e->getStatusCode() . "\n";
			
			}

		}
		
		public function logoutUser() {
        
			session_start();
			session_unset();
			session_destroy();
		}
		
}
   

?>
