import boto3
import json

def lambda_handler(event, context):
    # Initialize a DynamoDB client
    dynamodb = boto3.resource('dynamodb')
    table = dynamodb.Table('UserStories')

    # Extract user_id from the event
    user_id = event['queryStringParameters']['userId']
    
    # Query DynamoDB for the story
    response = table.query(
        KeyConditionExpression=boto3.dynamodb.conditions.Key('user_id').eq(user_id)
    )

    # Check if any items were returned
    if response['Items']:
        # Assuming you want to return the first story
        story = response['Items'][0]['story_content']
        return {
        'statusCode': 200,
        'headers': {
            'Access-Control-Allow-Origin': '*',  # Replace with your domain in production
            'Access-Control-Allow-Headers': 'Content-Type',
            'Access-Control-Allow-Methods': 'GET, OPTIONS'
        },
        'body': json.dumps({'story': story})  # Replace with the actual story
    }
    else:
        return {
            'statusCode': 404,
            'headers': {
            'Access-Control-Allow-Origin': '*',  # Replace with your domain in production
            'Access-Control-Allow-Headers': 'Content-Type',
            'Access-Control-Allow-Methods': 'GET, OPTIONS'
        },
            'body': json.dumps({'message': 'Story not found'})
        }
