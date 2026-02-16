
<?php
// Vue pour afficher les dons par ville
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dons par ville</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Dons par ville</h1>
    <form method="post" action="/dons/create">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="nom" class="form-control" placeholder="Nom du don" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="montant" class="form-control" placeholder="Montant" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="date_don" class="form-control" required>
            </div>
            <div class="col-md-3">
                <select name="ville" class="form-control" required>
                    <option value="">Ville</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= htmlspecialchars($ville['nom']) ?>"> <?= htmlspecialchars($ville['nom']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-2">Ajouter don</button>
    </form>
    <hr>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Ville</th>
                <th>Nom</th>
                <th>Montant</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dons as $don): ?>
                <tr>
                    <td><?= htmlspecialchars($don['ville'] ?? '') ?></td>
                    <td><?= htmlspecialchars($don['nom']) ?></td>
                    <td><?= htmlspecialchars($don['montant']) ?></td>
                    <td><?= htmlspecialchars($don['date_don']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>