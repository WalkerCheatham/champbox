<!DOCTYPE html>
<html>
<head>
<link rel="icon" type="image/x-icon" href="./cboxlogo.PNG">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="./assets/plyr.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<style>
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

.seasons {
  margin-top: 20px;
}

.season-tabs {
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #ccc;
}

.season-tabs li {
  padding: 10px;
  cursor: pointer;
  background-color: #ccc;
  transition: background-color 0.3s;
}

.season-tabs li.active {
  background-color: #f00;
  color: #fff;
}

.season-episodes {
  background-color: #205837;
  display: none;
  margin-top: 20px;
  padding: 10px;
}

.season-episodes.active {
  display: block;
}

#episodes-list {
  display: grid;
  flex-wrap: wrap;
  background-color: #333;
  color: #fff;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
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


.responsive-heading {
  padding: 6px;
  font-size: 13px;
  line-height: 1.2;
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
  height: auto !important;
}

.vjs-tech {
  object-fit: contain !important;
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
}
</style>
<script>
// JavaScript code

// Variables to hold the video player and Cast session
let videoPlayer;

// Callback function to open the modal and start playing the video
function openModal(video_source) {
  if (videoPlayer) {
    videoPlayer.source = {
      type: 'video',
      sources: [{
        src: video_source,
        type: 'video/mp4'
      }]
    };
    videoPlayer.play();
    document.getElementById('modal-player').classList.add('active');
  }
}

// Callback function to close the modal and stop the video
function closeModal() {
  if (videoPlayer) {
    videoPlayer.pause();
    document.getElementById('modal-player').classList.remove('active');
  }
}

// Callback function to initialize the video player
function initializeVideoPlayer() {
  videoPlayer = new Plyr('#modal-video-player', {
    controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
    autoplay: false,
    fluid: true,
    fullscreen: { enabled: true, iosNative: true }
  });
}


// Initialize the video player
window.addEventListener('load', function () {
  initializeVideoPlayer();
});

// Event listeners and other JavaScript code

showSeason(<?php echo $seasonNumber; ?>);
</script>
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
    <div id="modal-player" class="modal">
  <div class="modal-content">
  <div class="video-container">
  <video id="modal-video-player" class="plyr__video-player" src="" allowfullscreen></video>
      <span class="modal-close" onclick="closeModal()">&times;</span>
    </div>
  </div>
</div>
<section class="seasons">
  <ul class="season-tabs">
    <?php foreach ($seasons as $season) : ?>
      <li class="<?php echo $seasonNumber == $season ? 'active' : ''; ?>" onclick="showSeason(<?php echo $season; ?>)"><?php echo $season; ?></li>
    <?php endforeach; ?>
  </ul>

  <?php foreach ($seasons as $season) : ?>
    <div id="season-<?php echo $season; ?>" class="season-episodes <?php echo $seasonNumber == $season ? 'active' : ''; ?>">
      <h2>Season <?php echo $season; ?> Episodes:</h2>
      <div id="episodes-list">
        <?php
        // Get the episodes for the specified season from the database
        $stmt = $database->prepare('SELECT * FROM episodes WHERE series_id = :seriesId AND season_number = :seasonNumber ORDER BY episode_number ASC');
        $stmt->bindValue(':seriesId', $seriesData['series_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':seasonNumber', $seasonNumber, SQLITE3_TEXT);
        $result = $stmt->execute();

        $episodes = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
          $episodes[] = [
            'episode_number' => $row['episode_number'],
            'episode_name' => $row['episode_name'],
            'video_source' => $row['video_source']
          ];
        }
        $stmt->close();

        foreach ($episodes as $episode) :
        ?>
          <div class="responsive-heading">
            <a onclick="openModal('<?php echo $episode['video_source']; ?>')" data-video-source="<?php echo $episode['video_source']; ?>">
              Episode <?php echo $episode['episode_number']; ?>: <?php echo $episode['episode_name']; ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</section>
  <script src="./assets/plyr.js"></script>
 
    </section>
</body>
</html>