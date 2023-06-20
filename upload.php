<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to remove unacceptable characters from filenames
function sanitizeFilename($filename) {
    $unacceptableCharacters = [':', ';', "'", '"']; // Add more characters as needed
    return str_replace($unacceptableCharacters, '', $filename);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $poster = $_FILES['poster']['name'] ?? '';
    $backgroundImg = $_FILES['backgroundImg']['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $seasonNumber = $_POST['seasonNumber'] ?? '';
    $episodeNumbers = $_POST['episodeNumber'] ?? []; // Initialize as an array
    $episodeNames = $_POST['episodeName'] ?? []; // Initialize as an array
    $genre = $_POST['genre'] ?? '';
    $subtitles = $_FILES['subtitles']['name'] ?? [];

   
    // Move uploaded files to the appropriate folders
    $baseDirectory = __DIR__;
    $posterExtension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $posterFileName = sanitizeFilename($title) . '.' . $posterExtension;
    $posterTarget = './Posters/' . $posterFileName;
    $backgroundImgExtension = pathinfo($_FILES['backgroundImg']['name'], PATHINFO_EXTENSION);
    $backgroundImgFileName = sanitizeFilename($title) . '.' . $backgroundImgExtension;
    $backgroundImgTarget = './Background Images/' . $backgroundImgFileName;

    move_uploaded_file($_FILES['poster']['tmp_name'], $baseDirectory . '/' . $posterTarget);
    move_uploaded_file($_FILES['backgroundImg']['tmp_name'], $baseDirectory . '/' . $backgroundImgTarget);

    // Create and open the SQLite database connection
    $database = new SQLite3($baseDirectory . '/movies-series.db');

    // Check if the series already exists in the database
    $stmt = $database->prepare('SELECT series_id FROM movies_series WHERE title = :title');
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $result = $stmt->execute();

    $seriesData = $result->fetchArray(SQLITE3_ASSOC);
    $stmt->close();

    // If the series exists, retrieve its series_id
    if ($seriesData) {
        $seriesId = $seriesData['series_id'];

 // Generate the PHP file location based on the series or movie type
 $phpFileLocation = strtolower(str_replace(' ', '_', sanitizeFilename($title))) . '.php';
} else {
         // Insert the series data and retrieve the last inserted series_id
         $stmt = $database->prepare('INSERT INTO movies_series (title, summary, poster, background_img, type, genre, php_file_location) VALUES (:title, :summary, :poster, :backgroundImg, :type, :genre, :phpFileLocation)');
         $stmt->bindValue(':title', $title, SQLITE3_TEXT);
         $stmt->bindValue(':summary', $summary, SQLITE3_TEXT);
         $stmt->bindValue(':poster', $posterTarget, SQLITE3_TEXT);
         $stmt->bindValue(':backgroundImg', $backgroundImgTarget, SQLITE3_TEXT);
         $stmt->bindValue(':type', $type, SQLITE3_TEXT);
         $stmt->bindValue(':genre', $genre, SQLITE3_TEXT);
 
         // Generate the PHP file location based on the series or movie type
         $phpFileLocation = strtolower(str_replace(' ', '_', sanitizeFilename($title))) . '.php';
         $stmt->bindValue(':phpFileLocation', $phpFileLocation, SQLITE3_TEXT);
 
         $stmt->execute();
 
         // Retrieve the last inserted series ID
         $seriesId = $database->lastInsertRowID();
 
         $stmt->close();
     }
 
     // Prepare the SQL statement for inserting data into the episodes table
     $episodeStmt = $database->prepare('INSERT INTO episodes (series_id, season_number, episode_number, episode_name, video_source) VALUES (:seriesId, :seasonNumber, :episodeNumber, :episodeName, :videoSource)');
 
     // Insert multiple episodes into the database
if (is_array($episodeNumbers)) {
    foreach ($episodeNumbers as $key => $episodeNumber) {
        if (isset($episodeNames[$key])) {
            $episodeName = $episodeNames[$key];
        } else {
            $episodeName = '';
        }

        $episodeExtension = pathinfo($_FILES['episodeFile']['name'][$key], PATHINFO_EXTENSION); // Get the episode file extension

        if ($type === 'series') {
            $episodeFileName = "S{$seasonNumber}E{$episodeNumber}-" . sanitizeFilename($title) . '.' . $episodeExtension;
            $episodeFileTarget = './Episodes/' . $episodeFileName; // Set the target directory for series
        } else if ($type === 'movie') {
            $episodeFileName = sanitizeFilename($title) . '.' . $episodeExtension;
            $episodeFileTarget = './Moviemp4/' . sanitizeFilename($episodeFileName); // Set the target directory for movies
        }

        $episodeStmt->bindValue(':seriesId', $seriesId, SQLITE3_INTEGER);
        $episodeStmt->bindValue(':seasonNumber', $seasonNumber, SQLITE3_TEXT);
        $episodeStmt->bindValue(':episodeNumber', $episodeNumber, SQLITE3_TEXT);
        $episodeStmt->bindValue(':episodeName', $episodeName, SQLITE3_TEXT);

        move_uploaded_file($_FILES['episodeFile']['tmp_name'][$key], $baseDirectory . '/' . $episodeFileTarget);

        $episodeStmt->bindValue(':videoSource', $episodeFileTarget, SQLITE3_TEXT);

        // Execute the SQL statement for inserting episode data
        $episodeStmt->execute();
    }
}
 
     $episodeStmt->close();

    // Prepare the SQL statement for inserting data into the subtitles table
    $subtitleStmt = $database->prepare('INSERT INTO subtitles (series_id, season_number, episode_number, subtitle_source) VALUES (:seriesId, :seasonNumber, :episodeNumber, :subtitleSource)');

    // Insert multiple subtitles into the database
    if (is_array($subtitles)) {
        foreach ($subtitles as $key => $subtitle) {
            $seasonNumber = $_POST['seasonNumber'][$key];
            $episodeNumber = $_POST['episodeNumber'][$key];

            // Move uploaded subtitle file to the appropriate folder
            $subtitleExtension = pathinfo($_FILES['subtitles']['name'][$key], PATHINFO_EXTENSION);
            $subtitleFileName = "S{$seasonNumber}E{$episodeNumber}-" . sanitizeFilename($title) . '.' . $subtitleExtension;

            if ($type === 'series') {
                $subtitleFileTarget = './Subtitles/' . $subtitleFileName;
            } else if ($type === 'movie') {
                $subtitleFileTarget = './Moviesub/' . $subtitleFileName;
            }

            move_uploaded_file($_FILES['subtitles']['tmp_name'][$key], $baseDirectory . '/' . $subtitleFileTarget);

            $subtitleStmt->bindValue(':seriesId', $seriesId, SQLITE3_INTEGER);
            $subtitleStmt->bindValue(':seasonNumber', $seasonNumber, SQLITE3_TEXT);
            $subtitleStmt->bindValue(':episodeNumber', $episodeNumber, SQLITE3_TEXT);
            $subtitleStmt->bindValue(':subtitleSource', $subtitleFileTarget, SQLITE3_TEXT);

            // Execute the SQL statement for inserting subtitle data
            $subtitleStmt->execute();
        }
    }

    $subtitleStmt->close();
    $database->close();

// Generate the PHP file for the uploaded series or movie
$phpFilePath = $baseDirectory . '/' . $phpFileLocation;

// Check if the PHP file already exists
if (!file_exists($phpFilePath)) {
    if ($type === 'movie') {
        // Generate the PHP file for the uploaded movie
        $phpContent = <<<PHP
<?php
\$baseDirectory = __DIR__;
include \$baseDirectory . '/db_connect.php';

// Create and open the SQLite database connection
\$database = new SQLite3(\$baseDirectory . '/movies-series.db');

// Get the series data from the database
\$title = '$title';

\$stmt = \$database->prepare('SELECT * FROM movies_series WHERE title = :title');
\$stmt->bindValue(':title', \$title, SQLITE3_TEXT);
\$result = \$stmt->execute();

\$seriesData = \$result->fetchArray(SQLITE3_ASSOC);
\$stmt->close();

if (!\$seriesData) {
    die('Series Not Found');
}

\$title = \$seriesData['title'];
\$summary = \$seriesData['summary'];
\$poster = \$seriesData['poster'];
\$backgroundImg = \$seriesData['background_img'];
\$type = \$seriesData['type'];

// Retrieve the video source from the episodes table
\$stmt = \$database->prepare('SELECT video_source FROM episodes WHERE series_id = :series_id LIMIT 1');
\$stmt->bindValue(':series_id', \$seriesData['series_id'], SQLITE3_INTEGER);
\$result = \$stmt->execute();

\$episodeData = \$result->fetchArray(SQLITE3_ASSOC);
\$stmt->close();

\$videoSource = \$episodeData['video_source'];

// Include the movies.php file and pass the necessary variables
include \$baseDirectory . '/movies.php';
?>
PHP;
    } else {
        // Generate the PHP file for the uploaded series
        $phpContent = <<<PHP
<?php
\$baseDirectory = __DIR__;
include \$baseDirectory . '/db_connect.php';

// Create and open the SQLite database connection
\$database = new SQLite3(\$baseDirectory . '/movies-series.db');

// Get the series data from the database
\$title = '$title';

\$stmt = \$database->prepare('SELECT * FROM movies_series WHERE title = :title');
\$stmt->bindValue(':title', \$title, SQLITE3_TEXT);
\$result = \$stmt->execute();

\$seriesData = \$result->fetchArray(SQLITE3_ASSOC);
\$stmt->close();

if (!\$seriesData) {
    die('Series Not Found');
}

\$title = \$seriesData['title'];
\$summary = \$seriesData['summary'];
\$poster = \$seriesData['poster'];
\$backgroundImg = \$seriesData['background_img'];
\$type = \$seriesData['type'];

// Get the seasons for the specified series from the database
\$stmt = \$database->prepare('SELECT DISTINCT season_number FROM episodes WHERE series_id = :seriesId');
\$stmt->bindValue(':seriesId', \$seriesData['series_id'], SQLITE3_INTEGER);
\$result = \$stmt->execute();

\$seasons = [];
while (\$row = \$result->fetchArray(SQLITE3_ASSOC)) {
    \$seasons[] = \$row['season_number'];
}
\$stmt->close();

// Set the season number to the first season if available
\$seasonNumber = !empty(\$seasons) ? \$seasons[0] : '';

// Get the episodes for the specified season from the database
\$stmt = \$database->prepare('SELECT * FROM episodes WHERE series_id = :seriesId AND season_number = :seasonNumber');
\$stmt->bindValue(':seriesId', \$seriesData['series_id'], SQLITE3_INTEGER);
\$stmt->bindValue(':seasonNumber', \$seasonNumber, SQLITE3_TEXT);
\$result = \$stmt->execute();

\$episodes = [];
while (\$row = \$result->fetchArray(SQLITE3_ASSOC)) {
    \$episodes[] = [
        'episodeNumber' => \$row['episode_number'],
        'episodeName' => \$row['episode_name'],
        'videoSource' => \$row['video_source']
    ];
}
\$stmt->close();

// Include the series.php file and pass the necessary variables
include \$baseDirectory . '/series.php';
?>
PHP;
    }

    $phpFileName = sanitizeFilename($phpFileLocation); // Sanitize the PHP filename
    $phpFilePath = $baseDirectory . '/' . $phpFileName;
    
    // Create the PHP file
    file_put_contents($phpFilePath, $phpContent);
    
}

if ($type === 'movie') {
    // Redirect the user to the newly generated PHP file for movies
    header('Location: ' . $phpFileLocation);
    exit;
} else {
    // Redirect the user to the newly generated PHP file for series
    header('Location: ' . $phpFileLocation);
    exit;
}
}
?>