<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-clipboard-list" style="color:var(--secondary);"></i> Besoins par ville</h1>
        <p>Gérer et suivre les besoins des villes sinistrées</p>
    </div>

    <!-- Formulaire d'ajout / modification -->
    <div class="card-custom animate-in">
        <div class="card-title">
            <?php if (!empty($edit_mode) && isset($besoin)): ?>
                <i class="fa-solid fa-pen-to-square"></i> Modifier le besoin
            <?php else: ?>
                <i class="fa-solid fa-plus-circle"></i> Ajouter un besoin
            <?php endif; ?>
        </div>
        <form method="post" action="<?php echo $base_url; ?><?php echo (!empty($edit_mode)) ? 'besoins/update' : 'besoins/create'; ?>">
            <?php if (!empty($edit_mode) && isset($besoin)): ?>
                <input type="hidden" name="id" value="<?= $besoin['id'] ?>">
            <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="titre" class="form-control-custom" placeholder="Titre" value="<?= htmlspecialchars($besoin['titre'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control-custom" placeholder="Description" value="<?= htmlspecialchars($besoin['description'] ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantite" class="form-control-custom" placeholder="Quantité" value="<?= htmlspecialchars($besoin['quantite'] ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="prix_unitaire" class="form-control-custom" placeholder="Prix unitaire" value="<?= htmlspecialchars($besoin['prix_unitaire'] ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <select name="ville" class="form-select-custom" required>
                        <option value="">Ville</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= htmlspecialchars($v['nom']) ?>" <?= (isset($besoin['ville']) && $besoin['ville'] === $v['nom']) ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn-primary-custom flex-grow-1" style="justify-content:center;">
                    <i class="fa-solid fa-<?= (!empty($edit_mode)) ? 'floppy-disk' : 'plus' ?>"></i> 
                    <?= (!empty($edit_mode)) ? 'Mettre à jour' : 'Ajouter besoin' ?>
                </button>
                <?php if (!empty($edit_mode)): ?>
                    <a href="<?php echo $base_url; ?>besoins" class="btn-outline-custom">
                        <i class="fa-solid fa-xmark"></i> Annuler
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table des besoins -->
    <div class="card-custom animate-in">
        <div class="card-title">
            <i class="fa-solid fa-table-list"></i> Liste des besoins
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-city"></i> Ville</th>
                        <th><i class="fa-solid fa-heading"></i> Titre</th>
                        <th><i class="fa-solid fa-align-left"></i> Description</th>
                        <th><i class="fa-solid fa-cubes-stacked"></i> Quantité</th>
                        <th><i class="fa-solid fa-coins"></i> Prix unitaire</th>
                        <th><i class="fa-solid fa-tags"></i> Catégorie</th>
                        <th><i class="fa-solid fa-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($besoins as $besoin): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($besoin['ville'] ?? 'Non renseignée') ?></strong></td>
                            <td><?= htmlspecialchars($besoin['titre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['quantite'] ?? '') ?></td>
                            <td class="montant-col"><?= htmlspecialchars($besoin['prix_unitaire'] ?? '') ?></td>
                            <td><span class="badge-custom primary"><?= htmlspecialchars($besoin['categorie_nom'] ?? '') ?></span></td>
                            <td>
                                <a href="<?php echo $base_url; ?>besoins/edit?id=<?= $besoin['id'] ?>" class="btn-warning-custom">
                                    <i class="fa-solid fa-pen"></i> Modifier
                                </a>
                                <a href="<?php echo $base_url; ?>besoins/delete?id=<?= $besoin['id'] ?>" class="btn-danger-custom" onclick="return confirm('Supprimer ce besoin ?');">
                                    <i class="fa-solid fa-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
