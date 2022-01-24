<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$paths = [__DIR__ . '/App/Models/'];
$isDevMode = true;

// the connection configuration
$dbParams = array(
    'dbname' => 'parser',
    'user' => 'root',
    'password' => 'root',
    'host' => 'localhost',
    'port' => 8889,
    'driver' => 'pdo_mysql',
    'unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$connection = \Doctrine\DBAL\DriverManager::getConnection($dbParams);


// obtaining the entity manager
$entityManager = EntityManager::create($connection, $config);
