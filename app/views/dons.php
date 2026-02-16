<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dons</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Dons</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addDonModal">Ajouter un Don</button>
        <table class="table table-striped" id="donsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Modal for adding/editing don -->
    <div class="modal fade" id="addDonModal" tabindex="-1" role="dialog" aria-labelledby="addDonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDonModalLabel">Ajouter un Don</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="donForm">
                        <input type="hidden" id="donId">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="montant">Montant</label>
                            <input type="number" step="0.01" class="form-control" id="montant" required>
                        </div>
                        <div class="form-group">
                            <label for="date_don">Date</label>
                            <input type="date" class="form-control" id="date_don" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/template/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            loadDons();

            $('#donForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#donId').val();
                const data = {
                    nom: $('#nom').val(),
                    montant: parseFloat($('#montant').val()),
                    date_don: $('#date_don').val()
                };
                if (id) {
                    updateDon(id, data);
                } else {
                    createDon(data);
                }
            });

            $('#addDonModal').on('show.bs.modal', function() {
                $('#donForm')[0].reset();
                $('#donId').val('');
            });
        });

        function loadDons() {
            $.get('/api/dons', function(data) {
                const tbody = $('#donsTable tbody');
                tbody.empty();
                data.forEach(don => {
                    tbody.append(`
                        <tr>
                            <td>${don.id}</td>
                            <td>${don.nom}</td>
                            <td>${don.montant}</td>
                            <td>${don.date_don}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editDon(${don.id})">Modifier</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteDon(${don.id})">Supprimer</button>
                            </td>
                        </tr>
                    `);
                });
            });
        }

        function createDon(data) {
            $.post('/api/dons', data, function() {
                $('#addDonModal').modal('hide');
                loadDons();
            });
        }

        function editDon(id) {
            $.get(`/api/dons/${id}`, function(don) {
                $('#donId').val(don.id);
                $('#nom').val(don.nom);
                $('#montant').val(don.montant);
                $('#date_don').val(don.date_don);
                $('#addDonModal').modal('show');
            });
        }

        function updateDon(id, data) {
            $.ajax({
                url: `/api/dons/${id}`,
                type: 'PUT',
                data: data,
                success: function() {
                    $('#addDonModal').modal('hide');
                    loadDons();
                }
            });
        }

        function deleteDon(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce don ?')) {
                $.ajax({
                    url: `/api/dons/${id}`,
                    type: 'DELETE',
                    success: function() {
                        loadDons();
                    }
                });
            }
        }
    </script>
</body>
</html>