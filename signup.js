document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('signupForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        var poolData = {
            UserPoolId: 'us-east-1_0cl1rnWbx',
            ClientId: '6ptnv8oq0vfikroeq05lisbp5k'
        };

        // Using Amplify to access Cognito
        Amplify.Auth.signUp({
            username,
            password,
            attributes: {
                email: username, // Assuming username is an email
            }
        }).then(data => {
            console.log('User registration successful:', data);
            window.location.href = 'index.html';
        }).catch(err => {
            console.error('Error during signup:', err);
        });
    });
});
