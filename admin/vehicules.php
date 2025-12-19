<?php
$pageTitle = 'Gestion des Véhicules';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1>Gestion des Véhicules</h1>
    <button class="btn btn-primary" onclick="openVehiculeModal()">+ Ajouter un véhicule</button>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3>Liste des véhicules</h3>
        <div style="display: flex; gap: 1rem;">
            <input type="text" id="searchVehicule" placeholder="🔍 Rechercher..." style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px;">
            <select id="filterStatut" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px;">
                <option value="tous">Tous les statuts</option>
                <option value="disponible">Disponible</option>
                <option value="loue">Loué</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <table class="table" id="vehiculesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Prix/Jour</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<div id="vehiculeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Ajouter un véhicule</h2>
            <span class="close" onclick="closeVehiculeModal()">&times;</span>
        </div>
        <form id="vehiculeForm">
            <input type="hidden" id="vehicule_id" name="id">

            <div class="form-group">
                <label for="marque">Marque *</label>
                <input type="text" id="marque" name="marque" required>
            </div>

            <div class="form-group">
                <label for="modele">Modèle *</label>
                <input type="text" id="modele" name="modele" required>
            </div>

            <div class="form-group">
                <label for="prix_par_jour">Prix par jour (TND) *</label>
                <input type="number" id="prix_par_jour" name="prix_par_jour" step="0.001" min="0" required>
            </div>

            <div class="form-group">
                <label for="statut">Statut *</label>
                <select id="statut" name="statut" required>
                    <option value="disponible" selected>Disponible</option>
                    <option value="loue">Loué</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeVehiculeModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/vehicules.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>