<?php
$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();

// Récupérer les statistiques
$stmt = $pdo->query("SELECT COUNT(*) as total FROM vehicules");
$totalVehicules = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM vehicules WHERE statut = 'disponible'");
$vehiculesDisponibles = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM clients");
$totalClients = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM locations WHERE statut = 'active'");
$locationsActives = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(prix_total) as total FROM locations WHERE statut = 'active'");
$revenusActifs = $stmt->fetch()['total'] ?? 0;

// Récupérer les dernières locations
$stmt = $pdo->query("
    SELECT l.*, 
           CONCAT(c.nom, ' ', c.prenom) as client_nom,
           CONCAT(v.marque, ' ', v.modele) as vehicule_nom
    FROM locations l
    JOIN clients c ON l.client_id = c.id
    JOIN vehicules v ON l.vehicule_id = v.id
    ORDER BY l.id DESC
    LIMIT 5
");
$dernieresLocations = $stmt->fetchAll();

// Récupérer la liste des véhicules avec leur statut
$stmt = $pdo->query("SELECT * FROM vehicules ORDER BY statut, marque, modele");
$vehicules = $stmt->fetchAll();
?>

<div class="page-header">
    <h1>Tableau de bord</h1>
    <div class="quick-actions">
        <a href="/admin/locations.php" class="btn btn-primary">+ Nouvelle Location</a>
        <a href="/admin/vehicules.php" class="btn btn-success">+ Nouveau Véhicule</a>
        <a href="/admin/clients.php" class="btn btn-info">+ Nouveau Client</a>
    </div>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Véhicules</h3>
            <p class="stat-number"><?php echo $totalVehicules; ?></p>
            <small><?php echo $vehiculesDisponibles; ?> disponibles</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Clients</h3>
            <p class="stat-number"><?php echo $totalClients; ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <h3>Locations Actives</h3>
            <p class="stat-number"><?php echo $locationsActives; ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <h3>Revenus Actifs</h3>
            <p class="stat-number"><?php echo number_format($revenusActifs, 3); ?> TND</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>État des Véhicules</h3>
    </div>
    <div class="card-body">
        <div class="vehicules-grid">
            <?php foreach ($vehicules as $vehicule): ?>
                <?php
                $statusClass = $vehicule['statut'] === 'disponible' ? 'vehicule-disponible' : ($vehicule['statut'] === 'loué' ? 'vehicule-loue' : 'vehicule-maintenance');
                $statusIcon = $vehicule['statut'] === 'disponible' ? '✅' : ($vehicule['statut'] === 'loué' ? '🔒' : '🔧');
                ?>
                <div class="vehicule-card <?php echo $statusClass; ?>">
                    <div class="vehicule-info">
                        <span class="vehicule-icon"><?php echo $statusIcon; ?></span>
                        <div>
                            <strong><?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></strong>
                            <p><?php echo number_format($vehicule['prix_par_jour'], 3); ?> TND/jour</p>
                        </div>
                    </div>
                    <span class="status-badge <?php echo $vehicule['statut'] === 'disponible' ? 'status-disponible' : ($vehicule['statut'] === 'loué' ? 'status-loue' : 'status-maintenance'); ?>">
                        <?php echo htmlspecialchars($vehicule['statut']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Dernières Locations</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($dernieresLocations) > 0): ?>
                    <?php foreach ($dernieresLocations as $location): ?>
                        <?php
                        if ($location['statut'] === 'active') {
                            $statusClass = 'status-active';
                        } elseif ($location['statut'] === 'terminee') {
                            $statusClass = 'status-terminee';
                        } else {
                            $statusClass = 'status-annulee';
                        }

                        // Display label with accent for better readability
                        $statusLabel = $location['statut'];
                        if ($location['statut'] === 'terminee') $statusLabel = 'terminée';
                        if ($location['statut'] === 'annulee') $statusLabel = 'annulée';
                        ?>
                        <tr>
                            <td><?php echo $location['id']; ?></td>
                            <td><?php echo htmlspecialchars($location['client_nom']); ?></td>
                            <td><?php echo htmlspecialchars($location['vehicule_nom']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($location['date_debut'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($location['date_fin'])); ?></td>
                            <td><?php echo number_format($location['prix_total'], 3); ?> TND</td>
                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusLabel); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucune location trouvée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>