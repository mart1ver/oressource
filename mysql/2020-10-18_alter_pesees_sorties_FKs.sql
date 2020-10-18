/**
 * Author:  darnuria
 * Created: Dec 18, 2020
 *
 * Bon c'était pas top top cette contrainte, façon le schema il est pas terrible.
 * Il en faut au moins un parmis les trois vu le schema c'est trop complexe,
 * pour faire ça vite.
 */

ALTER TABLE oressource.pesees_sorties DROP FOREIGN KEY FK_PeseesSorties_TypeDechets;
ALTER TABLE oressource.pesees_sorties DROP FOREIGN KEY FK_PeseesSorties_TypeDechetEvac;
ALTER TABLE oressource.pesees_sorties DROP FOREIGN KEY FK_PeseesSorties_TypePoubelles;
