<?php include 'header.php'; ?>

<main>
    <div class="container mt-5">
        <h1>Application de Suivi des Collectes et Distributions de Dons</h1>
        <?php if(!empty($message)) { ?>
        <h3><?=$message?></h3>
        <?php } ?>
        <div class="d-flex gap-2">
            <a href="<?php echo $base_url; ?>dons" class="btn btn-primary">Gérer les Dons</a>
            <a href="<?php echo $base_url; ?>besoins" class="btn btn-success">Gérer les Besoins</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>