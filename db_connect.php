
<?php
$databaseFile = __DIR__ . '/movies-series.db';

// Check if the database file exists
if (!file_exists($databaseFile)) {
    die('Database file not found.');
}

// Create a new SQLite3 database connection
$database = new SQLite3($databaseFile);

// Check if the connection was successful
if (!$database) {
    die('Failed to connect to the database.');
}
?>