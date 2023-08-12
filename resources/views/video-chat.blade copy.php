<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Call App</title>
  <!-- Include CSS and JavaScript dependencies -->
  <link rel="stylesheet" href="./css/app.css">
</head>
<body>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="btn-group" role="group">
            <!-- Buttons for each user -->
            <!-- Buttons will be dynamically created using JavaScript -->
          </div>
        </div>
      </div>
      <div class="row mt-5" id="video-row">
        <div class="col-12 video-container">
          <video id="userVideo" muted playsinline autoplay class="cursor-pointer user-video"></video>
          <video id="partnerVideo" playsinline autoplay class="cursor-pointer partner-video"></video>
          <div class="partner-video">
            <div class="column items-center q-pt-xl">
              <div class="col q-gutter-y-md text-center">
                <p class="q-pt-md"><strong id="callPartner"></strong></p>
                <p>calling...</p>
              </div>
            </div>
          </div>
          <div class="action-btns">
            <button type="button" class="btn btn-info" id="toggleMuteAudio">Mute</button>
            <button type="button" class="btn btn-primary mx-4" id="toggleMuteVideo">HideVideo</button>
            <button type="button" class="btn btn-danger" id="endCall">EndCall</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="incomingCallDialog">
    <div class="col">
      <p>Incoming Call From <strong id="callerName"></strong></p>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-danger" id="declineCall">Decline</button>
        <button type="button" class="btn btn-success ml-5" id="acceptCall">Accept</button>
      </div>
    </div>
  </div>

  <script src="./js/app.js"></script>
  <script>
	document.addEventListener("DOMContentLoaded", function() {
	const userVideo = document.getElementById("userVideo");
	const partnerVideo = document.getElementById("partnerVideo");
	const toggleMuteAudio = document.getElementById("toggleMuteAudio");
	const toggleMuteVideo = document.getElementById("toggleMuteVideo");
	const endCall = document.getElementById("endCall");
	const incomingCallDialog = document.getElementById("incomingCallDialog");
	const callerName = document.getElementById("callerName");
	const acceptCall = document.getElementById("acceptCall");
	const declineCall = document.getElementById("declineCall");

	// Initialize your variables here
	let isFocusMyself = true;
	let callPlaced = false;
	let callPartner = null;
	let mutedAudio = false;
	let mutedVideo = false;

	// Add event listeners and functions similar to Vue methods here
	function getMediaPermission() {
		// Your implementation
	}

	function placeVideoCall(id, name) {
		// Your implementation
	}

	function acceptCall() {
		// Your implementation
	}

	function declineCall() {
		// Your implementation
	}

	// More functions...

	// You can call these functions based on events and user interactions

	// Example:
	acceptCall.addEventListener("click", () => {
		acceptCall();
	});

	// Add more event listeners as needed
	});

  </script>
</body>
</html>
