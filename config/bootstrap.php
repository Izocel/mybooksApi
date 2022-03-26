<?php

use DI\ContainerBuilder;
use Slim\App;

// Injection des dépendences tierces
require_once __DIR__ . '/../vendor/autoload.php';
// Chargement des variables d'environnement
require_once __DIR__ . '/env.php'; // Ligne à ajouter

// Classe d'aide à la construction d'un conteneur d'application
$containerBuilder = new ContainerBuilder();

// Configuration des paramêtres pour le constructeur de conteneur
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

//Construit une instance du conteneur PHP-DI(injection de dépendance PHP)
$container = $containerBuilder->build();

// Construit l'instance aplicative
$api = $container->get(App::class);

// Enregistre les routes dans l'application
(require __DIR__ . '/routes.php')($api);

// Enregistre les 'middlewares dans l'application
(require __DIR__ . '/middleware.php')($api);

return $api;
