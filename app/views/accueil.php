<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Villes et Sinistres</title>
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Tableau de bord - Villes affectées par les sinistres</h1>
        
        <?php if (!empty($sinistres)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Région</th>
                        <th>Besoin</th>
                        <th>Type de besoin</th>
                        <th>Prix (Ar)</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sinistres as $sinistre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sinistre['ville']); ?></td>
                            <td><?php echo htmlspecialchars($sinistre['region']); ?></td>
                            <td><?php echo htmlspecialchars($sinistre['besoin']); ?></td>
                            <td><?php echo htmlspecialchars($sinistre['type_besoin']); ?></td>
                            <td><?php echo number_format($sinistre['prix'], 2, ',', ' '); ?></td>
                            <td><?php echo htmlspecialchars($sinistre['quantite']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun sinistre enregistré.</p>
        <?php endif; ?>
    </div>
</body>
</html>