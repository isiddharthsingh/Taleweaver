import boto3
import json
import requests
import subprocess
import sys

# pip install custom package to /tmp/ and add to path
subprocess.call('pip install openai==0.28 -t /tmp/ --no-cache-dir'.split(), stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
sys.path.insert(1, '/tmp/')
import openai

# Function to send labels to ChatGPT and get a response
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


def lambda_handler(event, context):
    # Your Elasticsearch configuration
    elasticsearch_url = "https://search-photos-5fq6juhzphna3uzhzgb5kgq6ry.us-east-1.es.amazonaws.com"
    index_name = "photos_index"
    auth = ("rohitmohanty", "Chocolates@123")

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

    # Initialize S3 client
    s3 = boto3.client('s3')

    # Retrieve S3 object metadata
    response = s3.head_object(Bucket=s3_bucket, Key=s3_object_key)
    created_timestamp = response['LastModified'].isoformat()

    # Retrieve User ID from metadata (assuming it's stored in the metadata)
    user_id = response['Metadata'].get('userid', 'unknown')

    # Construct JSON object with user ID, labels, and ChatGPT response
    json_object = {
        "objectKey": s3_object_key,
        "bucket": s3_bucket,
        "createdTimestamp": created_timestamp,
        "labels": labels,
        "userId": user_id,
        "chatGPTResponse": chatgpt_response
    }

    # Index the document in Elasticsearch
    url = f"{elasticsearch_url}/{index_name}/_doc/{s3_object_key}"
    headers = {"Content-Type": "application/json"}
    response = requests.post(url, auth=auth, headers=headers, data=json.dumps(json_object))

    # Check response from Elasticsearch
    if response.status_code != 201:
        print("Failed to index document in Elasticsearch:", response.text)
        return {
            'statusCode': 500,
            'body': "Failed to index document in Elasticsearch"
        }

    return {
        'statusCode': 200,
        'body': json.dumps(json_object)
    }
