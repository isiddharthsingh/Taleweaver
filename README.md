# Taleweaver


## Overview
Taleweaver is an innovative web application that transforms user-uploaded images into captivating stories. Utilizing AWS Rekognition for image label detection and integrating with OpenAI's API, Taleweaver offers a unique storytelling experience. 

## Features
- **Image-Based Story Generation**: Users can upload an image, which is then analyzed using AWS Rekognition to detect labels. These labels are sent to OpenAI's API, which crafts a story based on the detected elements.
- **Story Summarization**: Each generated story comes with a summarization feature, allowing users to grasp the essence of the story quickly.
- **User Authentication**: The app includes full user authentication functionality, ensuring that each user’s stories remain private and secure.
- **Personal Story Archive**: Users have the ability to view all their generated stories, creating a personal archive of their Taleweaver journey.
- **Secure Data Storage**: All generated stories and user details are securely stored in AWS DynamoDB.
- **Cloud-Based Hosting**: Hosted on AWS EC2, Taleweaver leverages the cloud for reliable and scalable service.
- **Serverless Functions**: Utilizes AWS Lambda for various backend functionalities, enhancing the app’s performance and scalability.

## Technologies Used
- AWS Rekognition
- OpenAI API
- AWS DynamoDB
- AWS EC2
- AWS Lambda
- User Authentication and Session Management

## Contributing

Contributions to this project are welcome. Please fork the repository and submit a pull request for review.

## License

NA

## Contact

For technical queries and support, contact Siddharth Singh at sms10221@nyu.edu.
