document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    fetch('https://oak3zt9qu6.execute-api.us-east-1.amazonaws.com/stage1/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username: username, password: password })
    })
    .then(response => {
        if (!response.ok) {
            window.location.href = 'signup.html';
            throw new Error('Login failed');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        console.log('Login successful');
        window.location.href = 'index.html';
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
