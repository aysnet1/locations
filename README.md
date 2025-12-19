# Gestion d'une Agence de Location de Voitures

Application web complète pour la gestion d'une agence de location de voitures.

## Technologies utilisées

- **PHP 8**
- **MySQL**
- **HTML/CSS**
- **jQuery 3.7.1**
- **PDO** (pour les requêtes préparées)
- **AJAX** (pour les opérations CRUD)

## Fonctionnalités

### Authentification
- Connexion administrateur avec session
- Déconnexion sécurisée

### Gestion des Véhicules
- Ajouter un véhicule
- Modifier un véhicule
- Supprimer un véhicule
- Champs : marque, modèle, prix par jour, statut (disponible/loué/maintenance)

### Gestion des Clients
- Ajouter un client
- Modifier un client
- Supprimer un client
- Champs : nom, prénom, email, téléphone, adresse

### Gestion des Locations
- Créer une location
- Assigner un véhicule à un client
- Définir les dates de début et fin
- Calcul automatique du prix total
- Modifier/Supprimer une location
- Gestion du statut (active/terminée/annulée)

### Tableau de bord
- Nombre total de véhicules
- Véhicules disponibles
- Nombre de clients
- Locations actives
- Revenus actifs
- Liste des dernières locations

## Structure du projet

```
locations/
├── admin/                  # Pages d'administration
│   ├── dashboard.php      # Tableau de bord
│   ├── vehicules.php      # Gestion des véhicules
│   ├── clients.php        # Gestion des clients
│   └── locations.php      # Gestion des locations
├── api/                   # API endpoints pour AJAX
│   ├── vehicules.php
│   ├── clients.php
│   └── locations.php
├── assets/
│   ├── css/
│   │   └── style.css      # Styles CSS
│   └── js/
│       ├── vehicules.js   # JavaScript pour véhicules
│       ├── clients.js     # JavaScript pour clients
│       └── locations.js   # JavaScript pour locations
├── config/
│   └── database.php       # Configuration de la base de données
├── includes/
│   ├── auth.php          # Gestion de l'authentification
│   ├── header.php        # En-tête commun
│   └── footer.php        # Pied de page commun
├── database.sql          # Script SQL de création de la base
├── index.php             # Page de connexion
├── logout.php            # Déconnexion
└── README.md             # Ce fichier
```

## Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache, Nginx, ou serveur PHP intégré)

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   cd c:\Users\user\workdev\locations
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou votre client MySQL
   - Importer le fichier `database.sql`
   
   OU via la ligne de commande :
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Configurer la connexion à la base de données**
   - Ouvrir `config/database.php`
   - Modifier les paramètres si nécessaire :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'location_voitures');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Démarrer le serveur**
   
   Avec le serveur PHP intégré :
   ```bash
   php -S localhost:8000
   ```
   
   Ou placez le projet dans votre dossier `htdocs` (XAMPP) ou `www` (WAMP).

5. **Accéder à l'application**
   - Ouvrir votre navigateur
   - Aller à : `http://localhost:8000` ou `http://localhost/locations`

6. **Se connecter**
   - **Nom d'utilisateur** : `admin`
   - **Mot de passe** : `admin123`

## Données de test

La base de données contient des données de test :

### Véhicules
- Peugeot 208 (35€/jour)
- Renault Clio (30€/jour)
- Citroën C3 (32€/jour)
- Volkswagen Golf (45€/jour)
- BMW Série 3 (80€/jour)

### Clients
- Marie Dubois
- Jean Martin
- Sophie Lefebvre

## Sécurité

- Utilisation de **PDO** avec des **requêtes préparées** pour prévenir les injections SQL
- Gestion des sessions PHP pour l'authentification
- Validation côté serveur de toutes les entrées
- Protection contre les suppressions accidentelles (vérification des dépendances)

## Utilisation

### Gestion des véhicules
1. Cliquer sur "Véhicules" dans le menu
2. Cliquer sur "+ Ajouter un véhicule"
3. Remplir le formulaire et enregistrer
4. Utiliser les boutons "Modifier" ou "Supprimer" pour gérer les véhicules

### Gestion des clients
1. Cliquer sur "Clients" dans le menu
2. Cliquer sur "+ Ajouter un client"
3. Remplir les informations du client
4. Gérer les clients via les boutons d'action

### Créer une location
1. Aller dans "Locations"
2. Cliquer sur "+ Nouvelle location"
3. Sélectionner un client et un véhicule disponible
4. Choisir les dates de début et fin
5. Le prix total est calculé automatiquement
6. Enregistrer la location

### Tableau de bord
- Vue d'ensemble des statistiques
- Suivi des locations actives
- Monitoring des revenus

## Notes techniques

- Toutes les opérations CRUD utilisent **AJAX** pour une expérience utilisateur fluide
- Les données sont chargées dynamiquement sans rechargement de page
- La base de données utilise des **transactions** pour garantir l'intégrité des données
- Le statut des véhicules est automatiquement mis à jour lors de la création/modification de locations
- Validation des contraintes (impossible de supprimer un véhicule ou client avec des locations actives)

## Support

Pour toute question ou problème, veuillez créer une issue sur le dépôt du projet.

## Licence

Ce projet est fourni à des fins éducatives.
