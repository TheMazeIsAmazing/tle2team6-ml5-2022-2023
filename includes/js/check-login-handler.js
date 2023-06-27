if (localStorage.getItem('loggedInUser')) {
    let json = JSON.parse(localStorage.getItem('loggedInUser'))
    let expiryDate = json.expiryDate

    // Get today's date
    let today = new Date();

    // Get today's date in the format 'YYYY-MM-DD'
    let todayString = today.toISOString().split('T')[0];

    // Calculate the future date in the format 'YYYY-MM-DD'
    let expiryDateString = expiryDate.split('T')[0];

    // Check if today's date is the same as the future date
    if (todayString === expiryDateString) {
        localStorage.removeItem('loggedInUser')
        window.location = "https://localhost/tle2team6-ml5-2022-2023/";
    }
} else {
    console.log(window.location.href)
    if (window.location.href !== 'https://localhost/tle2team6-ml5-2022-2023/') {
        window.location = "https://localhost/tle2team6-ml5-2022-2023/";
    }
}