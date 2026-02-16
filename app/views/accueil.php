<?php include 'header.php'; ?>

<main>
    <div class="page-header animate-in">
        <h1><i class="fa-solid fa-triangle-exclamation" style="color:var(--accent);"></i> Villes affectées par les sinistres</h1>
        <p>Tableau récapitulatif des besoins par ville sinistrée</p>
    </div>

    <?php if (!empty($sinistres)): ?>
        <div class="card-custom animate-in">
            <div class="card-title">
                <i class="fa-solid fa-table-list"></i> Détail des sinistres
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th><i class="fa-solid fa-city"></i> Ville</th>
                            <th><i class="fa-solid fa-map"></i> Région</th>
                            <th><i class="fa-solid fa-clipboard-list"></i> Besoin</th>
                            <th><i class="fa-solid fa-tags"></i> Type</th>
                            <th><i class="fa-solid fa-coins"></i> Prix (Ar)</th>
                            <th><i class="fa-solid fa-cubes-stacked"></i> Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sinistres as $sinistre): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($sinistre['ville']); ?></strong></td>
                                <td><?php echo htmlspecialchars($sinistre['region']); ?></td>
                                <td><?php echo htmlspecialchars($sinistre['besoin']); ?></td>
                                <td><span class="badge-custom primary"><?php echo htmlspecialchars($sinistre['type_besoin']); ?></span></td>
                                <td class="montant-col"><?php echo number_format($sinistre['prix'], 2, ',', ' '); ?></td>
                                <td><?php echo htmlspecialchars($sinistre['quantite']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="card-custom">
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Aucun sinistre enregistré pour le moment.</p>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>