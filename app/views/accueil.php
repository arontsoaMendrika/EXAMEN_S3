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
                <div class="header-top black-bg d-none d-md-block">
                    <div class="container">
                        <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left">
                                    <ul>
                                        <li><img src="/template/img/icon/header_icon1.png" alt="">34ºc, Sunny </li>
                                        <li><img src="/template/img/icon/header_icon1.png" alt="">Tuesday, 18:00</li>
                                    </ul>
                                </div>
                                <div class="header-info-right">
                                    <ul class="header-social">
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li> <a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <a href="dons.php" class="btn header-btn">Faire un don</a>
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

    <!-- Main Content -->
    <main>
        <div class="container mt-5">
            <h1 class="mb-4">Tableau de bord - Villes affectées par les sinistres</h1>

            <?php if (!empty($sinistres)): ?>
                <div class="table-responsive">
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
                </div>
            <?php else: ?>
                <p>Aucun sinistre enregistré.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-wrapper section-bg2" data-background="/template/img/gallery/footer_bg.png">
            <div class="footer-area footer-padding">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-4 col-lg-4 col-md-5 col-sm-8">
                            <div class="single-footer-caption mb-50">
                                <div class="single-footer-caption mb-30">
                                    <div class="footer-tittle">
                                        <div class="footer-pera">
                                            <p>Aider les communautés touchées par les cyclones.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Liens rapides</h4>
                                    <ul>
                                        <li><a href="/">Accueil</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom-area">
                <div class="container">
                    <div class="footer-border">
                        <div class="row d-flex align-items-center">
                            <div class="col-xl-12">
                                <div class="footer-copy-right text-center">
                                    Développé par : <strong>ETU004081</strong> &amp; <strong>ETU004342</strong> &amp; <strong>ETU004364</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <script src="/template/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="/template/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="/template/js/popper.min.js"></script>
    <script src="/template/js/bootstrap.min.js"></script>
    <script src="/template/js/jquery.slicknav.min.js"></script>
    <script src="/template/js/owl.carousel.min.js"></script>
    <script src="/template/js/slick.min.js"></script>
    <script src="/template/js/wow.min.js"></script>
    <script src="/template/js/animated.headline.js"></script>
    <script src="/template/js/jquery.magnific-popup.js"></script>
    <script src="/template/js/gijgo.min.js"></script>
    <script src="/template/js/jquery.nice-select.min.js"></script>
    <script src="/template/js/jquery.sticky.js"></script>
    <script src="/template/js/jquery.barfiller.js"></script>
    <script src="/template/js/jquery.counterup.min.js"></script>
    <script src="/template/js/waypoints.min.js"></script>
    <script src="/template/js/jquery.countdown.min.js"></script>
    <script src="/template/js/hover-direction-snake.min.js"></script>
    <script src="/template/js/contact.js"></script>
    <script src="/template/js/jquery.form.js"></script>
    <script src="/template/js/jquery.validate.min.js"></script>
    <script src="/template/js/mail-script.js"></script>
    <script src="/template/js/jquery.ajaxchimp.min.js"></script>
    <script src="/template/js/plugins.js"></script>
    <script src="/template/js/main.js"></script>
</body>
</html>