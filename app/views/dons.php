<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dons - BNGRC</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/font-awesome.min.css">
    <link rel="stylesheet" href="/template/css/themify-icons.css">
    <link rel="stylesheet" href="/template/css/style.css">
    <link rel="stylesheet" href="/template/css/theme-default.css">
    <style>
        body { background: #f7f7f7; }
        .dons-section { padding: 40px 0; }
        .dons-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); padding: 30px; margin-bottom: 30px; }
        .dons-card h2 { color: #191d34; font-size: 28px; margin-bottom: 20px; }
        .table thead th { background: #3CC78F; color: #fff; border: none; font-weight: 500; }
        .table tbody tr:hover { background: #f0faf5; }
        .btn-add { background: #3CC78F; color: #fff; border: none; padding: 10px 25px; border-radius: 5px; font-weight: 600; }
        .btn-add:hover { background: #2ea874; color: #fff; }
        .btn-edit { background: #5DB2FF; color: #fff; border: none; border-radius: 4px; padding: 5px 12px; }
        .btn-edit:hover { background: #3a9cf0; color: #fff; }
        .btn-delete { background: #ff6b6b; color: #fff; border: none; border-radius: 4px; padding: 5px 12px; }
        .btn-delete:hover { background: #e04545; color: #fff; }
        .modal-header { background: #3CC78F; color: #fff; border-radius: 5px 5px 0 0; }
        .modal-header .close { color: #fff; opacity: 1; }
        .bradcam_area { background: linear-gradient(135deg, #3CC78F 0%, #2ea874 100%); padding: 40px 0; text-align: center; }

        /* Fix modal Bootstrap - forcer le bon affichage */
        .modal { position: fixed !important; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050 !important; overflow-x: hidden; overflow-y: auto; outline: 0; }
        .modal-backdrop { position: fixed !important; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 1040 !important; background-color: #000; }
        .modal-backdrop.show { opacity: 0.5; }
        .modal-dialog { position: relative; margin: 80px auto; max-width: 500px; z-index: 1060 !important; }
        .modal-content { position: relative; background: #fff; border: none; border-radius: 8px; box-shadow: 0 5px 30px rgba(0,0,0,0.3); }
        .modal.show .modal-dialog { transform: translate(0, 0); }
        body.modal-open { overflow: hidden !important; }
        .bradcam_area h3 { color: #fff; font-size: 32px; font-weight: 700; margin: 0; }
        .bradcam_area p { color: rgba(255,255,255,0.8); margin-top: 5px; }
        .stats-box { background: #fff; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .stats-box h3 { color: #3CC78F; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .stats-box p { color: #777; margin: 0; font-size: 14px; }
        .montant-col { font-weight: 600; color: #3CC78F; }
        .nav-back { padding: 15px 0; }
        .nav-back a { color: #3CC78F; text-decoration: none; font-weight: 500; }
        .nav-back a:hover { color: #2ea874; }
        #loading { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="bradcam_area">
        <div class="container">
            <h3><i class="fa fa-heart"></i> Gestion des Dons</h3>
            <p>BNGRC - Suivi des collectes et distributions pour les sinistrés</p>
        </div>
    </div>

    <!-- Navigation -->
    <div class="container nav-back">
        <a href="/"><i class="fa fa-arrow-left"></i> Retour au tableau de bord</a>
    </div>

    <!-- Stats -->
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-box">
                    <h3 id="totalDons">0</h3>
                    <p>Total des dons</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <h3 id="totalMontant">0 Ar</h3>
                    <p>Montant total collecté</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <h3 id="dernierDon">-</h3>
                    <p>Dernier don reçu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des dons -->
    <div class="container dons-section">
        <div class="dons-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="fa fa-list"></i> Liste des Dons</h2>
                <button class="btn btn-add" onclick="openAddModal()">
                    <i class="fa fa-plus"></i> Ajouter un Don
                </button>
            </div>

            <div id="loading"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Chargement...</div>

            <div class="table-responsive" id="tableContainer" style="display:none;">
                <table class="table table-bordered" id="donsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom du donateur</th>
                            <th>Montant (Ar)</th>
                            <th>Date du don</th>
                            <th style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="emptyMessage" style="display:none; text-align:center; padding:40px; color:#999;">
                <i class="fa fa-inbox fa-3x"></i>
                <p class="mt-3">Aucun don enregistré pour le moment.</p>
                <button class="btn btn-add" onclick="openAddModal()">Ajouter le premier don</button>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter/Modifier -->
    <div class="modal fade" id="donModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-plus"></i> Ajouter un Don</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="donForm">
                        <input type="hidden" id="donId">
                        <div class="form-group">
                            <label for="nom"><i class="fa fa-user"></i> Nom du donateur</label>
                            <input type="text" class="form-control" id="nom" placeholder="Ex: ONG Internationale" required>
                        </div>
                        <div class="form-group">
                            <label for="montant"><i class="fa fa-money"></i> Montant (Ar)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="montant" placeholder="Ex: 50000" required>
                        </div>
                        <div class="form-group">
                            <label for="date_don"><i class="fa fa-calendar"></i> Date du don</label>
                            <input type="date" class="form-control" id="date_don" required>
                        </div>
                        <div id="formError" class="alert alert-danger" style="display:none;"></div>
                        <button type="submit" class="btn btn-add btn-block">
                            <i class="fa fa-save"></i> <span id="btnText">Sauvegarder</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#ff6b6b;">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-trash fa-3x" style="color:#ff6b6b;"></i>
                    <p class="mt-3 mb-0">Êtes-vous sûr de vouloir supprimer ce don ?</p>
                    <p class="text-muted"><small>Cette action est irréversible.</small></p>
                </div>
                <div class="modal-footer" style="justify-content:center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteBtn" onclick="confirmDelete()">
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" style="display:none; position:fixed; top:20px; right:20px; z-index:9999; padding:15px 25px; border-radius:8px; color:#fff; font-weight:500; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s;">
        <i class="fa" id="toastIcon"></i> <span id="toastMsg"></span>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/template/js/popper.min.js"></script>
    <script src="/template/js/bootstrap.min.js"></script>
    <script>
        var API_URL = '/api/dons';
        var deleteId = null;

        $(document).ready(function() {
            loadDons();

            $('#donForm').on('submit', function(e) {
                e.preventDefault();
                $('#formError').hide();

                var id = $('#donId').val();
                var data = {
                    nom: $('#nom').val().trim(),
                    montant: parseFloat($('#montant').val()),
                    date_don: $('#date_don').val()
                };

                if (!data.nom || !data.montant || !data.date_don) {
                    $('#formError').text('Veuillez remplir tous les champs.').show();
                    return;
                }

                if (id) {
                    updateDon(id, data);
                } else {
                    createDon(data);
                }
            });
        });

        function showToast(message, type) {
            var bg = type === 'success' ? '#3CC78F' : type === 'error' ? '#ff6b6b' : '#5DB2FF';
            var icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
            $('#toast').css('background', bg);
            $('#toastIcon').attr('class', 'fa ' + icon);
            $('#toastMsg').text(message);
            $('#toast').fadeIn(300);
            setTimeout(function() { $('#toast').fadeOut(300); }, 3000);
        }

        function loadDons() {
            $('#loading').show();
            $('#tableContainer').hide();
            $('#emptyMessage').hide();

            $.ajax({
                url: API_URL,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#loading').hide();
                    var tbody = $('#donsTable tbody');
                    tbody.empty();

                    if (data && data.length > 0) {
                        $('#tableContainer').show();
                        var totalMontant = 0;

                        data.forEach(function(don) {
                            totalMontant += parseFloat(don.montant);
                            tbody.append(
                                '<tr>' +
                                    '<td>' + don.id + '</td>' +
                                    '<td>' + escapeHtml(don.nom) + '</td>' +
                                    '<td class="montant-col">' + formatMontant(don.montant) + ' Ar</td>' +
                                    '<td>' + formatDate(don.date_don) + '</td>' +
                                    '<td>' +
                                        '<button class="btn btn-edit btn-sm mr-1" onclick="editDon(' + don.id + ')">' +
                                            '<i class="fa fa-pencil"></i> Modifier' +
                                        '</button>' +
                                        '<button class="btn btn-delete btn-sm" onclick="deleteDon(' + don.id + ')">' +
                                            '<i class="fa fa-trash"></i> Supprimer' +
                                        '</button>' +
                                    '</td>' +
                                '</tr>'
                            );
                        });

                        $('#totalDons').text(data.length);
                        $('#totalMontant').text(formatMontant(totalMontant) + ' Ar');
                        $('#dernierDon').text(escapeHtml(data[0].nom));
                    } else {
                        $('#emptyMessage').show();
                        $('#totalDons').text('0');
                        $('#totalMontant').text('0 Ar');
                        $('#dernierDon').text('-');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    $('#tableContainer').show();
                    $('#donsTable tbody').html(
                        '<tr><td colspan="5" class="text-center text-danger">' +
                        '<i class="fa fa-exclamation-triangle"></i> Erreur de chargement des dons' +
                        '</td></tr>'
                    );
                    console.error('Erreur:', status, error);
                }
            });
        }

        function openAddModal() {
            $('#donForm')[0].reset();
            $('#donId').val('');
            $('#formError').hide();
            $('#modalTitle').html('<i class="fa fa-plus"></i> Ajouter un Don');
            $('#btnText').text('Sauvegarder');
            $('#date_don').val(new Date().toISOString().split('T')[0]);
            $('#donModal').modal('show');
        }

        function createDon(data) {
            $.ajax({
                url: API_URL,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function() {
                    $('#donModal').modal('hide');
                    showToast('Don ajouté avec succès !', 'success');
                    loadDons();
                },
                error: function(xhr) {
                    var msg = 'Erreur lors de la création.';
                    if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                    $('#formError').text(msg).show();
                }
            });
        }

        function editDon(id) {
            $.ajax({
                url: API_URL + '/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(don) {
                    $('#donId').val(don.id);
                    $('#nom').val(don.nom);
                    $('#montant').val(don.montant);
                    $('#date_don').val(don.date_don);
                    $('#formError').hide();
                    $('#modalTitle').html('<i class="fa fa-pencil"></i> Modifier le Don');
                    $('#btnText').text('Mettre à jour');
                    $('#donModal').modal('show');
                },
                error: function() {
                    showToast('Impossible de charger le don.', 'error');
                }
            });
        }

        function updateDon(id, data) {
            $.ajax({
                url: API_URL + '/' + id,
                type: 'PUT',
                data: data,
                dataType: 'json',
                success: function() {
                    $('#donModal').modal('hide');
                    showToast('Don modifié avec succès !', 'success');
                    loadDons();
                },
                error: function(xhr) {
                    var msg = 'Erreur lors de la mise à jour.';
                    if (xhr.responseJSON && xhr.responseJSON.error) msg = xhr.responseJSON.error;
                    $('#formError').text(msg).show();
                }
            });
        }

        function deleteDon(id) {
            deleteId = id;
            $('#deleteModal').modal('show');
        }

        function confirmDelete() {
            if (!deleteId) return;
            $.ajax({
                url: API_URL + '/' + deleteId,
                type: 'DELETE',
                dataType: 'json',
                success: function() {
                    $('#deleteModal').modal('hide');
                    showToast('Don supprimé avec succès !', 'success');
                    deleteId = null;
                    loadDons();
                },
                error: function() {
                    $('#deleteModal').modal('hide');
                    showToast('Erreur lors de la suppression.', 'error');
                    deleteId = null;
                }
            });
        }

        function formatMontant(val) {
            return parseFloat(val).toLocaleString('fr-FR');
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            var parts = dateStr.split('-');
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }

        function escapeHtml(str) {
            if (!str) return '';
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
    </script>
</body>
</html>
