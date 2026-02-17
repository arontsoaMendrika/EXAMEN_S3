<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-cart-shopping" style="color:var(--primary);"></i> Gestion des Achats</h1>
        <p>Acheter des fournitures pour les besoins sinistrés</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;">
            <i class="fa-solid fa-circle-check"></i> Achat enregistré avec succès !
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 animate-in">
        <!-- Formulaire d'achat -->
        <div class="col-lg-5">
            <div class="card-custom">
                <div class="card-title">
                    <i class="fa-solid fa-plus-circle"></i> Nouvel Achat
                </div>
                <form action="<?php echo $base_url; ?>achats/create" method="POST" id="formAchat">
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-clipboard-list"></i> Besoin</label>
                        <select name="besoin_id" class="form-select" required id="selectBesoin">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($besoins as $b): ?>
                                <option value="<?php echo $b['id']; ?>" data-prix="<?php echo $b['prix']; ?>">
                                    <?php echo htmlspecialchars($b['nom']); ?> (<?php echo number_format($b['prix'], 0, ',', ' '); ?> Ar)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-hand-holding-heart"></i> Don (source)</label>
                        <select name="don_id" class="form-select" required id="selectDon">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($dons as $d): ?>
                                <option value="<?php echo $d['id']; ?>">
                                    <?php echo htmlspecialchars($d['nom']); ?> (<?php echo number_format($d['montant'], 0, ',', ' '); ?> Ar)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fa-solid fa-coins"></i> Montant (Ar)</label>
                        <input type="number" name="montant" class="form-control" step="0.01" min="0.01" required id="inputMontant">
                    </div>

                    <!-- Calcul avec frais en temps réel -->
                    <div class="card-custom" style="background:var(--bg-light);padding:15px;margin-bottom:15px;" id="calcFrais">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Montant de base :</span>
                            <strong id="montantBase">0 Ar</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                                <span>Frais de gestion (<?php echo htmlspecialchars($frais_percent); ?>%) :</span>
                                <strong id="montantFrais" style="color:var(--accent);">0 Ar</strong>
                        </div>
                        <hr style="margin:8px 0;">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total TTC :</span>
                            <strong id="montantTotal" style="color:var(--primary);font-size:1.1em;">0 Ar</strong>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-custom w-100">
                        <i class="fa-solid fa-check"></i> Valider l'achat
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des achats avec filtre ville -->
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="card-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span><i class="fa-solid fa-list"></i> Liste des achats</span>
                    <!-- Filtre par ville -->
                    <form method="GET" action="<?php echo $base_url; ?>achats" class="d-flex gap-2 align-items-center" style="min-width:250px;">
                        <select name="ville_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php echo (isset($ville_id) && $ville_id == $v['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($v['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th><i class="fa-solid fa-calendar"></i> Date</th>
                                <th><i class="fa-solid fa-clipboard-list"></i> Besoin</th>
                                <th><i class="fa-solid fa-user"></i> Donateur</th>
                                <th><i class="fa-solid fa-coins"></i> Montant (Ar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($achats)): ?>
                                <?php foreach ($achats as $a): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($a['date_achat'])); ?></td>
                                        <td><span class="badge-custom primary"><?php echo htmlspecialchars($a['besoin_nom']); ?></span></td>
                                        <td><?php echo htmlspecialchars($a['donateur_nom']); ?></td>
                                        <td class="montant-col"><?php echo number_format($a['montant'], 2, ',', ' '); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:30px;">
                                        <i class="fa-solid fa-inbox" style="font-size:2em;color:var(--text-muted);"></i>
                                        <p style="color:var(--text-muted);margin-top:8px;">Aucun achat trouvé.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Calcul des frais en temps réel
const FRAIS_PERCENT = <?php echo intval($frais_percent); ?>;
const FRAIS_RATE = FRAIS_PERCENT / 100.0;
document.getElementById('inputMontant').addEventListener('input', function() {
    const montant = parseFloat(this.value) || 0;
    const frais = montant * FRAIS_RATE;
    const total = montant + frais;

    document.getElementById('montantBase').textContent = formatMontant(montant) + ' Ar';
    document.getElementById('montantFrais').textContent = formatMontant(frais) + ' Ar';
    document.getElementById('montantTotal').textContent = formatMontant(total) + ' Ar';
});

function formatMontant(n) {
    return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
}
</script>

<?php include 'footer.php'; ?>
