<?php 

// Constante du mode de l'application
// dev : variables utilisées en local
// prod : pour le déploiement de l'api en production
define("MODE", "dev");

switch (MODE) {
    case "dev":
        // Configuration BD en local
        $_ENV['host'] = 'localhost';
        $_ENV['username'] = 'root';
        $_ENV['database'] = 'libapi';
        $_ENV['password'] = 'mysql';
        break;

    case "prod":
        // Configuration BD pour Heroku
        $_ENV['host'] = 'us-cdbr-east-05.cleardb.net';
        $_ENV['username'] = 'b4692d61f95260';
        $_ENV['database'] = 'heroku_330c927d2db0d16';
        $_ENV['password'] = '06aee1da';
        break;
};
