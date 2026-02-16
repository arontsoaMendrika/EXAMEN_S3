<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-gauge-high" style="color:var(--primary);"></i> Tableau de bord</h1>
        <p>Application de Suivi des Collectes et Distributions de Dons — BNGRC</p>
    </div>

    <?php if(!empty($message)) { ?>
    <div class="card-custom animate-in" style="border-left: 4px solid var(--primary);">
        <p style="margin:0;font-weight:700;color:var(--primary);">
            <i class="fa-solid fa-circle-info"></i> <?=$message?>
        </p>
    </div>
    <?php } ?>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <h3>Dons</h3>
                <p>Gérer les collectes</p>
                <a href="<?php echo $base_url; ?>dons" class="btn-primary-custom mt-3" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-arrow-right"></i> Accéder
                </a>
            </div>
        </div>
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h3>Besoins</h3>
                <p>Gérer les besoins par ville</p>
                <a href="<?php echo $base_url; ?>besoins" class="btn-secondary-custom mt-3" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-arrow-right"></i> Accéder
                </a>
            </div>
        </div>
        <div class="col-md-4 animate-in">
            <div class="stat-card">
                <div class="stat-icon accent">
                    <i class="fa-solid fa-city"></i>
                </div>
                <h3>Villes</h3>
                <p>Gérer les villes sinistrées</p>
                <a href="<?php echo $base_url; ?>villes" class="btn-warning-custom mt-3" style="width:100%;justify-content:center;padding:10px 20px;">
                    <i class="fa-solid fa-arrow-right"></i> Accéder
                </a>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>