document.getElementById('login').addEventListener('submit', LogIn);

function SaveLoginLocal(response) {
    let today = new Date();
    let expiryDate = new Date(today);
    expiryDate.setDate(today.getDate() + 30);

    let data = JSON.stringify({id: response.id, email: response.email, phone: response.phone, expiryDate: expiryDate})
    localStorage.setItem('loggedInUser', data)
    window.location = "https://localhost/tle2team6-ml5-2022-2023/classification-on-pi/";
}

function LogIn(e) {
    e.preventDefault();
    let emailOrPhone = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    const url = './accounts/login.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ emailOrPhone: emailOrPhone, password: password }),
    })
        .then(response => {
            if (response.ok) {
                return response.json(); // Parse response as JSON
            } else {
                throw new Error('Error updating label');
            }
        })
        .then(responseJson => {
            SaveLoginLocal(responseJson); // Pass the JSON response to loginLocal() function
        })
        .catch(error => {
            console.error(error);
        });
}