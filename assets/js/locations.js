$(document).ready(function () {
    loadLocations();
    loadClientsForSelect();
    loadVehiculesForSelect();

    $('#locationForm').on('submit', function (e) {
        e.preventDefault();
        saveLocation();
    });

    $('#vehicule_id, #date_debut, #date_fin').on('change', function () {
        calculatePrice();
    });
});

function loadLocations() {
    $.ajax({
        url: '/api/locations.php?action=list',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                displayLocations(response.data);
            } else {
                alert('Erreur lors du chargement des locations');
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function displayLocations(locations) {
    const tbody = $('#locationsTable tbody');
    tbody.empty();

    if (locations.length === 0) {
        tbody.append('<tr><td colspan="8" class="text-center">Aucune location trouvée</td></tr>');
        return;
    }

    locations.forEach(function (location) {
        const statusClass = location.statut === 'active' ? 'status-active' :
            location.statut === 'terminee' ? 'status-terminee' : 'status-annulee';

        let statusLabel = location.statut;
        if (location.statut === 'terminee') statusLabel = 'terminée';
        if (location.statut === 'annulee') statusLabel = 'annulée';

        const row = `
            <tr>
                <td>${location.id}</td>
                <td>${location.client_nom}</td>
                <td>${location.vehicule_nom}</td>
                <td>${formatDate(location.date_debut)}</td>
                <td>${formatDate(location.date_fin)}</td>
                <td>${parseFloat(location.prix_total).toFixed(3)} TND</td>
                <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editLocation(${location.id})">Modifier</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteLocation(${location.id})">Supprimer</button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function loadClientsForSelect() {
    $.ajax({
        url: '/api/clients.php?action=list',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const select = $('#client_id');
                select.find('option:not(:first)').remove();
                response.data.forEach(function (client) {
                    select.append(`<option value="${client.id}">${client.nom} ${client.prenom}</option>`);
                });
            }
        }
    });
}

function loadVehiculesForSelect() {
    $.ajax({
        url: '/api/vehicules.php?action=list',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const select = $('#vehicule_id');
                select.find('option:not(:first)').remove();
                response.data.forEach(function (vehicule) {
                    const disponible = vehicule.statut === 'disponible' ? '' : ' (Non disponible)';
                    select.append(`<option value="${vehicule.id}" ${vehicule.statut !== 'disponible' ? 'disabled' : ''}>${vehicule.marque} ${vehicule.modele} - ${vehicule.prix_par_jour}€/jour${disponible}</option>`);
                });
            }
        }
    });
}

function calculatePrice() {
    const vehicule_id = $('#vehicule_id').val();
    const date_debut = $('#date_debut').val();
    const date_fin = $('#date_fin').val();

    if (!vehicule_id || !date_debut || !date_fin) {
        return;
    }

    $.ajax({
        url: `/api/locations.php?action=calculate&vehicule_id=${vehicule_id}&date_debut=${date_debut}&date_fin=${date_fin}`,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#prix_total').val(response.prix_total.toFixed(2));
            }
        }
    });
}

function openLocationModal() {
    $('#modalTitle').text('Nouvelle location');
    $('#locationForm')[0].reset();
    $('#location_id').val('');
    $('#prix_total').val('');
    $('#statut_location').val('active');
    $('#locationModal').fadeIn();
}

function closeLocationModal() {
    $('#locationModal').fadeOut();
}

function editLocation(id) {
    $.ajax({
        url: '/api/locations.php?action=get&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const location = response.data;
                $('#modalTitle').text('Modifier la location');
                $('#location_id').val(location.id);
                $('#client_id').val(location.client_id);
                $('#vehicule_id').val(location.vehicule_id);
                $('#date_debut').val(location.date_debut);
                $('#date_fin').val(location.date_fin);
                $('#prix_total').val(location.prix_total);
                $('#statut_location').val(location.statut);
                $('#locationModal').fadeIn();
            }
        }
    });
}

function saveLocation() {
    const id = $('#location_id').val();
    const action = id ? 'update' : 'create';

    const data = {
        client_id: $('#client_id').val(),
        vehicule_id: $('#vehicule_id').val(),
        date_debut: $('#date_debut').val(),
        date_fin: $('#date_fin').val(),
        prix_total: $('#prix_total').val(),
        statut: $('#statut_location').val()
    };

    if (id) {
        data.id = id;
    }

    $.ajax({
        url: '/api/locations.php?action=' + action,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert(response.message);
                closeLocationModal();
                loadLocations();
            } else {
                alert('Erreur: ' + response.message);
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function deleteLocation(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette location ?')) {
        return;
    }

    $.ajax({
        url: '/api/locations.php?action=delete&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert(response.message);
                loadLocations();
            } else {
                alert('Erreur: ' + response.message);
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR');
}
