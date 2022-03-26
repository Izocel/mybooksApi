# base-slim-skeleton
Api de base avec le framework slim et une connexion MySQL.
Source : https://odan.github.io/2019/11/05/slim4-tutorial.html

### Installation
> composer update

### Setup de la BD
- Créez une base de données
- Modifier les informations de connexion dans le fichier config/settings.php
- Rouler le script ressource/createUserTable.sql dans la base de données crée pour tester la création d'un usager.

### Routes disponibles
| Méthodes | Route  | Description                      |
| -------- | ------ | -------------------------------- |
| GET      | /      | Message de bienvenue             |
| GET      | /docs  | Documentation de l'api           |
| POST     | /users | Création d'un usager             |

Pour la création d'un usager, ajouter dans le body de la requête l'information en JSON : 
```
{
    "username" : "[username]",
    "first_name" : "[first_name]",
    "last_name" : "[last_name]",
    "email" : "[email]"
}
```
