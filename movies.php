<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="./cboxlogo.PNG">
    <link rel="stylesheet" href="./assets/plyr.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
      /* CSS code here */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'arial';
}

header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 40px 100px;
    z-index: 1000;
}

.banner {
    position: relative;
    width: 100%;
    min-height: 100vh;
    padding: 0 100px;
    background: linear-gradient(rgba(0, 0, 0, 0.527), rgba(0, 0, 0, 0.664)), url('<?php echo $backgroundImg; ?>');
    background-position: center;
    background-size: cover;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

.banner .content {
    max-width: 550px;
}

.banner .content h2 {
    text-transform: uppercase;
    font-weight: 400;
    font-size: 2.5em;
    letter-spacing: 0.1em;
    color: #fff;
}

.banner .content h2 span {
    font-weight: 800;
}

.banner .content p {
    font-weight: 300;
    font-size: 1.2em;
    letter-spacing: 0.02em;
    line-height: 1.5em;
    color: #fff;
    margin: 15px 0 35px;
}

.play {
    position: relative;
    display: inline-flex;
    justify-content: flex-start;
    align-items: center;
    color: #fff;
    text-transform: uppercase;
    font-weight: 500;
    text-decoration: none;
    letter-spacing: 2px;
    font-size: 1.2em;
    cursor: pointer;
}

.play img {
    margin-right: 10px;
    max-width: 50px;
}

/* CSS styles for the modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    display: flex;
    overflow: hidden;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal.active {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    position: relative;
    width: 100%;
    height: 100%;
    max-width: 800px;
    max-height: 80vh;
    background-color: #000;
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.modal-content video {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
}

.modal-content .modal-close {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    z-index: 1001; /* Increase the z-index value to ensure it appears above the fullscreen video player */
}
/* New styles */
/* CSS styles for the custom video controls */
.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.video-controls {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 10px;
    font-size: 14px;
}

/* New styles for video player */
.video-js {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
}

.vjs-tech {
    object-fit: contain !important;
}

/* CSS styles for the menu */
.navigation {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #205837;
    color: #fff;
    z-index: 100;
}

.menu-items {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    justify-content: space-between;
}

.menu-items li {
    margin: 0;
}

.menu-items li a {
    color: #fff;
    text-decoration: none;
    padding: 15px;
    display: block;
}

.menu-items li a:hover {
    background-color: #666;
}

.content {
    margin-top: 50px;
    padding: 0 15px;
}

.responsive-heading {
    padding: 6px;
    font-size: 24px;
    line-height: 1.2;
}

/* Mobile styles */
@media only screen and (max-width: 768px) {

    .logo {
        padding-top: 25px;
        max-width: 150px;
        height: auto;
        /* Added height: auto to maintain the logo's aspect ratio */
    }

    .banner {
        padding: 0 20px;
    }

    .banner .content {
        max-width: 100%;
    }

    .banner .content h2 {
        font-size: 2em;
    }

    .banner .content p {
        font-size: 1em;
    }

    .play {
        font-size: 0.9em;
    }
}
    </style>

    <!-- Include the Chromecast SDK script -->
 

</head>
<body>
<div class="navigation">
    <ul class="menu-items">
        <li><a href="./index.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="./upload_form.html">Uploader</a></li>
        <li><a href="./admin.php">Admin</a></li>
    </ul>
</div>

  <section class="banner">
    <div class="content">
      <h2><?php echo $title; ?></h2>
      <p><?php echo $summary; ?></p>
      <a href="#" class="play" onclick="openModal('<?php echo $videoSource; ?>')" data-video-source="<?php echo $videoSource; ?>"><img src="./assets/play.png" alt="">Watch Now</a>
    </div>
    <div id="modal-player" class="modal">
    <div class="modal-content">
      <div class="video-container">
      <video id="modal-video-player" class="plyr__video-player" allowfullscreen></video>
      <span class="modal-close" onclick="closeModal()">&times;</span>
      <google-cast-launcher></google-cast-launcher>
              </div>
    </div>
  </div>

<script>
var player = new cast.framework.RemotePlayer();
var controller = new cast.framework.RemotePlayerController(player);
// Listen to any player update, and trigger angular data binding
update.controller.addEventListener(
  cast.framework.RemotePlayerEventType.ANY_CHANGE,
  function(event) {
    if (!$scope.$$phase) $scope.$apply();
  });
</script>
    </section>
    <script src="./assets/plyr.js"></script>
    <script src="//www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
    <script>
window['__onGCastApiAvailable'] = function(isAvailable) {
  if (isAvailable) {
    initializeCastApi();
  }
};
</script>
    <script>
        // JavaScript code

        // Variables to hold the video player and Cast session
        let videoPlayer;
        let castSession;

        // Function to open the modal and play the video
        function openModal(videoSource) {
            const modal = document.getElementById('modal-player');
            const modalVideoPlayer = document.getElementById('modal-video-player');

            // Set the video source for the modal video player
            modalVideoPlayer.src = videoSource;

            // Create a new Plyr video player instance
            videoPlayer = new Plyr(modalVideoPlayer, {
                controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
            });

            // Open the modal and play the video
            modal.classList.add('active');
            videoPlayer.play();
        }

        // Function to close the modal and stop the video
        function closeModal() {
            const modal = document.getElementById('modal-player');

            // Stop the video and destroy the Plyr video player instance
            videoPlayer.stop();
            videoPlayer.destroy();

            // Close the modal
            modal.classList.remove('active');
        }

        // Callback function to initialize the Cast SDK
        initializeCastApi = function() {
  cast.framework.CastContext.getInstance().setOptions({
    receiverApplicationId: applicationId,
    autoJoinPolicy: chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED
  });
};

        // Callback function for successful Cast session
        function onSessionSuccess(session) {
            castSession = session;
            console.log('Cast session initialized:', session);
        }

        // Callback function for Cast session error
        function onSessionError(error) {
            console.error('Cast session error:', error);
        }

        // Callback function to handle Cast button click
        function onCastButtonClick() {
            if (castSession) {
                const videoSource = document.querySelector('.play').getAttribute('data-video-source');
                // Load media and play on Cast session
                const mediaInfo = new chrome.cast.media.MediaInfo(videoSource, 'video/mp4');
                const request = new chrome.cast.media.LoadRequest(mediaInfo);
                castSession.loadMedia(request).then(onLoadSuccess, onLoadError);
            }
        }

        // Callback function for successful media load
        function onLoadSuccess() {
            console.log('Media loaded and playing on Cast device');
        }

        // Callback function for media load error
        function onLoadError(error) {
            console.error('Media load error:', error);
        }

        // Event listener for DOMContentLoaded event
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the Cast SDK
            initializeCastSdk();
        });
    </script>
</body>
</html>
