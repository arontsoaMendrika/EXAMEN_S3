<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-flask" style="color:var(--secondary);"></i> Simulation d'Achat</h1>
        <p>Simuler un achat avant de le valider</p>
    </div>

    <div class="row g-4 animate-in">
        <!-- Formulaire de simulation -->
        <div class="col-lg-5">
            <div class="card-custom">
                <div class="card-title">
                    <i class="fa-solid fa-sliders"></i> Paramètres de simulation
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fa-solid fa-clipboard-list"></i> Besoin</label>
                    <select class="form-select" id="simBesoin">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($besoins as $b): ?>
                            <option value="<?php echo $b['id']; ?>" data-prix="<?php echo $b['prix']; ?>" data-nom="<?php echo htmlspecialchars($b['nom']); ?>">
                                <?php echo htmlspecialchars($b['nom']); ?> (<?php echo number_format($b['prix'], 0, ',', ' '); ?> Ar/unité)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fa-solid fa-hand-holding-heart"></i> Don (source)</label>
                    <select class="form-select" id="simDon">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($dons as $d): ?>
                            <option value="<?php echo $d['id']; ?>">
                                <?php echo htmlspecialchars($d['nom']); ?> (<?php echo number_format($d['montant'], 0, ',', ' '); ?> Ar)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="fa-solid fa-cubes-stacked"></i> Quantité</label>
                    <input type="number" class="form-control" id="simQuantite" min="1" value="1">
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn-primary-custom flex-grow-1" id="btnSimuler">
                        <i class="fa-solid fa-flask"></i> Simuler
                    </button>
                    <button type="button" class="btn-success-custom flex-grow-1" id="btnValider" disabled>
                        <i class="fa-solid fa-check-circle"></i> Valider
                    </button>
                </div>
            </div>
        </div>

        <!-- Distribution globale -->
        <div class="col-12">
            <div class="card-custom">
                <div class="card-title"><i class="fa-solid fa-share-nodes"></i> Distribution automatique (simulation)</div>
                <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="applyToBesoin">
                                <label class="form-check-label" for="applyToBesoin">Appliquer au besoin sélectionné</label>
                            </div>
                        </div>
                    <div class="col-md-3">
                        <button id="btnDist" class="btn-primary-custom">Simuler distribution</button>
                    </div>
                    <div class="col-md-5 text-end">
                        <button id="btnReset" class="btn-danger-custom">Tout réinitialiser</button>
                    </div>
                </div>

                <div id="distResult" style="margin-top:12px;display:none;"></div>
            </div>
        </div>

        <!-- Résultat de la simulation -->
        <div class="col-lg-7">
            <div class="card-custom" id="resultCard">
                <div class="card-title">
                    <i class="fa-solid fa-chart-bar"></i> Résultat de la simulation
                </div>

                <!-- État initial -->
                <div id="simPlaceholder" class="text-center" style="padding:40px;">
                    <i class="fa-solid fa-flask" style="font-size:3em;color:var(--text-muted);opacity:.3;"></i>
                    <p style="color:var(--text-muted);margin-top:12px;">Remplissez les paramètres et cliquez sur <strong>Simuler</strong></p>
                </div>

                <!-- Résultat -->
                <div id="simResult" style="display:none;">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <div class="stat-card" style="border-left:4px solid var(--primary);">
                                <div class="stat-label">Besoin</div>
                                <div class="stat-value" id="resBesoin" style="font-size:1em;">-</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="stat-card" style="border-left:4px solid var(--secondary);">
                                <div class="stat-label">Quantité × Prix unitaire</div>
                                <div class="stat-value" id="resDetail" style="font-size:1em;">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-custom" style="background:var(--bg-light);padding:20px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Montant de base :</span>
                            <strong id="resMontantBase">0 Ar</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frais de gestion (<?php echo htmlspecialchars($frais_percent); ?>%) :</span>
                            <strong id="resFrais" style="color:var(--accent);">0 Ar</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold" style="font-size:1.1em;">Total TTC :</span>
                            <strong id="resTotal" style="color:var(--primary);font-size:1.2em;">0 Ar</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Solde restant après achat :</span>
                            <strong id="resSolde" style="color:var(--secondary);">0 Ar</strong>
                        </div>
                    </div>
                </div>

                <!-- Erreur -->
                <div id="simError" style="display:none;">
                    <div class="alert alert-danger" style="border-radius:12px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span id="simErrorMsg"></span>
                    </div>
                </div>

                <!-- Succès validation -->
                <div id="simSuccess" style="display:none;">
                    <div class="alert alert-success" style="border-radius:12px;">
                        <i class="fa-solid fa-circle-check"></i> <span id="simSuccessMsg"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
const BASE_URL = '<?php echo $base_url; ?>';
const FRAIS_PERCENT = <?php echo intval($frais_percent); ?>;

function formatMontant(n) {
    return parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
}

// Bouton Simuler (Ajax, pas de sauvegarde)
$('#btnSimuler').on('click', function() {
    const besoin_id = $('#simBesoin').val();
    const don_id = $('#simDon').val();
    const quantite = parseInt($('#simQuantite').val()) || 0;

    if (!besoin_id || !don_id || quantite <= 0) {
        $('#simPlaceholder, #simResult, #simSuccess').hide();
        $('#simError').show();
        $('#simErrorMsg').text('Veuillez remplir tous les champs correctement.');
        $('#btnValider').prop('disabled', true);
        return;
    }

    $.ajax({
        url: BASE_URL + 'api/achats/simuler',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ besoin_id, don_id, quantite }),
        success: function(res) {
            if (res.success) {
                const d = res.data;
                $('#simPlaceholder, #simError, #simSuccess').hide();
                $('#simResult').show();

                $('#resBesoin').text(d.besoin_nom);
                $('#resDetail').text(quantite + ' × ' + formatMontant(d.prix_unitaire) + ' Ar');
                $('#resMontantBase').text(formatMontant(d.montant_base) + ' Ar');
                $('#resFrais').text(formatMontant(d.frais) + ' Ar');
                $('#resTotal').text(formatMontant(d.montant_total) + ' Ar');
                $('#resSolde').text(formatMontant(d.solde_restant) + ' Ar');

                $('#btnValider').prop('disabled', false);
            } else {
                $('#simPlaceholder, #simResult, #simSuccess').hide();
                $('#simError').show();
                $('#simErrorMsg').text(res.error);
                $('#btnValider').prop('disabled', true);
            }
        },
        error: function() {
            $('#simPlaceholder, #simResult, #simSuccess').hide();
            $('#simError').show();
            $('#simErrorMsg').text('Erreur de connexion au serveur.');
            $('#btnValider').prop('disabled', true);
        }
    });
});

// Bouton Valider (Ajax, sauvegarde)
$('#btnValider').on('click', function() {
    const besoin_id = $('#simBesoin').val();
    const don_id = $('#simDon').val();
    const quantite = parseInt($('#simQuantite').val()) || 0;

    if (!confirm('Confirmer cet achat ?')) return;

    $.ajax({
        url: BASE_URL + 'api/achats/valider',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ besoin_id, don_id, quantite }),
        success: function(res) {
            if (res.success) {
                $('#simError').hide();
                $('#simSuccess').show();
                $('#simSuccessMsg').text(res.message);
                $('#btnValider').prop('disabled', true);
            } else {
                $('#simSuccess').hide();
                $('#simError').show();
                $('#simErrorMsg').text(res.error);
            }
        },
        error: function() {
            $('#simSuccess').hide();
            $('#simError').show();
            $('#simErrorMsg').text('Erreur de connexion au serveur.');
        }
    });
});

// Simuler distribution globale
    $('#btnDist').on('click', function() {
    // remove interactive mode selection: default to priority
    const mode = 'priority';
    const apply = $('#applyToBesoin').is(':checked');
    const besoin_id = apply ? $('#simBesoin').val() : null;
    $('#distResult').hide().html('Chargement...').show();
    $.ajax({
        url: BASE_URL + 'api/achats/distribute',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ mode, besoin_id }),
        success: function(res) {
            if (res.success) {
                // Group by ville
                const byVille = {};
                res.summary.forEach(function(s) {
                    const ville = s.ville_nom ? s.ville_nom : 'Autres';
                    if (!byVille[ville]) byVille[ville] = [];
                    byVille[ville].push(s);
                });

                let html = '<div class="table-responsive">';
                for (const villeName in byVille) {
                    html += '<h4 style="margin-top:12px;">Ville: ' + (villeName === 'Autres' ? '-' : villeName) + '</h4>';
                    html += '<table class="table-custom" style="margin-bottom:12px;"><thead><tr><th>Besoin</th><th>Total Besoin</th><th>Attribué</th><th>Statut</th><th>Détails</th></tr></thead><tbody>';
                    byVille[villeName].forEach(function(s) {
                        let details = '';
                        s.allocations.forEach(function(a){ details += 'Don #' + a.don_id + ': ' + formatMontant(a.montant) + ' Ar<br>'; });
                        const statutLabel = s.statut === 'comble' ? '<span class="badge bg-success">Comblé</span>' : '<span class="badge bg-warning">En attente</span>';
                        html += '<tr><td><strong>' + s.besoin_nom + '</strong></td><td>' + formatMontant(s.montant_total) + ' Ar</td><td>' + formatMontant(s.montant_attribue) + ' Ar</td><td>' + statutLabel + '</td><td>' + details + '</td></tr>';
                    });
                    html += '</tbody></table>';
                }
                html += '</div>';
                $('#distResult').html(html);
            } else {
                $('#distResult').html('<div class="alert alert-danger">' + (res.error||'Erreur') + '</div>');
            }
        },
        error: function() { $('#distResult').html('<div class="alert alert-danger">Erreur de connexion</div>'); }
    });
});

// Reset DB (partial)
$('#btnReset').on('click', function() {
    if (!confirm('Réinitialiser toutes les données (achats) et réappliquer le jeu d\'initialisation ?')) return;
    $('#btnReset').prop('disabled', true).text('Réinitialisation...');
    $.ajax({
        url: BASE_URL + 'api/achats/reset',
        method: 'POST',
        success: function(res) {
            $('#btnReset').prop('disabled', false).text('Tout réinitialiser');
            if (res.success) {
                alert('Réinitialisation effectuée.');
                location.reload();
            } else {
                alert('Erreur: ' + (res.error || 'Impossible de réinitialiser'));
            }
        },
        error: function() { $('#btnReset').prop('disabled', false).text('Tout réinitialiser'); alert('Erreur de connexion'); }
    });
});
</script>

<?php include 'footer.php'; ?>
