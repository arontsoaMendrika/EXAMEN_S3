<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Villes et Sinistres</title>
    <!-- CSS -->
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/template/css/magnific-popup.css">
    <link rel="stylesheet" href="/template/css/font-awesome.min.css">
    <link rel="stylesheet" href="/template/css/themify-icons.css">
    <link rel="stylesheet" href="/template/css/nice-select.css">
    <link rel="stylesheet" href="/template/css/flaticon.css">
    <link rel="stylesheet" href="/template/css/gijgo.css">
    <link rel="stylesheet" href="/template/css/animate.css">
    <link rel="stylesheet" href="/template/css/slicknav.css">
    <link rel="stylesheet" href="/template/css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-area">
            <div class="main-header">
                <div class="header-mid d-none d-md-block">
                    <div class="container">
                        <div class="row d-flex align-items-center">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <div class="logo">
                                    <a href="/"><img src="/template/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9">
                                <div class="header-banner f-right">
                                    <img src="/template/img/gallery/header_card.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-bottom header-sticky">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-10 col-lg-10 col-md-12 header-flex">
                                <div class="sticky-logo">
                                    <a href="/"><img src="/template/img/logo/logo.png" alt=""></a>
                                </div>
                                <div class="main-menu d-none d-md-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="/">Accueil</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <div class="header-right-btn f-right d-none d-lg-block">
                                    <a href="/dons" class="btn header-btn">Faire un don</a>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-md-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

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