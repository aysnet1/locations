<?php
$pageTitle = 'Gestion des Clients';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1>Gestion des Clients</h1>
    <button class="btn btn-primary" onclick="openClientModal()">+ Ajouter un client</button>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Liste des clients</h3>
        <input type="text" id="searchClient" placeholder="🔍 Rechercher..." style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
    </div>
    <div class="card-body">
        <table class="table" id="clientsTable">
            <thead>
                <tr>
                    <th class="sortable" data-column="0" style="cursor: pointer;">ID ⇅</th>
                    <th class="sortable" data-column="1" style="cursor: pointer;">Nom ⇅</th>
                    <th class="sortable" data-column="2" style="cursor: pointer;">Prénom ⇅</th>
                    <th class="sortable" data-column="3" style="cursor: pointer;">Email ⇅</th>
                    <th class="sortable" data-column="4" style="cursor: pointer;">Téléphone ⇅</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les données seront chargées via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal pour ajouter/modifier un client -->
<div id="clientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Ajouter un client</h2>
            <span class="close" onclick="closeClientModal()">&times;</span>
        </div>
        <form id="clientForm">
            <input type="hidden" id="client_id" name="id">

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone *</label>
                <input type="tel" id="telephone" name="telephone" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" rows="3"></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeClientModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/clients.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>