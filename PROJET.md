# 🚗 Application de Gestion d'Agence de Location de Voitures

## ✅ Application Complète - Prête à l'emploi

---

## 📋 Résumé du Projet

Application web complète en **français** pour gérer une agence de location de voitures avec toutes les fonctionnalités demandées.

---

## 🛠️ Technologies Utilisées

- ✅ **PHP 8** (PDO avec requêtes préparées)
- ✅ **MySQL** (Base de données relationnelle)
- ✅ **HTML/CSS** (Interface utilisateur)
- ✅ **jQuery 3.7.1** (AJAX pour toutes les opérations CRUD)
- ✅ **Pas de framework** (Code natif uniquement)

---

## 🎯 Fonctionnalités Implémentées

### 1. 🔐 Authentification
- [x] Connexion administrateur avec session
- [x] Gestion sécurisée des sessions PHP
- [x] Déconnexion
- [x] Protection des pages admin

### 2. 🚙 Gestion des Véhicules (CRUD complet)
- [x] **Créer** un véhicule
- [x] **Lire** la liste des véhicules
- [x] **Modifier** un véhicule
- [x] **Supprimer** un véhicule
- [x] Champs : marque, modèle, prix/jour, statut
- [x] Statuts : disponible / loué / maintenance
- [x] Toutes les opérations via **AJAX**

### 3. 👥 Gestion des Clients (CRUD complet)
- [x] **Créer** un client
- [x] **Lire** la liste des clients
- [x] **Modifier** un client
- [x] **Supprimer** un client
- [x] Champs : nom, prénom, email, téléphone, adresse
- [x] Toutes les opérations via **AJAX**

### 4. 📝 Gestion des Locations (CRUD complet)
- [x] **Créer** une location
- [x] **Lire** la liste des locations
- [x] **Modifier** une location
- [x] **Supprimer** une location
- [x] Assigner véhicule à client
- [x] Dates de début et fin
- [x] **Calcul automatique du prix total**
- [x] Gestion des statuts (active/terminée/annulée)
- [x] Mise à jour automatique du statut des véhicules
- [x] Toutes les opérations via **AJAX**

### 5. 📊 Tableau de Bord
- [x] Nombre total de véhicules
- [x] Véhicules disponibles
- [x] Nombre de clients
- [x] **Locations actives**
- [x] Revenus actifs
- [x] Liste des 5 dernières locations

---

## 📁 Structure du Projet

```
locations/
│
├── 📄 database.sql              ← Script SQL complet
├── 📄 index.php                 ← Page de connexion
├── 📄 logout.php                ← Déconnexion
├── 📄 .htaccess                 ← Configuration Apache
├── 📄 README.md                 ← Documentation complète
├── 📄 INSTALLATION.md           ← Guide d'installation
│
├── 📁 config/
│   └── database.php             ← Connexion PDO
│
├── 📁 includes/
│   ├── auth.php                 ← Gestion authentification
│   ├── header.php               ← En-tête commun
│   └── footer.php               ← Pied de page commun
│
├── 📁 admin/
│   ├── dashboard.php            ← Tableau de bord
│   ├── vehicules.php            ← Gestion véhicules
│   ├── clients.php              ← Gestion clients
│   └── locations.php            ← Gestion locations
│
├── 📁 api/
│   ├── vehicules.php            ← API CRUD véhicules
│   ├── clients.php              ← API CRUD clients
│   └── locations.php            ← API CRUD locations
│
└── 📁 assets/
    ├── 📁 css/
    │   └── style.css            ← Styles CSS complets
    └── 📁 js/
        ├── vehicules.js         ← AJAX véhicules
        ├── clients.js           ← AJAX clients
        └── locations.js         ← AJAX locations
```

---

## 🗄️ Base de Données

### Tables créées :

1. **`users`** - Utilisateurs administrateurs
   - id, username, password, nom, created_at

2. **`vehicules`** - Véhicules de l'agence
   - id, marque, modele, prix_par_jour, statut, created_at, updated_at

3. **`clients`** - Clients de l'agence
   - id, nom, prenom, email, telephone, adresse, created_at, updated_at

4. **`locations`** - Locations de véhicules
   - id, vehicule_id, client_id, date_debut, date_fin, prix_total, statut, created_at, updated_at
   - Avec clés étrangères vers vehicules et clients

### Données de test incluses :
- ✅ 1 utilisateur admin (admin/admin123)
- ✅ 5 véhicules
- ✅ 3 clients

---

## 🔒 Sécurité Implémentée

- ✅ **PDO** avec requêtes préparées (protection SQL injection)
- ✅ Sessions PHP sécurisées
- ✅ Validation des entrées côté serveur
- ✅ Protection contre les suppressions accidentelles
- ✅ Transactions MySQL pour l'intégrité des données
- ✅ Headers de sécurité HTTP (.htaccess)

---

## 🚀 Installation Rapide

### Étape 1 : Créer et importer la base de données
```powershell
mysql -u root -p
```
```sql
CREATE DATABASE location_voitures CHARACTER SET utf8mb4;
exit;
```
```powershell
mysql -u root -p location_voitures < database.sql
```

### Étape 2 : Configurer la connexion
Vérifier `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'location_voitures');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Étape 3 : Démarrer le serveur
```powershell
cd c:\Users\user\workdev\locations
php -S localhost:8000
```

### Étape 4 : Se connecter
- **URL** : http://localhost:8000
- **Username** : `admin`
- **Password** : `admin123`

---

## 💡 Règles Techniques Respectées

- ✅ Utilisation de **PDO** uniquement
- ✅ Toutes les requêtes sont **préparées** (prepared statements)
- ✅ **jQuery AJAX** pour toutes les opérations CRUD
- ✅ Structure de dossiers simple et claire
- ✅ **Aucun framework** utilisé
- ✅ Interface en **français**
- ✅ Code commenté et structuré

---

## 🎨 Interface Utilisateur

- ✅ Design moderne et responsive
- ✅ Navigation intuitive
- ✅ Modals pour les formulaires
- ✅ Badges de statut colorés
- ✅ Tableaux interactifs
- ✅ Alertes et confirmations
- ✅ Pas de rechargement de page (AJAX)

---

## 📖 Utilisation

### Créer une location :
1. Aller dans "Locations"
2. Cliquer sur "+ Nouvelle location"
3. Sélectionner un client
4. Choisir un véhicule disponible
5. Définir les dates
6. Le prix est **calculé automatiquement** !
7. Enregistrer

### Le système fait automatiquement :
- ✅ Calcul du nombre de jours
- ✅ Calcul du prix total (jours × prix/jour)
- ✅ Changement du statut du véhicule (disponible → loué)
- ✅ Validation de la disponibilité
- ✅ Mise à jour des statistiques

---

## 📊 Calcul Automatique du Prix

```javascript
Prix Total = (Date Fin - Date Début + 1 jour) × Prix par Jour
```

Exemple :
- Véhicule : Peugeot 208 (35€/jour)
- Du 18/12/2025 au 22/12/2025
- Durée : 5 jours
- **Prix total : 175€**

---

## 🎯 Points Forts

1. **Code propre et structuré**
2. **100% AJAX** - Pas de rechargement de page
3. **Transactions MySQL** - Intégrité garantie
4. **Validation complète** - Côté client et serveur
5. **Responsive design** - Fonctionne sur mobile
6. **Données de test** - Prêt à tester immédiatement
7. **Documentation complète** - README + Guide d'installation
8. **Sécurité renforcée** - PDO, sessions, validation

---

## 📝 Fichiers Créés (Total : 20+ fichiers)

### PHP (9 fichiers)
- index.php, logout.php
- config/database.php
- includes/auth.php, header.php, footer.php
- admin/dashboard.php, vehicules.php, clients.php, locations.php

### API PHP (3 fichiers)
- api/vehicules.php, clients.php, locations.php

### JavaScript (3 fichiers)
- assets/js/vehicules.js, clients.js, locations.js

### CSS (1 fichier)
- assets/css/style.css

### SQL (1 fichier)
- database.sql

### Documentation (3 fichiers)
- README.md, INSTALLATION.md, PROJET.md

### Configuration (1 fichier)
- .htaccess

---

## ✨ Prêt à l'emploi !

L'application est **100% fonctionnelle** et prête à être testée.

Tous les fichiers sont créés, la base de données est prête, et l'interface est complète.

**Il suffit d'importer la base de données et de démarrer le serveur !** 🚀

---

## 📞 Support

Consultez :
- `README.md` pour la documentation complète
- `INSTALLATION.md` pour le guide d'installation détaillé
- `database.sql` pour la structure de la base de données

---

**Bon développement ! 🎉**
