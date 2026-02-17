<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-chart-pie" style="color:var(--secondary);"></i> Récapitulation</h1>
        <p>Vue d'ensemble des besoins et achats effectués</p>
    </div>

    <div class="animate-in">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="card-title mb-0">
                    <i class="fa-solid fa-table-list"></i> Tableau récapitulatif
                </div>
                <button type="button" class="btn-primary-custom" id="btnActualiser">
                    <i class="fa-solid fa-rotate" id="iconActualiser"></i> Actualiser
                </button>
            </div>

            <div class="table-responsive">
                <table class="table-custom" id="recapTable">
                    <thead>
                        <tr>
                            <th>Besoin</th>
                            <th>Prix unitaire (Ar)</th>
                            <th>Besoin total (Ar)</th>
                            <th>Satisfait (Ar)</th>
                            <th>Reste (Ar)</th>
                            <th>Progression</th>
                        </tr>
                    </thead>
                    <tbody id="recapBody">
                        <tr>
                            <td colspan="6" class="text-center" style="padding:30px;color:var(--text-muted);">
                                <i class="fa-solid fa-spinner fa-spin"></i> Chargement...
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="recapFoot" style="display:none;">
                        <tr style="background:var(--bg-light);font-weight:700;">
                            <td colspan="2">TOTAL</td>
                            <td id="footTotal">0</td>
                            <td id="footSatisfait">0</td>
                            <td id="footReste">0</td>
                            <td id="footProg">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
const BASE_URL = '<?php echo $base_url; ?>';

function formatMontant(n) {
    return parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
}

function chargerRecap() {
    const icon = $('#iconActualiser');
    icon.addClass('fa-spin');

    $.ajax({
        url: BASE_URL + 'api/recap',
        method: 'GET',
        success: function(res) {
            icon.removeClass('fa-spin');
            if (res.success && res.data.length > 0) {
                let html = '';
                let totalBesoin = 0, totalSatisfait = 0, totalReste = 0;

                res.data.forEach(function(r) {
                    const pct = r.montant_total_besoin > 0 ? Math.min((r.montant_satisfait / r.montant_total_besoin) * 100, 100) : 0;
                    let barColor = 'var(--accent)';
                    if (pct >= 75) barColor = 'var(--primary)';
                    else if (pct >= 40) barColor = 'var(--secondary)';

                    totalBesoin += parseFloat(r.montant_total_besoin);
                    totalSatisfait += parseFloat(r.montant_satisfait);
                    totalReste += parseFloat(r.montant_restant);

                    html += '<tr>';
                    html += '<td><strong>' + r.nom + '</strong></td>';
                    html += '<td>' + formatMontant(r.prix) + '</td>';
                    html += '<td>' + formatMontant(r.montant_total_besoin) + '</td>';
                    html += '<td style="color:var(--primary);">' + formatMontant(r.montant_satisfait) + '</td>';
                    html += '<td style="color:var(--accent);">' + formatMontant(r.montant_restant) + '</td>';
                    html += '<td style="min-width:150px;">';
                    html += '<div style="background:#e9ecef;border-radius:8px;height:20px;overflow:hidden;">';
                    html += '<div style="width:' + pct.toFixed(1) + '%;background:' + barColor + ';height:100%;border-radius:8px;transition:width .6s;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75em;font-weight:600;">';
                    html += pct >= 10 ? pct.toFixed(0) + '%' : '';
                    html += '</div></div>';
                    html += '</td>';
                    html += '</tr>';
                });

                $('#recapBody').html(html);
                $('#recapFoot').show();

                const pctTotal = totalBesoin > 0 ? (totalSatisfait / totalBesoin * 100) : 0;
                $('#footTotal').text(formatMontant(totalBesoin) + ' Ar');
                $('#footSatisfait').text(formatMontant(totalSatisfait) + ' Ar');
                $('#footReste').text(formatMontant(totalReste) + ' Ar');
                $('#footProg').text(pctTotal.toFixed(1) + '%');
            } else {
                $('#recapBody').html('<tr><td colspan="6" class="text-center" style="padding:30px;color:var(--text-muted);"><i class="fa-solid fa-inbox"></i> Aucun achat effectué</td></tr>');
                $('#recapFoot').hide();
            }
        },
        error: function() {
            icon.removeClass('fa-spin');
            $('#recapBody').html('<tr><td colspan="6" class="text-center" style="color:var(--accent);padding:20px;"><i class="fa-solid fa-triangle-exclamation"></i> Erreur de connexion</td></tr>');
        }
    });
}

// Bouton Actualiser (AJAX sans recharger la page)
$('#btnActualiser').on('click', function() {
    chargerRecap();
});

// Chargement initial
$(document).ready(function() {
    chargerRecap();
});
</script>

<?php include 'footer.php'; ?>
