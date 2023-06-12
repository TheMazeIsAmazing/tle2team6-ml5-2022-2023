// Grab all the DOM elements
const video = document.getElementById('video'); // Video element
const videoStatus = document.getElementById('video-status'); // Element to display video status
const loading = document.getElementById('loading'); // Element to display loading status
const startStopButton = document.getElementById('start-stop-button')

let classifying = false

let latestClassifiedLabels = [];
const labelThreshold = 80; // Number of times the label should occur before updating the database

console.log('force')

// Create a webcam capture
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({video: true}).then(function (stream) {
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
        };
    });
}

// A function to be called when the model has been loaded
function modelLoaded() {
    loading.innerText = 'Model loaded!';
    classifier.load('./model/model.json', function () {
        loading.innerText = "Model and custom model loaded!";
        startStopButton.addEventListener('click', toggleClassification)
    })
}

function toggleClassification() {
    if (classifying) {
        classifying = false
        startStopButton.innerHTML = 'Start Classifying';
        startStopButton.classList.replace('btn-danger', 'btn-primary')
    } else {
        classifying = true
        startStopButton.innerHTML = 'Stop Classifying';
        startStopButton.classList.replace('btn-primary', 'btn-danger')
        classifier.classify(gotResults);
    }

}

// Extract the already learned features from MobileNet
const featureExtractor = ml5.featureExtractor('MobileNet', modelLoaded);
// Create a new classifier using those features
const classifier = featureExtractor.classification(video, videoReady);

// A function to be called when the video is finished loading
function videoReady() {
    videoStatus.innerText = 'Video ready!';
}

// Show the results
function gotResults(err, results) {
    // Display any error
    if (err) {
        console.error(err);
    }
    if (results && results[0]) {
        result.innerText = results[0].label;
        confidence.innerText = results[0].confidence;

        const classifiedLabel = JSON.parse(results[0].label).id;
        console.log('Label:', classifiedLabel);

        // Update label counts
        latestClassifiedLabels.push(classifiedLabel);

        console.log(latestClassifiedLabels)
        console.log(latestClassifiedLabels.length)

        // Check if the label count threshold is reached
        if (latestClassifiedLabels.length >= 100) {
            if (latestClassifiedLabels.filter(label => label === classifiedLabel).length > labelThreshold) {
                // // Find the most occurring label
                // const mostOccurringLabel = Object.keys(labelCounts).reduce((a, b) => labelCounts[a] > labelCounts[b] ? a : b);
                // console.log('Updating database with label:', mostOccurringLabel);

                // // Update the database
                // updateDatabase(mostOccurringLabel);
                console.log('wow i`m in here')

                // Reset label counts
                latestClassifiedLabels = [];
            }
            latestClassifiedLabels.shift()
        }


        if (classifying) {
            classifier.classify(gotResults);
        }
    }
}

function updateDatabase(label) {
    const url = '../includes/back-end-handlers/recognition-handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({label}),
    })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Error updating label');
            }
        })
        .then(responseText => {
            console.log(responseText);
        })
        .catch(error => {
            console.error(error);
        });
}