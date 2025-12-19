$(document).ready(function () {
    loadClients();

    $('#clientForm').on('submit', function (e) {
        e.preventDefault();
        saveClient();
    });

    $('#searchClient').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        $('#clientsTable tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
        });
    });

    $('th.sortable').on('click', function () {
        const column = $(this).data('column');
        sortTable(column);
    });

    $(document).on('mouseenter', '#clientsTable tbody tr', function () {
        $(this).addClass('row-hover');
    }).on('mouseleave', '#clientsTable tbody tr', function () {
        $(this).removeClass('row-hover');
    });
});

let sortDirection = {};

function sortTable(column) {
    const tbody = $('#clientsTable tbody');
    const rows = tbody.find('tr').toArray();

    sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';

    rows.sort(function (a, b) {
        const aValue = $(a).find('td').eq(column).text();
        const bValue = $(b).find('td').eq(column).text();

        if (sortDirection[column] === 'asc') {
            return aValue.localeCompare(bValue, 'fr', { numeric: true });
        } else {
            return bValue.localeCompare(aValue, 'fr', { numeric: true });
        }
    });

    tbody.empty().append(rows);

    rows.forEach(function (row, index) {
        $(row).hide().delay(index * 50).fadeIn(300);
    });
}

function loadClients() {
    $.ajax({
        url: '/api/clients.php?action=list',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                displayClients(response.data);
            } else {
                alert('Erreur lors du chargement des clients');
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function displayClients(clients) {
    const tbody = $('#clientsTable tbody');
    tbody.empty();

    if (clients.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center">Aucun client trouvé</td></tr>');
        return;
    }

    clients.forEach(function (client, index) {
        const row = `
            <tr style="display: none;">
                <td>${client.id}</td>
                <td>${client.nom}</td>
                <td>${client.prenom}</td>
                <td>${client.email}</td>
                <td>${client.telephone}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editClient(${client.id})">Modifier</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteClient(${client.id})">Supprimer</button>
                </td>
            </tr>
        `;
        tbody.append(row);
        tbody.find('tr').last().delay(index * 50).fadeIn(300);
    });
}

function openClientModal() {
    $('#modalTitle').text('Ajouter un client');
    $('#clientForm')[0].reset();
    $('#client_id').val('');
    $('#clientModal').fadeIn(300);
    $('.modal-content').hide().slideDown(400);
}

function closeClientModal() {
    $('.modal-content').slideUp(300, function () {
        $('#clientModal').fadeOut(200);
    });
}

function editClient(id) {
    $.ajax({
        url: '/api/clients.php?action=get&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const client = response.data;
                $('#modalTitle').text('Modifier le client');
                $('#client_id').val(client.id);

                $('#nom').val('').fadeOut(100, function () {
                    $(this).val(client.nom).fadeIn(200);
                });
                $('#prenom').val('').fadeOut(100, function () {
                    $(this).val(client.prenom).fadeIn(200);
                });
                $('#email').val('').fadeOut(100, function () {
                    $(this).val(client.email).fadeIn(200);
                });
                $('#telephone').val('').fadeOut(100, function () {
                    $(this).val(client.telephone).fadeIn(200);
                });
                $('#adresse').val('').fadeOut(100, function () {
                    $(this).val(client.adresse).fadeIn(200);
                });

                $('#clientModal').fadeIn(300);
                $('.modal-content').hide().slideDown(400);
            }
        }
    });
}

function saveClient() {
    const id = $('#client_id').val();
    const action = id ? 'update' : 'create';

    const data = {
        nom: $('#nom').val(),
        prenom: $('#prenom').val(),
        email: $('#email').val(),
        telephone: $('#telephone').val(),
        adresse: $('#adresse').val()
    };

    if (id) {
        data.id = id;
    }

    $.ajax({
        url: '/api/clients.php?action=' + action,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showNotification(response.message, 'success');
                closeClientModal();
                setTimeout(function () {
                    loadClients();
                }, 300);
            } else {
                showNotification('Erreur: ' + response.message, 'error');
            }
        },
        error: function () {
            alert('Erreur de communication avec le serveur');
        }
    });
}

function deleteClient(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        return;
    }

    $.ajax({
        url: '/api/clients.php?action=delete&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showNotification(response.message, 'success');
                $('#clientsTable tbody tr').filter(function () {
                    return $(this).find('td').first().text() == id;
                }).fadeOut(400, function () {
                    $(this).remove();
                    loadClients();
                });
            } else {
                showNotification('Erreur: ' + response.message, 'error');
            }
        },
        error: function () {
            showNotification('Erreur de communication avec le serveur', 'error');
        }
    });
}

function showNotification(message, type) {
    const bgColor = type === 'success' ? '#27ae60' : '#e74c3c';
    const icon = type === 'success' ? '✓' : '✕';

    const notification = $(`
        <div class="custom-notification" style="
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: ${bgColor};
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 10000;
            display: none;
            font-weight: 500;
        ">
            <span style="font-size: 1.2rem; margin-right: 10px;">${icon}</span>
            ${message}
        </div>
    `);

    $('body').append(notification);
    notification.fadeIn(300).delay(2500).fadeOut(300, function () {
        $(this).remove();
    });
}
