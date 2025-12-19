<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Gestion Location Voitures'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h2>🚗 Location Voitures</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="/admin/dashboard.php">Tableau de bord</a></li>
                <li><a href="/admin/vehicules.php">Véhicules</a></li>
                <li><a href="/admin/clients.php">Clients</a></li>
                <li><a href="/admin/locations.php">Locations</a></li>
                <li class="user-info">
                    <span>👤 <?php echo htmlspecialchars($currentUser['nom']); ?></span>
                    <a href="/logout.php" class="btn-logout">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container main-content">