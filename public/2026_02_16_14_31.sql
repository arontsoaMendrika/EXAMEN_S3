-- Insertion des données d'exemple

-- Régions
INSERT INTO region (nom) VALUES ('Antananarivo');
INSERT INTO region (nom) VALUES ('Toamasina');
INSERT INTO region (nom) VALUES ('Antsiranana');
INSERT INTO region (nom) VALUES ('Mahajanga');
INSERT INTO region (nom) VALUES ('Toliara');

-- Villes
INSERT INTO ville (nom, region_id) VALUES ('Antananarivo', 1);
INSERT INTO ville (nom, region_id) VALUES ('Toamasina', 2);
INSERT INTO ville (nom, region_id) VALUES ('Antsiranana', 3);
INSERT INTO ville (nom, region_id) VALUES ('Mahajanga', 4);
INSERT INTO ville (nom, region_id) VALUES ('Toliara', 5);
INSERT INTO ville (nom, region_id) VALUES ('Fianarantsoa', 1);
INSERT INTO ville (nom, region_id) VALUES ('Antsirabe', 1);

-- Besoins
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Riz', 'Alimentation', 5000.00, 100);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Eau potable', 'Hydratation', 2000.00, 200);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Médicaments', 'Santé', 15000.00, 50);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Tentes', 'Abri', 25000.00, 30);
INSERT INTO besoins (nom, type_besoin, prix, quantite) VALUES ('Vêtements', 'Vêtements', 8000.00, 150);

-- Sinistres (liens entre villes et besoins)
INSERT INTO sinistres (ville_id, besoin_id) VALUES (1, 1); -- Antananarivo a besoin de Riz
INSERT INTO sinistres (ville_id, besoin_id) VALUES (1, 2); -- Antananarivo a besoin d'Eau
INSERT INTO sinistres (ville_id, besoin_id) VALUES (2, 3); -- Toamasina a besoin de Médicaments
INSERT INTO sinistres (ville_id, besoin_id) VALUES (3, 4); -- Antsiranana a besoin de Tentes
INSERT INTO sinistres (ville_id, besoin_id) VALUES (4, 5); -- Mahajanga a besoin de Vêtements
INSERT INTO sinistres (ville_id, besoin_id) VALUES (5, 1); -- Toliara a besoin de Riz

-- Dons
INSERT INTO dons (nom, montant, date_don) VALUES ('Donateur 1', 10000.00, '2026-02-01');
INSERT INTO dons (nom, montant, date_don) VALUES ('ONG Internationale', 50000.00, '2026-02-05');
INSERT INTO dons (nom, montant, date_don) VALUES ('Entreprise Locale', 25000.00, '2026-02-10');
INSERT INTO dons (nom, montant, date_don) VALUES ('Individu Anonyme', 5000.00, '2026-02-12');