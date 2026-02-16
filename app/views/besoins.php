<?php
// Vue pour afficher les besoins par ville
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Besoins par ville</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Besoins par ville</h1>
    <form method="post" action="/besoins/create">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="titre" class="form-control" placeholder="Titre" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="description" class="form-control" placeholder="Description" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantite" class="form-control" placeholder="Quantité" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="prix_unitaire" class="form-control" placeholder="Prix unitaire" required>
            </div>
            <div class="col-md-2">
                <select name="ville" class="form-control" required>
                    <option value="">Ville</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= htmlspecialchars($ville['nom']) ?>"><?= htmlspecialchars($ville['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Ajouter besoin</button>
    </form>
    <hr>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Ville</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Categorie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($besoins as $besoin): ?>
                <tr>
                    <td><?= htmlspecialchars($besoin['ville']) ?></td>
                    <td><?= htmlspecialchars($besoin['titre']) ?></td>
                    <td><?= htmlspecialchars($besoin['description']) ?></td>
                    <td><?= htmlspecialchars($besoin['quantite'] ?? '') ?></td>
                    <td><?= htmlspecialchars($besoin['prix_unitaire'] ?? '') ?></td>
                    <td><?= htmlspecialchars($besoin['categorie_nom'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
