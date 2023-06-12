// Grab all the DOM elements
const video = document.getElementById('video'); // Video element
const videoStatus = document.getElementById('video-status'); // Element to display video status
const loading = document.getElementById('loading'); // Element to display loading status

const addImageButton = document.getElementById('add-image-button'); // Button to add new image
const imageFilesInput = document.getElementById('training-data'); // Input for image files

const source = document.getElementById('source'); // Source selection element
let trainingSource = 'image'; // Variable to store the selected training source

const trainButton = document.getElementById('train'); // Train button
const saveButton = document.getElementById('save-button'); // Save button
const loss = document.getElementById('loss'); // Element to display loss
const result = document.getElementById('result'); // Element to display prediction result
const confidence = document.getElementById('confidence'); // Element to display prediction confidence
const predict = document.getElementById('predict'); // Predict button

let trainedWith = []

//
// ML5 related code
//

// A variable to store the total loss
let totalLoss = 0;

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
function modelLoaded() {
    loading.innerText = 'Model loaded!';
}

// Extract the already learned features from MobileNet
const featureExtractor = ml5.featureExtractor('MobileNet', modelLoaded);
// Create a new classifier using those features
const classifier = featureExtractor.classification(video, videoReady);

// A function to be called when the video is finished loading
function videoReady() {
    videoStatus.innerText = 'Video ready!';
}

trainButton.onclick = function () {
// When the train button is pressed, train the classifier) {
    classifier.train(function (lossValue) {
        if (lossValue) {
            totalLoss = lossValue;
            loss.innerHTML = `Were training! Current Loss: ${totalLoss}`;
        } else {
            loss.innerHTML = `Done Training! Final Loss: ${totalLoss}`;
            document.getElementById('hidden-if-not-trained').style.display = 'block';
        }
    });
}

// When the save button is pressed, save the model
saveButton.onclick = function () {
    classifier.save();
};

// Show the results
function gotResults(err, results) {
    // Display any error
    if (err) {
        console.error(err);
    }
    if (results && results[0]) {
        result.innerText = results[0].label;
        confidence.innerText = results[0].confidence;
        classifier.classify(gotResults);
    }
}

// Start predicting when the predict button is clicked
predict.onclick = function () {
    classifier.classify(gotResults);
};

// Event listeners for adding new data
addImageButton.addEventListener('click', addNewImage);

function addNewImage(e) {
    e.preventDefault();

    // Use existing product information
    let productInformation = document.getElementById('item-select').value;

    if (trainedWith.indexOf(productInformation <= 0)) {
        trainedWith.push(productInformation)
    }

    if (trainedWith.length >= 2) {
        document.getElementById('hidden-if-no-data').style.display = 'block';
        document.getElementById('hidden-if-data').style.display = 'none';
    }

    trainingSourceHanlder(productInformation)
}

function trainingSourceHanlder(productInformation) {
    if (trainingSource !== 'image') {
        // Add image from video to the classifier
        classifier.addImage(video, productInformation);
        console.log(video, productInformation)

        document.getElementById('u-did-it').innerHTML = `Je hebt het algoritme succesvol 1 afbeelding van je camera geleerd voor het product met nummer ${productInformation}`;
        document.getElementById('u-did-it').style.display = 'block';
    } else {
        const files = imageFilesInput.files;
        if (files.length > 0) {
            for (let file of files) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const img = new Image();
                    img.src = event.target.result;

                    img.onload = function () {
                        classifier.addImage(img, productInformation);
                        console.log(img, productInformation)
                    };
                };
                reader.readAsDataURL(file);
            }
            document.getElementById('u-did-it').innerHTML = `Je hebt succesvol het algoritme ${files.length} afbeelding(en) geleerd voor het product met nummer ${productInformation}`;
            document.getElementById('u-did-it').style.display = 'block';

        } else {
            alert('Please select at least one image file.');
        }
    }
}

//
// Form Related code
//

// Event listener for source selection change
source.addEventListener('change', (e) => {
    trainingSource = e.target.value;
    if (e.target.value !== 'image') {
        document.getElementById('training-data').style.display = 'none';
        document.getElementById('training-data-label').style.display = 'none';
    } else {
        document.getElementById('training-data').style.display = 'block';
        document.getElementById('training-data-label').style.display = 'block';
    }
});
