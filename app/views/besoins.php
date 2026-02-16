<?php include 'header.php'; ?>

<main>
    <div class="container mt-5 p-4 rounded shadow bg-white">
        <h1 class="mb-4 text-primary">Besoins par ville</h1>
        <form method="post" action="/besoins/create" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="titre" class="form-control border-primary" placeholder="Titre" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control border-primary" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantite" class="form-control border-primary" placeholder="Quantité" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="prix_unitaire" class="form-control border-primary" placeholder="Prix unitaire" required>
                </div>
                <div class="col-md-2">
                    <select name="ville" class="form-select border-primary" required>
                        <option value="">Ville</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville['nom']) ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">Ajouter besoin</button>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-4 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Ville</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Categorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($besoins as $besoin): ?>
                        <tr>
                            <td><?= htmlspecialchars($besoin['ville'] ?? 'Non renseignée') ?></td>
                            <td><?= htmlspecialchars($besoin['titre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['quantite'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['prix_unitaire'] ?? '') ?></td>
                            <td><?= htmlspecialchars($besoin['categorie_nom'] ?? '') ?></td>
                            <td>
                                <a href="/besoins/edit?id=<?= $besoin['id'] ?>" class="btn btn-sm btn-warning me-1">Modifier</a>
                                <a href="/besoins/delete?id=<?= $besoin['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce besoin ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
