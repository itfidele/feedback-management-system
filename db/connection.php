<?php
require_once __DIR__ . '/vendor/autoload.php';

// Database configuration
$host = 'localhost'; // Replace with your host name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$database = 'megatrust'; // Replace with your database name

$conn = new \PDO('mysql:dbname='.$database.';host='.$host.';charset=utf8mb4', $username, $password) or die("Error connecting to database");

$config = new \PHPAuth\Config($dbh);
$auth   = new \PHPAuth\Auth($dbh, $config);

//phpinfo();
