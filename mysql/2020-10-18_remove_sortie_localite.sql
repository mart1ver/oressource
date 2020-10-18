/**
 * Author:  darnuria
 * Created: Dec 18, 2020
 *
 * Retire une contrainte toute buggée des sorties.
 * Si il y avait pas de localité ça crisais.
 */

-- On drop
ALTER TABLE sorties DROP FOREIGN KEY FK_Sorties_Localites;
-- Recree la bonne contrainte sur la bonne table...
alter table sorties add CONSTRAINT FK_Sorties_PointSorties
foreign key (id_point_sortie) references points_sortie(id);
