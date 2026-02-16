<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-city" style="color:var(--accent);"></i> Liste des villes</h1>
        <p>Gérer les villes sinistrées enregistrées</p>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="card-custom animate-in">
        <div class="card-title">
            <i class="fa-solid fa-plus-circle"></i> Ajouter une ville
        </div>
        <form method="post" action="<?php echo $base_url; ?>villes/create">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="nom" class="form-control-custom" placeholder="Nom de la ville" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn-primary-custom" style="width:100%;justify-content:center;padding:10px 20px;">
                        <i class="fa-solid fa-plus"></i> Ajouter ville
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table des villes -->
    <div class="card-custom animate-in">
        <div class="card-title">
            <i class="fa-solid fa-table-list"></i> Villes enregistrées
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-city"></i> Nom de la ville</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($villes as $ville): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($ville['nom']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
