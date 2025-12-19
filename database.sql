
CREATE DATABASE IF NOT EXISTS location_voitures CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE location_voitures;

-- Table des utilisateurs 
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des véhicules
CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    prix_par_jour DECIMAL(10, 2) NOT NULL,
    statut ENUM('disponible', 'loue', 'maintenance') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    adresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    client_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10, 2) NOT NULL,
    statut ENUM('active', 'terminee', 'annulee') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE RESTRICT,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un utilisateur administrateur par défaut
INSERT INTO users (username, password, nom) VALUES 
('admin', 'admin123', 'Administrateur');

-- Insertion de quelques véhicules de test
INSERT INTO vehicules (marque, modele, prix_par_jour, statut) VALUES
('Peugeot', '208', 35.00, 'disponible'),
('Renault', 'Clio', 30.00, 'disponible'),
('Citroen', 'C3', 32.00, 'disponible'),
('Volkswagen', 'Golf', 45.00, 'disponible'),
('BMW', 'Serie 3', 80.00, 'disponible');

-- Insertion de quelques clients de test
INSERT INTO clients (nom, prenom, email, telephone, adresse) VALUES
('Ahmed', 'Salem', 'ahmed.salem@email.fr', '0612345678', '12 Rue de Med ali, Kairouan'),
('Aysser', 'Jbili', 'aysser.jbili@email.fr', '0623456789', 'Raggada, Kairouan'),
