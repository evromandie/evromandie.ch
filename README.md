# Site EV Romandie

Code source du site https://evromandie.ch/

Les pages sont au format Markdown.

Les données des différentes pages d'information sont au format Yaml et se trouvent dans le dossier `_data`.

## Mise à jour YouTube

Un script permet de mettre à jour les statistiques des chaînes et télécharge leur avatar.
Il corrige également le format et l'ordre des chaînes dans le fichier de données.

Installer les dépendances Composer dans le dossier `scripts` puis exécuter:

    php -f scripts/youtube.php 
