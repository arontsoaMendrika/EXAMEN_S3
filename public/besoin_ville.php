<?php
// Page d'ajout et CRUD des besoins par ville
require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

use app\models\Besoin;

// Connexion à la base de données
$db = $GLOBALS['db'];
$besoinModel = new Besoin($db);

// Gestion du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $categorie_id = $_POST['categorie_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $ville = $_POST['ville'] ?? '';
    // Ajout du besoin
    $besoinModel->create($titre, $description, $categorie_id, $user_id, $ville);
    echo '<div style="color:green">Besoin ajouté avec succès.</div>';
}

// Filtre par ville
$villeFiltre = $_GET['ville'] ?? '';
$besoins = [];
if ($villeFiltre) {
    $besoins = $db->fetchAll("SELECT b.*, c.nom as categorie_nom FROM besoins b LEFT JOIN categorie c ON b.categorie_id = c.id WHERE b.ville = ? ORDER BY b.id DESC", [$villeFiltre]);
} else {
    $besoins = $db->fetchAll("SELECT b.*, c.nom as categorie_nom FROM besoins b LEFT JOIN categorie c ON b.categorie_id = c.id ORDER BY b.id DESC");
}

// Affichage du formulaire et du CRUD
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRUD Besoin par Ville</title>
    <!-- Bootstrap et styles personnalisés -->
    <link rel="stylesheet" href="template/css/bootstrap.min.css">
    <link rel="stylesheet" href="template/css/style.css">
    <link rel="stylesheet" href="template/css/theme-default.css">
    <link rel="stylesheet" href="template/css/font-awesome.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3>Ajouter un besoin</h3>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                            </div>
                            <div class="mb-3">
                                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="categorie_id" class="form-control" placeholder="ID Catégorie" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="user_id" class="form-control" placeholder="ID Utilisateur" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="ville" class="form-control" placeholder="Ville" required>
                            </div>
                            <button type="submit" name="ajouter" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h4>Filtrer par ville</h4>
                    </div>
                    <div class="card-body">
                        <form method="get" class="d-flex">
                            <input type="text" name="ville" class="form-control me-2" placeholder="Ville" value="<?= htmlspecialchars($villeFiltre) ?>">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Filtrer</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h4>Liste des besoins<?= $villeFiltre ? ' pour la ville ' . htmlspecialchars($villeFiltre) : '' ?></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Catégorie</th>
                                    <th>Ville</th>
                                    <th>Utilisateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($besoins as $b) : ?>
                                <tr>
                                    <td><?= $b['id'] ?></td>
                                    <td><?= htmlspecialchars($b['titre']) ?></td>
                                    <td><?= htmlspecialchars($b['description']) ?></td>
                                    <td><?= htmlspecialchars($b['categorie_nom']) ?></td>
                                    <td><?= htmlspecialchars($b['ville']) ?></td>
                                    <td><?= htmlspecialchars($b['user_id']) ?></td>
                                    <td>
                                        <a href="?edit=<?= $b['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Modifier</a>
                                        <a href="?delete=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce besoin ?')"><i class="fa fa-trash"></i> Supprimer</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
