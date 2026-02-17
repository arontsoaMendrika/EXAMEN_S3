-- Alterer la colonne type_besoin pour n'accepter que les valeurs autorisées
-- ATTENTION: exécuter en connaissance de cause. Sauvegardez la base avant.
USE cyclone;
ALTER TABLE besoins
    MODIFY COLUMN type_besoin ENUM('nature','materiaux','argent') NOT NULL;
