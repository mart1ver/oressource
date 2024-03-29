# ORessource

# Ce dépot n'est plus maintenu 

Aprés 10 ans d'éxistence, Oressource se doit d'étre réécrit, en effet les choix techchnologiques initiaux ( php, bootstrap et peut etre aussi mysql... ) s'averrent de moins en moins pertinents au regard de l'utilité rééle du logiciel.

C'est en tant que résponsable d'un humble atelier d'une ressourcerie et qu'amateur total que fin 2013 je me posait cette question : 
Comment, en termes de traçabilité, atteindre au moins le niveau de professionalisme des grands acteurs du recyclage? 
Nous avions besoin d'un logiciel, il en existait un... : payant au nombre de poste, source fermée, technologie windev... 
Merci à [olive de ubuntu-fr](https://doc.ubuntu-fr.org/utilisateurs/olive) que je croisait de temps en temps , et qui un soir m'a conseillé de me lancer : coder c'est avant tout se documenter, et puis on commence simple... , on voit bien ce que ca donne...
Oressource n'est pas resté notre outil interne bien longtemps car certains de nos collegues se retrouvaient confrontés aux memes enjeux qu nous finalement : produire un bilan écologique fiable , précis et en temps réél sans passer par des procedures trop pompeuses.

Depuis le temps est passé de nombreuses àmeliorations et correctifs ont étés apportés. Merci principalement à [darnuria](https://github.com/darnuria) pour sa 'revitalisation' du code sur la fin, merci à tout ceux qui ont contribués à Oressource depuis .

 










Système libre et adaptable de quantification et de bilan écologique pour
ressourcerie écrit à l'aide de [PHP](https://secure.php.net/) compatible avec
les versions avec 7.0 et superieures, JavaScript (Ecmascript 6),
[Bootstrap](http://getbootstrap.com/) et utilisant
[MariaDB](https://mariadb.org/) ou [MySQL](https://www.mysql.com/) comme système
de bases de données.

ORessource neccessite un navigateur Web à jour tel que Firefox ou Chromium.

Internet Explorer ou Opera ne sont pas supportés à ce jour.

## Installation

Voir le fichier [INSTALL.md](INSTALL.md)

## Mise à jour / Upgrade

Voir le fichier [UPGRADE.md](UPGRADE.md)

## Sauvegarde et Restauration

Voir le fichier [SAUVEGARDE.md](SAUVEGARDE.md)

## Developement / Contribution

### Dependances logicielles

#### Client web

Des versions pas très a jour sont embarqué dans les sources du dépôt

- [Morris.js](https://morrisjs.github.io/morris.js/)
- Pour Morris.js [Raphael.js](https://github.com/DmitryBaranovskiy/raphael)
- [JQuery](https://jquery.com/)
- [bootstrap.js](https://getbootstrap.com/) de Bootstrap

> Note: Nous sommes conscient qu'une gestion des dépendances JavaScript modernes serait mieux.
> Voir issue [#394](https://github.com/mart1ver/oressource/issues/394).

### Développer

Si vous souhaitez develloper ou debugger Oressource pensez à modifier le bon
fichier de configuration de PHP (`php.ini`) afin de pouvoir traquer les erreurs
plus facilement.

Il est recommandé d'installer [xdebug](https://xdebug.org/) et d'utiliser un
navigateur web en version (beta ou nightly) pour avoir de meilleurs outils de
devellopement web.

Tel que Firefox Develloper Edition:
<https://www.mozilla.org/en-US/firefox/developer/> Ou bien chromium ou chrome
canary.

Idealement essayez d'utiliser un editeur ou un outil de verification syntaxique
avant de soumettre une pull-request tel que [eslint](http://eslint.org/) pour
Javascript ou bien pour php `php -l votre_fichier.php`.

Vous pouvez aussi installer le plugin [Editor Config](http://editorconfig.org)
afin que votre editeur se régle automatiquement sur les paramètres de style de code
du projet presents dans le fichier `.editorconfig`.

Pour dévellopper vous pouvez utiliser le serveur interne de `php7` via la
commande:

```shell
cd oressource
php -S localhost:8080
```

Vous retrouverez Oressource dans votre navigateur en local sur le port `8080`.

N'hesitez pas a ouvrir une issue pour toute question!

## Licence

Oressource est distribué sous les termes de la [License
AGPLv3](https://www.gnu.org/licenses/agpl.html).

Pour plus de détails vous reférer au fichier suivant [LICENSE](LICENSE.txt).

## Contributions

Toutes les contributions sont placées sous les termes de la [License
AGPLv3](https://www.gnu.org/licenses/agpl.html).

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
