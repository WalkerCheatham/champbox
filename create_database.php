<?php
// Specify the path to the database file
$databaseFile = 'movie-series.db';

// Create a new SQLite database
try {
    $db = new SQLite3($databaseFile);
    echo "Database created successfully!";
} catch (Exception $e) {
    echo "Error creating database: " . $e->getMessage();
}
?>
