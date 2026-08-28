# ORessource

Système libre et adaptable de quantification et de bilan écologique pour
ressourcerie écrit à l'aide de [PHP](https://secure.php.net/) compatible avec
les versions avec 7.0 et superieures, JavaScript (Ecmascript 6),
[Bootstrap](http://getbootstrap.com/) et utilisant
[MariaDB](https://mariadb.org/) ou [MySQL](https://www.mysql.com/) comme système
de bases de données.

ORessource neccessite un navigateur Web à jour tel que Firefox ou Chromium.

Internet Explorer ou Opera ne sont pas supportés à ce jour.

## Historique et état actuel du projet

ORessource est né fin 2013 d'un besoin très concret : permettre à une
ressourcerie de suivre ses flux avec le même niveau de traçabilité que les
grands acteurs du recyclage, sans dépendre d'un logiciel propriétaire payant
au poste. Le premier commit date du 25 mai 2014.

Le projet a connu plusieurs années de développement intense, avec l'aide
précieuse d'Axel Viala ([darnuria](https://github.com/darnuria)), qui a
largement contribué à faire évoluer et à revitaliser le code, ainsi que de
nombreux autres contributeurs (voir plus bas).

**Contrairement à ce que ce README a pu laisser penser un temps, ORessource
n'est pas un projet à l'arrêt : il est toujours activement maintenu et
exploité en production dans plusieurs ressourceries.**

Depuis 2025, la [Coopérative 24ème](https://24eme.fr/) — spécialisée dans le
logiciel libre pour les acteurs de l'économie sociale — développe et héberge
activement ORessource sur [oressource.org](https://oressource.org/), à
l'initiative de la ressourcerie La Brigaille (Marseille). Ils proposent
notamment :

- de l'hébergement d'instances clé en main, à prix libre, pour les
  ressourceries qui ne veulent pas s'auto-héberger ;
- un développement actif du logiciel, dont une grande partie des évolutions
  sont reversées dans ce dépôt.

👉 Pour un hébergement géré, un accompagnement, ou pour contribuer au système
de génération d'instances, rendez-vous sur
[oressource.org](https://oressource.org/) et sur leur dépôt
[24eme/ORessource_generator](https://github.com/24eme/ORessource_generator).

Ce dépôt (`mart1ver/oressource`) reste le dépôt d'origine du cœur applicatif
d'ORessource, et continue d'être maintenu.

## Installation

Voir le fichier [INSTALL.md](INSTALL.md)

## Mise à jour / Upgrade

Voir le fichier [UPGRADE.md](UPGRADE.md)

## Sauvegarde et Restauration

Voir le fichier [SAUVEGARDE.md](SAUVEGARDE.md)

## Contributeurs

Merci à toutes les personnes qui ont fait vivre ce projet depuis 2014 :

- **Martin Vert** ([mart1ver](https://github.com/mart1ver)) — création et
  maintenance du projet depuis 2014
- **Axel Viala** ([darnuria](https://github.com/darnuria))
- **La Coopérative 24ème** — notamment tale-fau, Tangui Morlier et Vincent
  Laurent — développement et hébergement actifs depuis 2025
  ([oressource.org](https://oressource.org/))
- **David Delon** — adaptations pour la ressourcerie Courts-Circuits
  (Saint-Martin-de-Londres)
- damien clochard
- Yvan Sraka
- Vincent Texier
- gtrouve (VonNeuman)
- Sebastien Da Rocha
- Aurelia Dolo
- Adrien Kara
- jlinux
- Epic
- Ouari

Merci également à [olive](https://doc.ubuntu-fr.org/utilisateurs/olive), de
la communauté ubuntu-fr, dont les explications ont inspiré le projet à ses
débuts.

La liste complète et à jour des contributions se trouve dans l'
[historique Git](https://github.com/mart1ver/oressource/graphs/contributors).

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
