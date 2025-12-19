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
            $stmt = $pdo->query("SELECT * FROM vehicules ORDER BY id DESC");
            $vehicules = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $vehicules]);
            break;

        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
            $stmt->execute([$id]);
            $vehicule = $stmt->fetch();

            if ($vehicule) {
                echo json_encode(['success' => true, 'data' => $vehicule]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Véhicule non trouvé']);
            }
            break;

        case 'create':
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $pdo->prepare("INSERT INTO vehicules (marque, modele, prix_par_jour, statut) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['marque'],
                $data['modele'],
                $data['prix_par_jour'],
                $data['statut']
            ]);

            echo json_encode(['success' => true, 'message' => 'Véhicule ajouté avec succès', 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $pdo->prepare("UPDATE vehicules SET marque = ?, modele = ?, prix_par_jour = ?, statut = ? WHERE id = ?");
            $stmt->execute([
                $data['marque'],
                $data['modele'],
                $data['prix_par_jour'],
                $data['statut'],
                $data['id']
            ]);

            echo json_encode(['success' => true, 'message' => 'Véhicule modifié avec succès']);
            break;

        case 'delete':
            $id = $_GET['id'] ?? 0;

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM locations WHERE vehicule_id = ? AND statut = 'active'");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo json_encode(['success' => false, 'message' => 'Impossible de supprimer ce véhicule car il a des locations actives']);
                break;
            }

            $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Véhicule supprimé avec succès']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action non valide']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
