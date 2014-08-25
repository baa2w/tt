<?php
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

define('ROOT_DIR', dirname(__DIR__));
define('DATA_DIR', ROOT_DIR . '/data');

// ensure that data dir exists and is writable
if (!is_writable(DATA_DIR)) {
    die('<html><body><h2>Configuration Error:</h2>Data dir must be writable.</body></html>');
}

// autoload vendor classes
require ROOT_DIR . '/vendor/autoload.php';

// set default timezone to suppress php warnings
date_default_timezone_set(@date_default_timezone_get());

// tweak session expiration time
if (!session_id()) {
    ini_set('gc_probability', 1);
    ini_set('gc_divisor',     1);
    ini_set('gc_maxlifetime', 60*60*24);  // 1 day
}

// create db file if not exist
$dbFile = DATA_DIR . '/vitta.db';
!file_exists($dbFile) && touch($dbFile);

// set db connection
$config           = new Configuration;
$connectionParams = array(
    'path'     => $dbFile,
    'driver'   => 'pdo_sqlite',
);
$db = DriverManager::getConnection($connectionParams, $config);

// explicitly enable foreign keys
$db->executeQuery("PRAGMA foreign_keys = ON;");

// unset no-longer needed variables
unset($dbFile, $config, $connectionParams);
