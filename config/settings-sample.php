<?php

// Rapport d'erreur en version production
error_reporting(0);
ini_set('display_errors', '0');

// Fuseau horraire
date_default_timezone_set('America/Toronto');

// Array des paramêtres
$settings = [];

// Chemin d'accès
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

// Middleware pour les erreurs
$settings['error'] = [

    // Devrait être à 'false' en production
    'display_error_details' => true,

    // Seront gérée par le gestionnaire d'erreur par défault
    'log_errors' => true,
    'log_error_details' => true,
];

// Paramêtres pour la connection LGBD
$settings['db'] = [
    'driver' => 'mysql',
    'host' => $_ENV['host'],
    'username' => $_ENV['username'],
    'database' => $_ENV['database'],
    'password' => $_ENV['password'],
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci'
    ],
];

// Configuration d'un logger par défault
$settings['logger'] = [
    'name' => 'app',
    'path' => __DIR__ . '/../logs',
    'filename' => 'app.log',
    'level' => \Monolog\Logger::DEBUG,
    'file_permission' => 0775,
];

// Twig settings
$settings['twig'] = [
    // Template paths
    'paths' => [
        __DIR__ . '/../templates',
    ],
    // Twig environment options
    'options' => [
        // Should be set to true in production
        'cache_enabled' => false,
        'cache_path' => __DIR__ . '/../tmp/twig',
    ],
];

return $settings;
