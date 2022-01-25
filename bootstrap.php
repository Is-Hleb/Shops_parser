<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$paths = [__DIR__ . '/App/Models/'];
$isDevMode = true;

// the connection configuration
$dbParams = require_once 'config/db-config.php';

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$connection = \Doctrine\DBAL\DriverManager::getConnection($dbParams);


// obtaining the entity manager
$entityManager = EntityManager::create($connection, $config);
