# Sauvegarde et Restauration

## 1- Sauvegarder la base de données

**Avec PhpMyAdmin** :

La manière la plus **simple** de sauvegarder la base est d'utiliser
`phpmyadmin`.

Pour cela, il faut l'installer avec la commande `apt-get install phpmyadmin`

Une fois installé, on se connecte sur phpmyadmin et on suit la procédure
suivante:

- cliquer sur la base oressource
- cliquer ensuite l'onglet "exporter".
- une fois la page d'export, cliquer sur "executer"

ça téléchargera un fichier SQL qui contient toutes les données.

**Avec la console** :

La manière la plus **automatisé** de sauvegarder la base, c'est d'ouvrir la
console et lancer la commande:

```shell
mysqldump -h localhost -u oressource -p XXX oressource > /tmp/sauvegarde_oressource.sql
```

(en remplaçant XXX par le vrai mot de passe de la base de données, voir dans le
fichier `moteur/dbconfig.php` si vous ne vous souvenez plus de mot de passe)

Ensuite il faut récupérer le fichier sauvegarde_oressource.sql dans le dossier
/tmp et le stocker en dehors du serveur Oressource. Par exemple sur une clé USB
qu'on va ranger dans un endroit sûr ou sur un autre ordinateur qui n'est pas
dans la même pièce que la caisse Oressource. C'est important car en cas de vol
ou de crash du PC, la sauvegarde sera à l'abri.

La commande `mysqldump` est completement inoffensive :-) C'est un outil de
sauvegarde ("dump") et ça exporte simplement la base dans un fichier texte
(format SQL) sans modifier les données. Si l'outil n'est pas installé, on peut
la télécharger avec `apt-get install mysql-client`

## 2- Sauvegarder les fichiers

En théorie : sauvegarder la base de données est suffisant pour reconstruire une
nouvelle caisse.

En pratique : il est conseillé de sauvegarder en même temps tous les fichiers
qui se trouvent le dossier `/var/www/oressource` car ça peut faire gagner
beaucoup de temps au moment de restaurer une vieille sauvegarde

## 3- Automatiser les sauvegardes

On peut faire une sauvegarde automatique et régulière (par exemple une fois par
jour) avec l'outil `cron`. Si la caisse oressource est éteinte tous les jours il
est préférable d'utiliser `anacron` au lieu de `cron`. Le changement se fait
avec la commande suivante `apt-get install anacron`.

Pour automatiser, il faut créer un fichier `/etc/cron.daily/oressource` avec la
commande suivante :

```shell
sudo touch /etc/cron.daily/oressource
sudo chmod a+x /etc/cron.daily/oressource
```

Ensuite éditer le fichier et copier-coller le script suivant :

```shell
#! /bin/bash

# Remplacer XXX par le mot de passe de la base
mysqldump -h localhost -u oressource -p XXX oressource > /var/backups/sauvegarde_oressource.daily.sql

# Sauvegarde des fichiers
tar cvzf /var/backups/sauvegarde_oressource.daily.tgz /var/www/oressource
```

Vérifier ensuite que les sauvegardes se font quotidiennement dans le répertoire
`/var/backups` et n'oubliez pas d'exporter les sauvegardes sur un autre PC ou
sur un stockage externe (clé USB, DVD, serveur FTP, etc.)

## 4- Restaurer une sauvegarde

**Avec PhpMyAdmin** :

Dans phpmyadmin, il y a un onglet "importer" qui fait le chemin à l'envers,
c'est à dire que ça prend un fichier SQL en entrée et ça réinjecte les données
dans une base.

Voici la procédure conseillée pour recharger les données :

1- Renommer la base "oressource" existante en "oressource_inactive" 2- Créer une
nouvelle base "oressource" 3- Sélectionner la nouvelle base "oressource" 4-
Aller sur l'onglet "Importer" et uploader le fichier sauvegarde_oressource.sql

**Avec la console** :

```shell
mysql -h localhost -u oressource -p XXX oressource < /tmp/sauvegarde_oressource.sql
```
