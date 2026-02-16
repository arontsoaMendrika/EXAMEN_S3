<?php
// Vue pour afficher les villes
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des villes</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h1>Liste des villes</h1>
    <form method="post" action="/villes/create">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="nom" class="form-control" placeholder="Nom de la ville" required>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-info">Ajouter ville</button>
            </div>
        </div>
    </form>
    <hr>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($villes as $ville): ?>
                <tr>
                    <td><?= htmlspecialchars($ville['nom']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
