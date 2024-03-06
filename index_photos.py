import boto3
import json
import subprocess
import sys
from datetime import datetime



# pip install custom package to /tmp/ and add to path
subprocess.call('pip install openai==0.28 -t /tmp/ --no-cache-dir'.split(), stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
sys.path.insert(1, '/tmp/')
import openai

# Get the current timestamp
current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

# Initialize a DynamoDB client
dynamodb = boto3.resource('dynamodb')

def ask_chatgpt(labels):
    openai.api_key = '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'  # Replace with your actual API key
    prompt = f"I detected these labels in an image: {', '.join(labels)}. Make a good story using these words of 300 words?"

    try:
        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",  # Adjust the model as needed
            messages=[{"role": "user", "content": prompt}]
        )
        return response.choices[0].message['content']
    except Exception as e:
        print("Error in ChatGPT request:", e)
        return None

def store_story_in_dynamodb(user_id, story_id, story_content):
    table = dynamodb.Table('UserStories')
    
    # Create a new item (story) in the table
    table.put_item(
        Item={
            'user_id': user_id,
            'story_id': story_id,
            'story_content': story_content,
            'creation_date': current_time
        }
    )

def lambda_handler(event, context):
    # Retrieve information about the uploaded image from the S3 event
    s3_bucket = event['Records'][0]['s3']['bucket']['name']
    s3_object_key = event['Records'][0]['s3']['object']['key']

    # Initialize Rekognition client
    rekognition = boto3.client('rekognition')

    # Call detectLabels method
    response = rekognition.detect_labels(
        Image={
            'S3Object': {
                'Bucket': s3_bucket,
                'Name': s3_object_key
            }
        },
        MaxLabels=10,
        MinConfidence=75
    )
    
    # Process the response - extract labels
    labels = [label['Name'] for label in response['Labels']]

    # Send labels to ChatGPT and get a response
    chatgpt_response = ask_chatgpt(labels)
    print("Response from ChatGPT:", chatgpt_response)

    # Initialize S3 client to retrieve user ID from metadata
    s3 = boto3.client('s3')
    response = s3.head_object(Bucket=s3_bucket, Key=s3_object_key)
    user_id = response['Metadata'].get('userid', 'userhardcoded')

    # Generate a unique story ID
    story_id = s3_object_key + "-" + "story"

    # Store the story in DynamoDB
    store_story_in_dynamodb(user_id, story_id, chatgpt_response)

    return {
        'statusCode': 200,
        'headers': {
            'Access-Control-Allow-Origin': '*',  # Replace with your domain in production
            'Access-Control-Allow-Headers': 'Content-Type',
            'Access-Control-Allow-Methods': 'POST, OPTIONS'
        },
        'body': json.dumps("Story saved successfully")
    }
