<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'BNGRC - Suivi des Dons'; ?></title>

    <?php $asset_url = (isset($base_url) ? $base_url : '/ETU004364/TRINOME_EXAMEN2/') . 'public/'; ?>
    
    <!-- Google Fonts - Nunito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 (local) -->
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/fontawesome-all.min.css">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/bootstrap.min.css">
    
    <!-- Custom Design -->
    <link rel="stylesheet" href="<?php echo $asset_url; ?>template/css/custom.css">
    
    <!-- jQuery -->
    <script src="<?php echo $asset_url; ?>template/js/vendor/jquery-1.12.4.min.js"></script>

    <?php
        // Déterminer la page active pour la sidebar
        $current_uri = $_SERVER['REQUEST_URI'] ?? '';
        $is_home = (rtrim($current_uri, '/') === rtrim($base_url, '/')) || $current_uri === $base_url;
        $is_besoins = strpos($current_uri, 'besoins') !== false;
        $is_dons = strpos($current_uri, 'dons') !== false;
        $is_villes = strpos($current_uri, 'villes') !== false;
    ?>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-hands-holding-circle"></i>
        </div>
        <div>
            <div class="brand-text">BNGRC</div>
            <div class="brand-sub">Suivi des Dons</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <div class="sidebar-section-title">Navigation</div>
            <a href="<?php echo $base_url; ?>" class="sidebar-link <?php echo $is_home ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i>
                <span>Accueil</span>
            </a>
            <a href="<?php echo $base_url; ?>besoins" class="sidebar-link <?php echo $is_besoins ? 'active' : ''; ?>">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Besoins</span>
            </a>
            <a href="<?php echo $base_url; ?>dons" class="sidebar-link <?php echo $is_dons ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-heart"></i>
                <span>Dons</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Administration</div>
            <a href="<?php echo $base_url; ?>villes" class="sidebar-link <?php echo $is_villes ? 'active' : ''; ?>">
                <i class="fa-solid fa-city"></i>
                <span>Villes</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-footer-text">
            <i class="fa-solid fa-code"></i> ETU004081 · ETU004342 · ETU004364
        </div>
    </div>
</aside>

<!-- App Content Wrapper -->
<div class="app-content">

    <!-- Top Header -->
    <header class="top-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="btn-sidebar-toggle" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="page-title"><?php echo isset($title) ? $title : 'Tableau de bord'; ?></h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo $base_url; ?>dons" class="btn-primary-custom">
                <i class="fa-solid fa-plus"></i> Nouveau Don
            </a>
        </div>
    </header>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
    </script>