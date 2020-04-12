
BEGIN;

INSERT INTO `utilisateurs` (`niveau`, `nom`, `prenom`, `mail`, `pass`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) 
VALUES ('c1c2c3v1v2v3s1bighljk', 'admin', 'admin', 'admin@admin', MD5('admin'), 0, 0, '0000-00-00 00:00:00');


COMMIT;
