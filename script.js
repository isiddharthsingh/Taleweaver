document.getElementById('uploadButton').addEventListener('click', async function() {
    var imageFile = document.getElementById('imageInput').files[0];
    if (!imageFile) {
        alert("Please select an image file.");
        return;
    }

    try {
        await uploadImage(imageFile);
        console.log("Image uploaded. Waiting 30 seconds before fetching story...");

        displayImage(imageFile);

        setTimeout(() => {
            document.getElementById('fetchButton').disabled = false;
        }, 30000); // Enable the fetch button after 30 seconds
    } catch (error) {
        console.error("Error during image upload:", error);
    }
});

document.getElementById('fetchButton').addEventListener('click', async function() {
    const userId = 'userhardcoded'; // Replace with actual user ID
    try {
        let story = await fetchStory(userId);
        document.getElementById('storySection').innerText = story;
    } catch (error) {
        console.error("Error fetching story:", error);
        document.getElementById('storySection').innerText = 'Error occurred.';
    }
});

async function uploadImage(imageFile) {
    const objectKey =  imageFile.name;
    const uploadUrl = `https://photoscc3.s3.amazonaws.com/${objectKey}`;
    // Update upload URL as per your configuration

    let headers = new Headers();
    // Add any additional headers as required

    let response = await fetch(uploadUrl, {
        method: 'PUT',
        body: imageFile,
        headers: headers
    });

    if (!response.ok) {
        throw new Error('Image upload failed');
    }
    console.log("Image uploaded:", response);
}

async function fetchStory(userId) {
    try {
        let response = await fetch(`https://oak3zt9qu6.execute-api.us-east-1.amazonaws.com/stage1/fetch?userId=${userId}`, {
            method: 'GET'
        });

        if (!response.ok) throw new Error('Failed to fetch story');
        let data = await response.json();
        return data.story;
    } catch (error) {
        console.error("Error fetching story:", error);
        return "Error fetching story.";
    }
}

function displayImage(imageFile) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('uploadedImage').src = e.target.result;
    };
    reader.readAsDataURL(imageFile);
}



// AWS Cognito SDK should be included in your HTML

// Sign Up User
function signUp(username, password) {
    var poolData = {
        UserPoolId : 'us-east-1_SodNe3z0V',
        ClientId : '56iimarr2rvhffr86kjcb48h3p'
    };
    var userPool = new AmazonCognitoIdentity.CognitoUserPool(poolData);

    userPool.signUp(username, password, [], null, function(err, result){
        if (err) {
            console.error(err);
            return;
        }
        cognitoUser = result.user;
        console.log('User registration successful:', cognitoUser.getUsername());
    });
}

// Sign In User
function signIn(username, password) {
    var authenticationData = {
        Username : username,
        Password : password,
    };
    var authenticationDetails = new AmazonCognitoIdentity.AuthenticationDetails(authenticationData);

    var poolData = {
        UserPoolId : 'us-east-1_SodNe3z0V',
        ClientId : '56iimarr2rvhffr86kjcb48h3p'
    };
    var userPool = new AmazonCognitoIdentity.CognitoUserPool(poolData);
    var userData = {
        Username : username,
        Pool : userPool
    };
    var cognitoUser = new AmazonCognitoIdentity.CognitoUser(userData);

    cognitoUser.authenticateUser(authenticationDetails, {
        onSuccess: function (result) {
            var accessToken = result.getAccessToken().getJwtToken();
            // Use the access token to access AWS services or make authenticated API calls
        },

        onFailure: function(err) {
            console.error(err);
        },
    });
}
