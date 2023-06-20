<?php
// SQLite database connection
$db = new SQLite3('movies-series.db');

// Get the table name from the URL parameter
$table = $_GET['table'];

// Query the table and fetch all rows
$result = $db->query("SELECT * FROM $table");
$data = array();
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  $data[] = $row;
}

// Return the data as JSON
echo json_encode($data);
