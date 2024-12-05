-- Création de la base de données
CREATE DATABASE IF NOT EXISTS burger_land;
USE burger_land;

-- Table : utilisateur
CREATE TABLE utilisateur (
    UTI_ID INT AUTO_INCREMENT PRIMARY KEY,
    UTI_NOM VARCHAR(255) NOT NULL,
    UTI_PRENOM VARCHAR(255) NOT NULL,
    UTI_NUM VARCHAR(15) NOT NULL,
    UTI_ADR TEXT NOT NULL,
    UTI_EMAIL VARCHAR(255) UNIQUE NOT NULL,
    UTI_KEY INT NOT NULL UNIQUE,
    UTI_MODE_PAYMENT VARCHAR(50) NOT NULL
);

-- Table : administrateur
CREATE TABLE administrateur (
    ADM_ID INT AUTO_INCREMENT PRIMARY KEY,
    ADM_NOM VARCHAR(255) NOT NULL,
    ADM_EMAIL VARCHAR(255) UNIQUE NOT NULL
);

-- Table : burger
CREATE TABLE burger (
    BURGER_ID INT AUTO_INCREMENT PRIMARY KEY,
    BURGER_NOM VARCHAR(255) NOT NULL,
    BURGER_PRIX DECIMAL(10, 2) NOT NULL
);

-- Table : crudite
CREATE TABLE crudite (
    CRUDITE_ID INT AUTO_INCREMENT PRIMARY KEY,
    CRUDITE_NOM VARCHAR(255) NOT NULL
);

-- Table : sauce
CREATE TABLE sauce (
    SAUCE_ID INT AUTO_INCREMENT PRIMARY KEY,
    SAUCE_NOM VARCHAR(255) NOT NULL
);

-- Table : boisson
CREATE TABLE boisson (
    BOISSON_ID INT AUTO_INCREMENT PRIMARY KEY,
    BOISSON_NOM VARCHAR(255) NOT NULL,
    BOISSON_PRIX DECIMAL(10, 2) NOT NULL
);

-- Table : commande
CREATE TABLE commande (
    CMD_ID INT AUTO_INCREMENT PRIMARY KEY,
    UTI_ID INT NOT NULL,
    ADM_ID INT NOT NULL,
    CMD_DATE DATETIME NOT NULL,
    CMD_PRIX DECIMAL(10, 2) NOT NULL,
    CMD_LIVRE BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (UTI_ID) REFERENCES utilisateur(UTI_ID),
    FOREIGN KEY (ADM_ID) REFERENCES administrateur(ADM_ID)
);

-- Table : lignecommande
CREATE TABLE lignecommande (
    LGN_CMD_ID INT AUTO_INCREMENT PRIMARY KEY,
    CMD_ID INT NOT NULL,
    BURGER_ID INT NOT NULL,
    LGN_CMD_PRIX DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (CMD_ID) REFERENCES commande(CMD_ID),
    FOREIGN KEY (BURGER_ID) REFERENCES burger(BURGER_ID)
);

-- Table : choixcrudites
CREATE TABLE choixcrudites (
    LGN_CMD_ID INT NOT NULL,
    CRUDITE_ID INT NOT NULL,
    PRIMARY KEY (LGN_CMD_ID, CRUDITE_ID),
    FOREIGN KEY (LGN_CMD_ID) REFERENCES lignecommande(LGN_CMD_ID),
    FOREIGN KEY (CRUDITE_ID) REFERENCES crudite(CRUDITE_ID)
);

-- Table : choixsauces
CREATE TABLE choixsauces (
    LGN_CMD_ID INT NOT NULL,
    SAUCE_ID INT NOT NULL,
    PRIMARY KEY (LGN_CMD_ID, SAUCE_ID),
    FOREIGN KEY (LGN_CMD_ID) REFERENCES lignecommande(LGN_CMD_ID),
    FOREIGN KEY (SAUCE_ID) REFERENCES sauce(SAUCE_ID)
);

-- Table : choixboissons
CREATE TABLE choixboissons (
    LGN_CMD_ID INT NOT NULL,
    BOISSON_ID INT NOT NULL,
    PRIMARY KEY (LGN_CMD_ID, BOISSON_ID),
    FOREIGN KEY (LGN_CMD_ID) REFERENCES lignecommande(LGN_CMD_ID),
    FOREIGN KEY (BOISSON_ID) REFERENCES boisson(BOISSON_ID)
);

-- Quelques données initiales (facultatif)

-- Insertion de quelques utilisateurs
INSERT INTO utilisateur (UTI_NOM, UTI_PRENOM, UTI_NUM, UTI_ADR, UTI_EMAIL, UTI_KEY, UTI_MODE_PAYMENT)
VALUES
('Doe', 'John', '0123456789', '123 Rue Exemple', 'john.doe@example.com', 123456, 'Carte bancaire');

-- Insertion de quelques burgers
INSERT INTO burger (BURGER_NOM, BURGER_PRIX)
VALUES
('Cheeseburger', 5.50),
('Bacon Burger', 6.50);

-- Insertion de quelques crudités
INSERT INTO crudite (CRUDITE_NOM)
VALUES
('Salade'), ('Tomate'), ('Oignon');

-- Insertion de quelques sauces
INSERT INTO sauce (SAUCE_NOM)
VALUES
('Ketchup'), ('Mayonnaise'), ('Barbecue');

-- Insertion de quelques boissons
INSERT INTO boisson (BOISSON_NOM, BOISSON_PRIX)
VALUES
('Coca-Cola', 2.00),
('Eau', 1.50);
