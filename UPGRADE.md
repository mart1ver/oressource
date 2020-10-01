# Upgrade


## Installation d'avant le 15 janvier 2018 (version 0.2.0)

### Récupération des utilisateurs supprimées

Ce script récupére les utilisateurs ayant été suprimmant en possédant encore des données.

```bash
cd mysql
php -f upgrade2018v0-2-0.php
```

## Si par exemple vous avez installé Oressource avant le 12 juin 2015

Mettez à jour votre base de données avec la commande suivante :

```shell
 mysql -u oressource -h localhost oressource -p < mysql/upgrade_20150612.sql
```

Répétez éventuellement (selon la date de votre dernière installation) l'opération avec les autres fichiers upgrade_*.sql.
