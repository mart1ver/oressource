ORessource
==========

Système libre et adaptable de quantification et de bilan écologique pour ressourcerie
écrit à l'aide de [PHP5](https://secure.php.net/), JavaScript, [Bootstrap](http://getbootstrap.com/).
et utilisant [MariaDB](https://mariadb.org/) ou [MySQL](https://www.mysql.com/) comme système de bases de données.

ORessource neccessite un navigateur Web à jour tel que Firefox ou Chromium.
Internet Explorer ou Opera ne sont pas supportés à ce jour.

# Installation

## Dépendances

`sudo apt-get install apache2 php5-mysql libapache2-mod-php5`

## MariaDB/MySQL
### Créer un utilisateur

Créer l'utilisateur oressource

```shell
mysql --user=root --host=localhost -e \
  'CREATE USER 'oressource'@'localhost' IDENTIFIED BY 'mot_de_passe_a_changer'; \
  GRANT ALL PRIVILEGES ON oressource.* TO 'oressource'@'localhost' ; \
  CREATE DATABASE oressource;'
```

### Charger les données

```bash
mysql --user=oressource --host=localhost --password=mot_de_passe_a_changer \
  --database=oressource < mysql/oressource.sql
```

## Apache
### Préparer le virtual host

```shell
sudo cp -pr oressource/ /var/www/oressource
sudo chown -R www-data.www-data /var/www/oressource
```

Editer le fichier de configuration

```shell
cd /var/www/oressource/moteur/
sudo cp dbconfig.php.exemple dbconfig.php
sudo vi dbconfig.php
```

### Configurer le virtual host

Créer un fichier oressource.conf

`sudo vi /etc/apache2/site-available/oressource.conf `

Ajouter les lignes suivantes :

```
<VirtualHost *:80>
    ServerName      oressource.example.com
    DocumentRoot /var/www/oressource/
    ErrorLog /var/log/apache2/oressource-error.log
    CustomLog /var/log/apache2/oressource-access.log combined
</VirtualHost>
```

### Activer le virtual host

```shell
sudo a2ensite oressource
sudo apache2ctl graceful
```
un petit redemarage du service apache2 semble oportun apres toute cette configuration
```shell
sudo service apache2 restart
``` 

# Mise à jour / Upgrade

Voir le fichier [UPGRADE.md]()

# Sauvegarde et Restauration

Voir le fichier [SAUVEGARDE.md]()


# Licence

Oressource est distribué sous les termes de la [License AGPLv3](https://www.gnu.org/licenses/agpl.html).

Pour plus de détails vous reférer au fichier suivant [LICENSE](LICENSE.txt).

## Contributions

Toutes les contributions sont placées sous les termes de la [License AGPLv3](https://www.gnu.org/licenses/agpl.html).

## Clause de non responsabilité
Comme indiqué en détail dans la [License AGPLv3](LICENSE.txt).
```
L'utilisation de ce Programme est libre et gratuite, aucune garantie
n'est fournie, comme le permet la loi. Sauf mention écrite, les détenteurs du
copyright et/ou les tiers fournissent le Programme en l'état, sans aucune sorte
de garantie explicite ou implicite, y compris les garanties de commercialisation
ou d'adaptation dans un but particulier. Vous assumez tous les risques quant à
la qualité et aux effets du Programme. Si le Programme est défectueux, Vous
assumez le coût de tous les services, corrections ou réparations nécessaires.
```
