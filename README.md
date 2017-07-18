# ORessource

Système libre et adaptable de quantification et de bilan écologique pour ressourcerie écrit à l'aide de [PHP](https://secure.php.net/) compatible avec les versions avec 7.0 et superieures et 5.8, JavaScript (Ecmascript 6), [Bootstrap](http://getbootstrap.com/) et utilisant [MariaDB](https://mariadb.org/) ou [MySQL](https://www.mysql.com/) comme système de bases de données.

ORessource neccessite un navigateur Web à jour tel que Firefox ou Chromium.

Internet Explorer ou Opera ne sont pas supportés à ce jour.

## Installation

Voir le fichier <INSTALL.md>

## Mise à jour / Upgrade

Voir le fichier <UPGRADE.md>

## Sauvegarde et Restauration

Voir le fichier <SAUVEGARDE.md>

## Developement / Contribution

Si vous souhaitez develloper ou debugger Oressource pensez à modifier le bon fichier de configuration de PHP (`php.ini`) afin de pouvoir traquer les erreurs plus facilement.

Il est recommandé d'installer [xdebug](https://xdebug.org/) et d'utiliser un navigateur web en version (beta ou nightly) pour avoir de meilleurs outils de devellopement web.

Tel que Firefox Develloper Edition: <https://www.mozilla.org/en-US/firefox/developer/> Ou bien chromium ou chrome canary.

Idealement essayez d'utiliser un editeur ou un outil de verification syntaxique avant de soumettre une pull-request tel que [eslint](http://eslint.org/) pour Javascript ou bien pour php `php -l votre_fichier.php`.

Pour dévellopper vous pouvez utiliser le serveur interne de php7 via la commande:

```shell
cd oressource
php -S localhost:8080
```

Vous retrouverez Oressource dans votre navigateur en local sur le port 8080.

N'hesitez pas a ouvrir une issue pour toute question!

## Licence

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
