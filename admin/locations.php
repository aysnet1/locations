<?php
$pageTitle = 'Gestion des Locations';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1>Gestion des Locations</h1>
    <button class="btn btn-primary" onclick="openLocationModal()">+ Nouvelle location</button>
</div>

<div class="card">
    <div class="card-header">
        <h3>Liste des locations</h3>
    </div>
    <div class="card-body">
        <table class="table" id="locationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="locationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nouvelle location</h2>
            <span class="close" onclick="closeLocationModal()">&times;</span>
        </div>
        <form id="locationForm">
            <input type="hidden" id="location_id" name="id">

            <div class="form-group">
                <label for="client_id">Client *</label>
                <select id="client_id" name="client_id" required>
                    <option value="">Sélectionnez un client</option>
                </select>
            </div>

            <div class="form-group">
                <label for="vehicule_id">Véhicule *</label>
                <select id="vehicule_id" name="vehicule_id" required>
                    <option value="">Sélectionnez un véhicule</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date_debut">Date de début *</label>
                <input type="date" id="date_debut" name="date_debut" required>
            </div>

            <div class="form-group">
                <label for="date_fin">Date de fin *</label>
                <input type="date" id="date_fin" name="date_fin" required>
            </div>

            <div class="form-group">
                <label for="prix_total">Prix total (TND)</label>
                <input type="number" id="prix_total" name="prix_total" step="0.001" readonly>
            </div>

            <div class="form-group">
                <label for="statut_location">Statut *</label>
                <select id="statut_location" name="statut" required>
                    <option value="active" selected>Active</option>
                    <option value="terminee">Terminée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeLocationModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/locations.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>