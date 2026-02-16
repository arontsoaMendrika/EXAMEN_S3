<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-hand-holding-heart" style="color:var(--primary);"></i> Gestion des Dons</h1>
        <p>BNGRC — Suivi des collectes et distributions pour les sinistrés</p>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
                <h3 id="totalDons">0</h3>
                <p>Total des dons</p>
            </div>
        </div>
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <h3 id="totalMontant">0 Ar</h3>
                <p>Montant total collecté</p>
            </div>
        </div>
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon accent">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 id="dernierDon">-</h3>
                <p>Dernier don reçu</p>
            </div>
        </div>
    </div>

    <!-- Liste des dons -->
    <div class="card-custom animate-in">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
            <div class="card-title" style="margin-bottom:0;">
                <i class="fa-solid fa-table-list"></i> Liste des Dons
            </div>
            <button class="btn-primary-custom" onclick="openAddModal()">
                <i class="fa-solid fa-plus"></i> Ajouter un Don
            </button>
        </div>

        <div id="loading" class="loading-spinner">
            <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
            <p class="mt-3">Chargement...</p>
        </div>

        <div class="table-responsive" id="tableContainer" style="display:none;">
            <table class="table-custom" id="donsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><i class="fa-solid fa-user"></i> Nom du donateur</th>
                        <th><i class="fa-solid fa-coins"></i> Montant (Ar)</th>
                        <th><i class="fa-solid fa-calendar"></i> Date du don</th>
                        <th><i class="fa-solid fa-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="emptyMessage" style="display:none;">
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Aucun don enregistré pour le moment.</p>
                <button class="btn-primary-custom" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Ajouter le premier don
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajouter/Modifier -->
<div class="modal fade" id="donModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><i class="fa-solid fa-plus"></i> Ajouter un Don</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer" style="background:none;border:none;color:#fff;font-size:20px;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="donForm">
                    <input type="hidden" id="donId">
                    <div class="form-group mb-3">
                        <label for="nom" style="font-weight:700;font-size:13px;color:var(--dark-600);margin-bottom:6px;display:block;">
                            <i class="fa-solid fa-user"></i> Nom du donateur
                        </label>
                        <input type="text" class="form-control-custom" id="nom" placeholder="Ex: ONG Internationale" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="montant" style="font-weight:700;font-size:13px;color:var(--dark-600);margin-bottom:6px;display:block;">
                            <i class="fa-solid fa-coins"></i> Montant (Ar)
                        </label>
                        <input type="number" step="0.01" min="0" class="form-control-custom" id="montant" placeholder="Ex: 50000" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_don" style="font-weight:700;font-size:13px;color:var(--dark-600);margin-bottom:6px;display:block;">
                            <i class="fa-solid fa-calendar-days"></i> Date du don
                        </label>
                        <input type="date" class="form-control-custom" id="date_don" required>
                    </div>
                    <div id="formError" class="alert alert-danger" style="display:none;border-radius:var(--radius);"></div>
                    <button type="submit" class="btn-primary-custom" style="width:100%;justify-content:center;padding:12px;">
                        <i class="fa-solid fa-floppy-disk"></i> <span id="btnText">Sauvegarder</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--danger);">
                <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation"></i> Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer" style="background:none;border:none;color:#fff;font-size:20px;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center" style="padding:30px;">
                <i class="fa-solid fa-trash" style="font-size:48px;color:var(--danger);margin-bottom:16px;"></i>
                <p style="font-weight:700;font-size:16px;margin-bottom:4px;">Supprimer ce don ?</p>
                <p style="color:var(--dark-400);font-size:13px;">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer" style="justify-content:center;gap:12px;">
                <button type="button" class="btn-outline-custom" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn-danger-custom" id="confirmDeleteBtn" onclick="confirmDelete()" style="padding:10px 20px;">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div class="toast-custom" id="toast">
    <i class="fa-solid" id="toastIcon"></i> <span id="toastMsg"></span>
</div>

<script>
    var API_URL = '<?php echo $base_url; ?>api/dons';
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
        var bg = type === 'success' ? '#10B981' : type === 'error' ? '#EF4444' : '#6366F1';
        var icon = type === 'success' ? 'fa-circle-check' : type === 'error' ? 'fa-circle-xmark' : 'fa-circle-info';
        $('#toast').css('background', bg);
        $('#toastIcon').attr('class', 'fa-solid ' + icon);
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
                                '<td><strong>' + escapeHtml(don.nom) + '</strong></td>' +
                                '<td class="montant-col">' + formatMontant(don.montant) + ' Ar</td>' +
                                '<td>' + formatDate(don.date_don) + '</td>' +
                                '<td>' +
                                    '<button class="btn-warning-custom" onclick="editDon(' + don.id + ')" style="margin-right:6px;">' +
                                        '<i class="fa-solid fa-pen"></i> Modifier' +
                                    '</button>' +
                                    '<button class="btn-danger-custom" onclick="deleteDon(' + don.id + ')">' +
                                        '<i class="fa-solid fa-trash"></i> Supprimer' +
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
                    '<i class="fa-solid fa-triangle-exclamation"></i> Erreur de chargement des dons' +
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
        $('#modalTitle').html('<i class="fa-solid fa-plus"></i> Ajouter un Don');
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
                $('#modalTitle').html('<i class="fa-solid fa-pen"></i> Modifier le Don');
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

<?php include 'footer.php'; ?>
