# Upgrade 

## Si par exemple vous avez installé Oressource avant le 12 juin 2015

Mettez à jour votre base de données avec la commande suivante :

``
cat mysql/upgrade_20150612.sql |mysql -u oressource -h localhost oressource -p
``

Répétez éventuellement (selon la date de votre dernière installation) l'opération avec les autres fichiers upgrade_*.sql.

