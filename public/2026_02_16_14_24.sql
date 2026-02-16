CREATE DATABASE cyclone;
USE cyclone;

CREATE TABLE region(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE ville(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES region(id)
);


CREATE TABLE besoins(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    type_besoin VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    quantite INT NOT NULL
);


CREATE TABLE sinistres(
    id INT PRIMARY KEY AUTO_INCREMENT,
    ville_id INT,
    besoin_id INT,
    FOREIGN KEY (ville_id) REFERENCES ville(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE dons(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_don DATE NOT NULL
);