<?php
// SQLite database connection
$db = new SQLite3('movies-series.db');

// Get the table name and ID from the URL parameters
$table = $_GET['table'];
$id = $_GET['id'];

// Delete the row from the specified table based on the ID
$db->exec("DELETE FROM $table WHERE series_id = $id");

echo 'Success';
?>