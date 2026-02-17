<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-cog" style="color:var(--secondary);"></i> Configuration</h1>
        <p>Paramètres applicatifs</p>
    </div>

    <div class="card-custom animate-in">
        <div class="card-title">
            <i class="fa-solid fa-sliders"></i> Frais de gestion
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="border-radius:12px;">
                <i class="fa-solid fa-circle-check"></i> Sauvegardé.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" style="border-radius:12px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo $base_url; ?>config/update" style="max-width:420px;padding:15px;">
            <div class="mb-3">
                <label class="form-label fw-bold">Pourcentage des frais (%)</label>
                <input type="number" name="frais_achat_percent" class="form-control" step="0.1" min="0" max="100" required value="<?php echo htmlspecialchars($frais_percent); ?>">
                <div class="form-text">Valeur en pourcent (ex: 5 pour 5%).</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="fa-solid fa-save"></i> Enregistrer</button>
                <a href="<?php echo $base_url; ?>" class="btn-outline-custom">Retour</a>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
