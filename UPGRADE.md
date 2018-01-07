# Upgrade

## Installation d'avant le 15 janvier 2018 (version 0.2.0)

Attention! De nombreux changements on eu lieu sur la base de donnée.
Il faut charger les scripts SQL suivants:

```
2017-12-18_drop_adherents.sql
2017-12-23_pesee_vendus.sql
2017-12-24_conformite_strict_SQL.sql
2017-12-24_ventes_par_lot.sql
2017-12-29_remove_oui_non.sql
2017-12-30_ajout_clef_etrangeres.sql
```

## Si vous avez installé Oressource avant le 12 juin 2015

Mettez à jour votre base de données avec la commande suivante :

```shell
 mysql -u oressource -h localhost oressource -p < mysql/upgrade_20150612.sql
```
