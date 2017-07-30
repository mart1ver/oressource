#! /usr/bin/bash -x

## Mise à jour d'Oressource

sudo su www-data
cd /var/www/oressource
timestamp=`git log -1 --format="%ct"`
git pull orgin master


## Si Oressource a été installé avant le 12 juin 2015

if [ $timestamp -lt 1434107616 ]
then
  # Mise à jour de la base de données :
  cat mysql/upgrade_20150612.sql | mysql -u oressource -h localhost oressource -p
fi


## Installation des dépendances du client

sudo apt-get install npm
npm install


## Génération d'une version de production des fichiers client

npm run build
cd -
