# INSTALL

## Préparer le serveur

``
sudo apt-get install apache2 php5-mysql
``

## Créer un utilisateur

Se connecter au serveur MySQL

``
$ mysql
``

Créer l'utilisateur oressource

`` sql
CREATE USER 'oressource'@'localhost' IDENTIFIED BY 'mot_de_passe_a_changer';
GRANT ALL PRIVILEGES ON oressource.* TO 'oressource'@'localhost' ;
``

## Charger les données

``
cat mysql/oressource.sql  |mysql -u oressource -h localhost oressource -p
``

## Préparer le virtual host

```
sudo cp -pr oressource/ /var/www/oressource
sudo chown -R www-data.www-data /var/www/oressource
```

Editer le fichier de configuration 

``
cd /var/www/oressource/moteur/
sudo cp dbconfig.php.exemple dbconfig.php
sudo vi dbconfig.php
``


## Configurer le virtual host

Créer un fichier oressource.conf

``
sudo vi /etc/apache2/site-available/oressource.conf
``

Ajouter les lignes suivantes :

``
<VirtualHost *:80>
ServerName      oressource.example.com
DocumentRoot /var/www/oressource/
ErrorLog /var/log/apache2/oressource-error.log
CustomLog /var/log/apache2/oressource-access.log combined
</VirtualHost>
``

## Activer le virtual host

```
sudo a2ensite oressource
sudo apache2ctl graceful
```

