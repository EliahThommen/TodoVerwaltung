<?php
$host = 'localhost';
$database = 'm151_database';
$db_username = 'root';
$db_password = '';

// mit datenbank verbinden
$conn = new mysqli($host, $db_username, $db_password, $database);

// fehlermeldung, falls die Verbindung fehl schlägt.
if ($conn->connect_error) {
    die('Connect Error' . $conn->connect_error);
}
