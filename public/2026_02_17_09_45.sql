
-- Regions
INSERT INTO region (nom) VALUES ('Region Test');

-- Villes
INSERT INTO ville (nom, region_id) VALUES ('Ville A', 1);
INSERT INTO ville (nom, region_id) VALUES ('Ville B', 1);
INSERT INTO ville (nom, region_id) VALUES ('Ville C', 1);

-- Besoins (type_besoin: nature, materiaux, argent)
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Riz', 'nature', 5000.00, 100);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Huile', 'nature', 7000.00, 50);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Tôle', 'materiaux', 15000.00, 20);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Clou (kg)', 'materiaux', 2000.00, 100);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Aide financière', 'argent', 10000.00, 30);

-- Lier sinistres (villes -> besoins)
-- Ville A a besoin de Riz et Tôle
INSERT INTO sinistres (ville_id, besoin_id) VALUES (1, 1);
INSERT INTO sinistres (ville_id, besoin_id) VALUES (1, 3);
-- Ville B a besoin d'Huile et Clou
INSERT INTO sinistres (ville_id, besoin_id) VALUES (2, 2);
INSERT INTO sinistres (ville_id, besoin_id) VALUES (2, 4);
-- Ville C a besoin d'Aide financière
INSERT INTO sinistres (ville_id, besoin_id) VALUES (3, 5);

-- Dons: dates et montants pour tester l'ordre (date plus ancienne = utilisé en premier)
INSERT INTO dons (nom, montant, date_don) VALUES ('Donateur ancien', 30000.00, '2026-01-01');
INSERT INTO dons (nom, montant, date_don) VALUES ('Don récent petit', 5000.00, '2026-02-10');
INSERT INTO dons (nom, montant, date_don) VALUES ('ONG majeure', 80000.00, '2026-02-05');

-- (Optionnel) achats initiales : vide pour tester la distribution propre
-- INSERT INTO achats (besoin_id, don_id, montant, date_achat) VALUES (1, 1, 10000.00, '2026-02-12');

-- Fin du fichier test_inserts.sql
