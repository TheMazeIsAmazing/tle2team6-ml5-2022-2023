// Grab all the DOM elements
const video = document.getElementById('video'); // Video element
const videoStatus = document.getElementById('video-status'); // Element to display video status
const loading = document.getElementById('loading'); // Element to display loading status
const startStopButton = document.getElementById('start-stop-button');

const errorSound = document.getElementById('error-sound');
const successSound = document.getElementById('success-sound');

let classifying = false;

let latestClassifiedLabels = [];
const labelThreshold = 120; // Number of times the label should occur before updating the database
const labelLimit = 150; // Limits the length of latestClassifiedLabels

// Create a webcam capture
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function (stream) {
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
        };
    });
}

// A function to be called when the model has been loaded
function ModelLoaded() {
    loading.innerText = 'Model loaded!';
    classifier.load('./model/model.json', function () {
        loading.innerText = 'Model and custom model loaded!';
        startStopButton.addEventListener('click', ToggleClassification);
    });
}

function ToggleClassification() {
    if (classifying) {
        classifying = false;
        startStopButton.innerHTML = 'Start Classifying';
        startStopButton.classList.replace('btn-danger', 'btn-primary');
    } else {
        classifying = true;
        startStopButton.innerHTML = 'Stop Classifying';
        startStopButton.classList.replace('btn-primary', 'btn-danger');
        classifier.classify(GotResults);
    }
}

const options = { numLabels: 9 };

// Extract the already learned features from MobileNet
const featureExtractor = ml5.featureExtractor('MobileNet', ModelLoaded);
// Create a new classifier using those features
const classifier = featureExtractor.classification(video, options, VideoReady);


// A function to be called when the video is finished loading
function VideoReady() {
    videoStatus.innerText = 'Video ready!';
}

// Show the results
function GotResults(err, results) {
    // Display any error
    if (err) {
        errorSound.play();
        console.error(err);
    }
    if (results && results[0]) {
        result.innerText = results[0].label;
        confidence.innerText = results[0].confidence;

        const classifiedLabel = results[0].label;

        // Update label counts
        latestClassifiedLabels.push(classifiedLabel);

        // Check if the label count threshold is reached
        if (latestClassifiedLabels.length >= labelLimit) {
            if (latestClassifiedLabels.filter(label => label === classifiedLabel).length > labelThreshold) {
                // Update the database
                UpdateDatabase(classifiedLabel);

                latestClassifiedLabels = [];
            }
            latestClassifiedLabels.shift();
        }

        if (classifying) {
            classifier.classify(GotResults);
        }
    }
}

function UpdateDatabase(label) {
    const url = '../includes/back-end-handlers/recognition-handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ label: label }),
    })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                errorSound.play();
                throw new Error('Error updating label');
            }
        })
        .then(responseText => {
            successSound.play();
            console.log(responseText);
        })
        .catch(error => {
            errorSound.play();
            console.error(error);
        });
}
