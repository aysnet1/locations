$(document).ready(function () {
    loadVehicules();

    $('#vehiculeForm').on('submit', function (e) {
        e.preventDefault();
        saveVehicule();
    });

    $('#searchVehicule').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        $('#vehiculesTable tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
        });
    });

    $('#filterStatut').on('change', function () {
        const filterValue = $(this).val();
        if (filterValue === 'tous') {
            $('#vehiculesTable tbody tr').show();
        } else {
            $('#vehiculesTable tbody tr').each(function () {
                const statut = $(this).find('.status-badge').text().toLowerCase();
                $(this).toggle(statut.includes(filterValue));
            });
        }
    });

    $(document).on('mouseenter', '#vehiculesTable tbody tr', function () {
        $(this).addClass('row-hover');
    }).on('mouseleave', '#vehiculesTable tbody tr', function () {
        $(this).removeClass('row-hover');
    });
});

function loadVehicules() {
    $.ajax({
        url: '/api/vehicules.php?action=list',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                displayVehicules(response.data);
            } else {
                alert('Erreur lors du chargement des véhicules');
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function displayVehicules(vehicules) {
    const tbody = $('#vehiculesTable tbody');
    tbody.empty();

    if (vehicules.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center">Aucun véhicule trouvé</td></tr>');
        return;
    }

    vehicules.forEach(function (vehicule) {
        const statusClass = vehicule.statut === 'disponible' ? 'status-disponible' :
            vehicule.statut === 'loue' ? 'status-loue' : 'status-maintenance';

        // Display with accent for better readability
        let statusLabel = vehicule.statut;
        if (vehicule.statut === 'loue') statusLabel = 'Loué';

        const row = `
            <tr>
                <td>${vehicule.id}</td>
                <td>${vehicule.marque}</td>
                <td>${vehicule.modele}</td>
                <td>${parseFloat(vehicule.prix_par_jour).toFixed(3)} TND</td>
                <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editVehicule(${vehicule.id})">Modifier</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteVehicule(${vehicule.id})">Supprimer</button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function openVehiculeModal() {
    $('#modalTitle').text('Ajouter un véhicule');
    $('#vehiculeForm')[0].reset();
    $('#vehicule_id').val('');
    $('#statut').val('disponible'); // Set default status
    $('#vehiculeModal').fadeIn();
}

function closeVehiculeModal() {
    $('#vehiculeModal').fadeOut();
}

function editVehicule(id) {
    $.ajax({
        url: '/api/vehicules.php?action=get&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const vehicule = response.data;
                $('#modalTitle').text('Modifier le véhicule');
                $('#vehicule_id').val(vehicule.id);
                $('#marque').val(vehicule.marque);
                $('#modele').val(vehicule.modele);
                $('#prix_par_jour').val(vehicule.prix_par_jour);
                $('#statut').val(vehicule.statut);
                $('#vehiculeModal').fadeIn();
            }
        }
    });
}

function saveVehicule() {
    const id = $('#vehicule_id').val();
    const action = id ? 'update' : 'create';

    const data = {
        marque: $('#marque').val(),
        modele: $('#modele').val(),
        prix_par_jour: $('#prix_par_jour').val(),
        statut: $('#statut').val()
    };

    if (id) {
        data.id = id;
    }

    $.ajax({
        url: '/api/vehicules.php?action=' + action,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert(response.message);
                closeVehiculeModal();
                loadVehicules();
            } else {
                alert('Erreur: ' + response.message);
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function deleteVehicule(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')) {
        return;
    }

    $.ajax({
        url: '/api/vehicules.php?action=delete&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert(response.message);
                loadVehicules();
            } else {
                alert('Erreur: ' + response.message);
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}
