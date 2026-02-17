-- Ajouts pour gestion des statuts sur table `besoin` et ajustements
-- 1) Ajouter colonne `statut` sur `besoin` (valeurs possibles: 'en_attente', 'comble')
ALTER TABLE besoins
ADD COLUMN statut VARCHAR(32) NOT NULL DEFAULT 'en_attente';

-- 2) Exemple: mettre à jour les besoins déjà totalement couverts
UPDATE besoins b
LEFT JOIN (
  SELECT besoin_id, COALESCE(SUM(montant),0) AS mont_total
  FROM achats
  GROUP BY besoin_id
) a ON a.besoin_id = b.id
SET b.statut = CASE WHEN COALESCE(a.mont_total,0) >= (b.prix * b.quantite) THEN 'comble' ELSE 'en_attente' END;

-- 3) Ajout d'un index pour accélérer les requêtes de récapitulatif
CREATE INDEX idx_besoins_statut ON besoins(statut);

-- NOTES:
-- Après exécution, le code PHP utilise maintenant la colonne `statut` et
-- renvoie ce champ dans la simulation et la distribution. Si vous avez
-- une sauvegarde de la base, exécutez ces commandes dans votre environnement
-- de développement ou production avec précaution.

-- 4) Ajouter colonne `ordre` sur `dons` pour choisir la stratégie liée au don
ALTER TABLE dons
ADD COLUMN ordre VARCHAR(32) NOT NULL DEFAULT 'priority';

-- Mettre à jour les dons existants pour avoir la valeur par défaut
UPDATE dons SET ordre = 'priority' WHERE ordre IS NULL OR ordre = '';
