<?php
/** Database Configeration
 * 
 * Switch on output buffering
 * 
 * Switch on sessions
 * 
 * Set the timezone
 */

// Turn on output buffering (wait for all php code to execute before outputting it to the page)
ob_start();
// start the session
session_start();
// set timezone
date_default_timezone_set('Africa/Johannesburg');

// Database connection setup
// Dev setup
$devDatabaseName = 'mysql:dbname=basic_setup';
$devUser = 'BSAdmin';
$devPass = 't)A@apL8q5QRJra*';
// Prod setup
$prodDatabaseName = 'mysql:dbname=meliorat_basic_setup';
$prodUser = 'meliorat_BSAdmin';
$prodPass = 't)A@apL8q5QRJra*';

// Connect to the database
try {
    // Create database connection using PDO (PHP Database Object)
    $con = new PDO("$devDatabaseName; host=localhost", $devUser, $devPass); // Use the data as setup on the server
    
    // Switch on error mode to warning so that the script continue but warns of any errors
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
