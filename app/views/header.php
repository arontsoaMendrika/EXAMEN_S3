<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Tableau de bord - Villes et Sinistres'; ?></title>

    <?php $asset_url = (isset($base_url) ? $base_url : '/ETU004364/TRINOME_EXAMEN2/') . 'public/'; ?>
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/nice-select.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/flaticon.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/gijgo.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/animate.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/slicknav.css">
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/style.css">
    <script src="<?php echo $asset_url; ?>template/js/vendor/jquery-1.12.4.min.js"></script>
</head>
<body>
  
    <header>
        <div class="header-area">
            <div class="main-header">
                <div class="header-mid d-none d-md-block">
                    <div class="container">
                        <div class="row d-flex align-items-center">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <div class="logo">
                                    <a href="<?php echo $base_url; ?>"><img src="<?php echo $asset_url; ?>template/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9">
                                <div class="header-banner f-right">
                                    <img src="<?php echo $asset_url; ?>template/img/gallery/header_card.png" alt="">
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
                                    <a href="<?php echo $base_url; ?>"><img src="<?php echo $asset_url; ?>template/img/logo/logo.png" alt=""></a>
                                </div>
                                <div class="main-menu d-none d-md-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="<?php echo $base_url; ?>">Accueil</a></li>
                                            <li><a href="<?php echo $base_url; ?>besoins">Voir les besoins</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <div class="header-right-btn f-right d-none d-lg-block">
                                    <a href="<?php echo $base_url; ?>dons" class="btn header-btn">Faire un don</a>
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