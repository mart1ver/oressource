# Carnet de changement du projet

## Semantique des numéros de version

Le premier numéro indique des changements majeurs qui cassent par exemple l'api
ou des usages du logiciel
Le second numéro des changements moyen visible par le developpeur ou
l'utilisateur.
Le dernier des fix de bug.

## Changements

Dans ce fichier, nous tachons de tenir un historique humainement suivable des
changements apporté au logiciel.

### Version v0.3.0

Version comprenant principalement corrections de bugs sur la
gestion des impressions papier et leur formattage.

- Fix d'une erreur dans le script travis merci @HoverEpic PR #383.
- PR #341 : Ajout d'un script par @yvan-sraka pour vérifier des une mise a jour existe.
- #396 PR #402 : @AureliaDolo Ajouts d'informations dans le ticket de caisse
    dates, mentions legales, reformattage.
- Corrections de soucis d'importance mineures detecter par eslint
- Correction de fautes de styles javascript vu par eslint.
- Mise en place d'une configuration pour le verificateur syntaxique [eslint](https://eslint.org/)
- Début du changement de convention de nommage sur le javascript (conformité
    standard communautaire JS).
- Mise à jour massive de la documentation de vente.js format jsdoc.
- Mise à jour de la documentation de ticket.js format jsdoc.
- [#391](https://github.com/mart1ver/oressource/issues/391) Correction d'un
bug dans le calcul de TVA
- Ajout de la mention «HT» quand l'association n'est pas assujetie à la TVA,
reformatage de la mention avec TTC lorsque la structure est assujetie
- [#392](https://github.com/mart1ver/oressource/issues/392) Affichage des
quantitées dans les tickets de caisse papier
- [#393](https://github.com/mart1ver/oressource/issues/393) Tenir un carnet
des changements apporté au logiciel


### version 0.2.0 et antérieur

Très grosse refonte générale, changement sur la base de donnée, corrections de
bugs.

