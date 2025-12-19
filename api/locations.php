<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$pdo = getDBConnection();

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("
                SELECT l.*, 
                       CONCAT(c.nom, ' ', c.prenom) as client_nom,
                       CONCAT(v.marque, ' ', v.modele) as vehicule_nom
                FROM locations l
                JOIN clients c ON l.client_id = c.id
                JOIN vehicules v ON l.vehicule_id = v.id
                ORDER BY l.id DESC
            ");
            $locations = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $locations]);
            break;

        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("
                SELECT l.*, 
                       CONCAT(c.nom, ' ', c.prenom) as client_nom,
                       CONCAT(v.marque, ' ', v.modele) as vehicule_nom
                FROM locations l
                JOIN clients c ON l.client_id = c.id
                JOIN vehicules v ON l.vehicule_id = v.id
                WHERE l.id = ?
            ");
            $stmt->execute([$id]);
            $location = $stmt->fetch();

            if ($location) {
                echo json_encode(['success' => true, 'data' => $location]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Location non trouvée']);
            }
            break;

        case 'create':
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ?");
            $stmt->execute([$data['vehicule_id']]);
            $vehicule = $stmt->fetch();

            if (!$vehicule || $vehicule['statut'] !== 'disponible') {
                echo json_encode(['success' => false, 'message' => 'Ce véhicule n\'est pas disponible']);
                break;
            }

            if (!isset($data['prix_total']) || $data['prix_total'] == 0) {
                $stmt = $pdo->prepare("SELECT prix_par_jour FROM vehicules WHERE id = ?");
                $stmt->execute([$data['vehicule_id']]);
                $vehicule = $stmt->fetch();

                $date_debut = new DateTime($data['date_debut']);
                $date_fin = new DateTime($data['date_fin']);
                $jours = $date_debut->diff($date_fin)->days + 1;
                $data['prix_total'] = $jours * $vehicule['prix_par_jour'];
            }

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO locations (vehicule_id, client_id, date_debut, date_fin, prix_total, statut) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['vehicule_id'],
                $data['client_id'],
                $data['date_debut'],
                $data['date_fin'],
                $data['prix_total'],
                $data['statut']
            ]);

            if ($data['statut'] === 'active') {
                $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'loue' WHERE id = ?");
                $stmt->execute([$data['vehicule_id']]);
            }

            $pdo->commit();

            echo json_encode(['success' => true, 'message' => 'Location créée avec succès', 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $data = json_decode(file_get_contents('php://input'), true);

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT statut, vehicule_id FROM locations WHERE id = ?");
            $stmt->execute([$data['id']]);
            $oldLocation = $stmt->fetch();

            $stmt = $pdo->prepare("UPDATE locations SET vehicule_id = ?, client_id = ?, date_debut = ?, date_fin = ?, prix_total = ?, statut = ? WHERE id = ?");
            $stmt->execute(
                [
                    $data['vehicule_id'],
                    $data['client_id'],
                    $data['date_debut'],
                    $data['date_fin'],
                    $data['prix_total'],
                    $data['statut'],
                ]
            );

            if ($oldLocation['statut'] === 'active' && $data['statut'] !== 'active') {
                $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'disponible' WHERE id = ?");
                $stmt->execute([$oldLocation['vehicule_id']]);
            }

            if ($oldLocation['statut'] !== 'active' && $data['statut'] === 'active') {
                $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'loue' WHERE id = ?");
                $stmt->execute([$data['vehicule_id']]);
            }

            $pdo->commit();

            echo json_encode(['success' => true, 'message' => 'Location modifiée avec succès']);
            break;

        case 'delete':
            $id = $_GET['id'] ?? 0;

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT vehicule_id, statut FROM locations WHERE id = ?");
            $stmt->execute([$id]);
            $location = $stmt->fetch();

            if ($location && $location['statut'] === 'active') {
                $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'disponible' WHERE id = ?");
                $stmt->execute([$location['vehicule_id']]);
            }

            $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->commit();

            echo json_encode(['success' => true, 'message' => 'Location supprimée avec succès']);
            break;

        case 'calculate':
            $vehicule_id = $_GET['vehicule_id'] ?? 0;
            $date_debut = $_GET['date_debut'] ?? '';
            $date_fin = $_GET['date_fin'] ?? '';

            if (!$vehicule_id || !$date_debut || !$date_fin) {
                echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
                break;
            }

            $stmt = $pdo->prepare("SELECT prix_par_jour FROM vehicules WHERE id = ?");
            $stmt->execute([$vehicule_id]);
            $vehicule = $stmt->fetch();

            if (!$vehicule) {
                echo json_encode(['success' => false, 'message' => 'Véhicule non trouvé']);
                break;
            }

            $debut = new DateTime($date_debut);
            $fin = new DateTime($date_fin);
            $jours = $debut->diff($fin)->days + 1;
            $prix_total = $jours * $vehicule['prix_par_jour'];

            echo json_encode([
                'success' => true,
                'jours' => $jours,
                'prix_par_jour' => $vehicule['prix_par_jour'],
                'prix_total' => $prix_total
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action non valide']);
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
