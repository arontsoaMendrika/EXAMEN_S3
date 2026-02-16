CREATE TABLE achats(
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT,
    don_id INT,
    montant DECIMAL(10, 2) NOT NULL,
    date_achat DATE NOT NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id),
    FOREIGN KEY (don_id) REFERENCES dons(id)
);