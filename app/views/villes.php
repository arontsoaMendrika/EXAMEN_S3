<?php include 'header.php'; ?>

<main>
    <div class="container mt-5 p-4 rounded shadow bg-white">
        <h1 class="mb-4 text-primary">Liste des villes</h1>
        <form method="post" action="/villes/create" class="mb-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="nom" class="form-control border-primary" placeholder="Nom de la ville" required>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">Ajouter ville</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-4 align-middle">
                <thead class="table-primary">
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
    </div>
</main>

<?php include 'footer.php'; ?>
