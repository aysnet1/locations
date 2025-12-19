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
            $stmt = $pdo->query("SELECT * FROM clients ORDER BY nom, prenom");
            $clients = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $clients]);
            break;

        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $client = $stmt->fetch();

            if ($client) {
                echo json_encode(['success' => true, 'data' => $client]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Client non trouvé']);
            }
            break;

        case 'create':
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, telephone, adresse) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'] ?? ''
            ]);

            echo json_encode(['success' => true, 'message' => 'Client ajouté avec succès', 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $data = json_decode(file_get_contents('php://input'), true);

            $stmt = $pdo->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?");
            $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'] ?? '',
                $data['id']
            ]);

            echo json_encode(['success' => true, 'message' => 'Client modifié avec succès']);
            break;

        case 'delete':
            $id = $_GET['id'] ?? 0;

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM locations WHERE client_id = ? AND statut = 'active'");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo json_encode(['success' => false, 'message' => 'Impossible de supprimer ce client car il a des locations actives']);
                break;
            }

            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Client supprimé avec succès']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action non valide']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
