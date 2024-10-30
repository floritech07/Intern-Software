![is1](https://github.com/user-attachments/assets/c12332d2-6788-433d-869e-2aeb79d00fd7)
# SBEE Intern Software Management

Ce projet est une application web pour gérer les logiciels internes de la Société Béninoise d'Énergie Électrique (SBEE). Les utilisateurs peuvent ajouter des logiciels, fournir des descriptions, des liens, et des images pour chaque logiciel.

## Fonctionnalités

- **Gestion des logiciels** : Ajout de logiciels avec leur nom, description, image et lien de téléchargement.
- **Téléchargement d'image** : Permet de télécharger une image associée à chaque logiciel.
- **Vérification de l'authentification** : Seuls les utilisateurs connectés peuvent ajouter des logiciels.
- **Formulaire dynamique** : Le formulaire d'ajout utilise `fetch` pour gérer les soumissions sans rechargement de la page.

## Prérequis

- PHP (>=7.4)
- Serveur Web (Apache, Nginx, etc.)
- Base de données MySQL
- [Composer](https://getcomposer.org/) pour la gestion des dépendances PHP

## Installation

1. **Clonez le dépôt** :
   ```
   git clone https://github.com/floritech07/Intern-Software.git
   cd Intern-Software
    ```![is1](https://github.com/user-attachments/assets/67e1b7f7-fa52-44ec-af28-14e722143cb0)


2- Configuration de la base de données:

Créez une base de données pour le projet. "software.sb"
Importez le fichier SQL : software.sb.sql
Configurez la connexion à la base de données dans db-conn.php en remplaçant les valeurs par celles de votre environnement.

 ```
$host = 'Adresse Hote';
$db = 'software.sb ou votre base de données';
$user = 'Nom d'utilisateur';
$pass = 'Mot de passe ';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
 ```

3- Installer les dépendances avec Composer :

Si ton projet a des dépendances PHP, exécute la commande suivante dans le terminal pour les installer :
  ```
composer install
 ```

4 - Configurer les permissions :

Assurez-vous que le dossier uploads/ a les permissions d’écriture, car c’est là que les images seront stockées.
Exécutez cette commande si nécessaire :


chmod -R 755 uploads/ 


 5 - Lance : 
  ```
 localhost/Intern-Software/index.php 
 pour tester l'application .
 ```


