<?php
/**
 * Script pour effacer les données existantes et insérer les nouvelles données cyclone 2026
 */

// Minimal Flight stub for config.php
if (!class_exists('Flight')) {
    class Flight {
        private static $app;
        public static function app() {
            if (!self::$app) self::$app = new class {
                private $store = [];
                public function path($p) { }
                public function set($k, $v) { $this->store[$k] = $v; }
                public function get($k) { return $this->store[$k] ?? null; }
            };
            return self::$app;
        }
    }
}

$cfg = require __DIR__ . '/../app/config/config.php';
$db = $cfg['database'];
$host = $db['host'] ?? '127.0.0.1';
$name = $db['dbname'] ?? 'cyclone';
$user = $db['user'] ?? 'root';
$pass = $db['password'] ?? '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$name};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connecté à la base '$name'\n";
} catch (Exception $e) {
    echo "Connexion échouée: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================================
// 1. Vider toutes les tables
// ============================================================
echo "\n=== Vidage des tables ===\n";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$tables = ['achats', 'sinistres', 'besoins', 'dons', 'ville', 'region'];
foreach ($tables as $t) {
    try {
        $pdo->exec("TRUNCATE TABLE `$t`");
        echo "  TRUNCATE $t OK\n";
    } catch (Exception $e) {
        echo "  TRUNCATE $t ERREUR: " . $e->getMessage() . "\n";
    }
}
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
echo "Tables vidées.\n";

// ============================================================
// 2. Insertion des régions
// ============================================================
echo "\n=== Insertion des régions ===\n";
$pdo->exec("INSERT INTO `region` (`id`, `nom`) VALUES
(1, 'Analamanga'),
(2, 'Atsinanana'),
(3, 'Diana'),
(4, 'Menabe'),
(5, 'Atsimo Atsinanana')");
echo "  5 régions insérées\n";

// ============================================================
// 3. Insertion des villes
// ============================================================
echo "\n=== Insertion des villes ===\n";
$pdo->exec("INSERT INTO `ville` (`id`, `nom`, `region_id`) VALUES
(1,  'Antananarivo', 1),
(2,  'Toamasina',    2),
(3,  'Antsiranana',  3),
(4,  'Mahajanga',    4),
(5,  'Toliara',      5),
(6,  'Fianarantsoa', 1),
(7,  'Antsirabe',    1),
(8,  'Mananjary',    2),
(9,  'Farafangana',  5),
(10, 'Nosy Be',      3),
(11, 'Morondava',    4)");
echo "  11 villes insérées\n";

// ============================================================
// 4. Vérifier/ajuster la colonne statut et ordre
// ============================================================
echo "\n=== Vérification des colonnes statut/ordre ===\n";
try {
    $cols = $pdo->query("SHOW COLUMNS FROM besoins LIKE 'statut'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE besoins ADD COLUMN statut VARCHAR(32) NOT NULL DEFAULT 'en_attente'");
        echo "  Colonne statut ajoutée à besoins\n";
    } else {
        echo "  Colonne statut existe déjà\n";
    }
} catch (Exception $e) {
    echo "  Erreur statut: " . $e->getMessage() . "\n";
}

try {
    $cols = $pdo->query("SHOW COLUMNS FROM dons LIKE 'ordre'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE dons ADD COLUMN ordre VARCHAR(32) NOT NULL DEFAULT 'prioritaire'");
        echo "  Colonne ordre ajoutée à dons\n";
    } else {
        echo "  Colonne ordre existe déjà\n";
    }
} catch (Exception $e) {
    echo "  Erreur ordre: " . $e->getMessage() . "\n";
}

// Vérifier/adapter type_besoin
try {
    $colInfo = $pdo->query("SHOW COLUMNS FROM besoins LIKE 'type_besoin'")->fetch();
    if ($colInfo && strpos($colInfo['Type'], 'enum') === false) {
        $pdo->exec("ALTER TABLE besoins MODIFY COLUMN type_besoin ENUM('nature','materiaux','argent') NOT NULL");
        echo "  type_besoin converti en ENUM\n";
    } else {
        echo "  type_besoin déjà ENUM\n";
    }
} catch (Exception $e) {
    echo "  Erreur type_besoin: " . $e->getMessage() . "\n";
}

// ============================================================
// 5. Insertion des besoins
// ============================================================
echo "\n=== Insertion des besoins ===\n";
$pdo->exec("INSERT INTO `besoins` (`id`, `nom`, `type_besoin`, `prix`, `quantite`, `statut`) VALUES
(11, 'Riz (kg)',     'nature',    3000.00,  800, 'en_attente'),
(12, 'Eau (L)',      'nature',    1000.00, 1500, 'en_attente'),
(13, 'Tôle',         'materiaux', 25000.00, 120, 'en_attente'),
(14, 'Bâche',        'materiaux', 15000.00, 200, 'en_attente'),
(15, 'Argent',       'argent',         1.00, 12000000, 'en_attente'),
(16, 'groupe',       'materiaux', 6750000.00,   3, 'en_attente'),

(17, 'Riz (kg)',     'nature',    3000.00,  500, 'en_attente'),
(18, 'Huile (L)',    'nature',    6000.00,  120, 'en_attente'),
(19, 'Tôle',         'materiaux', 25000.00,  80, 'en_attente'),
(20, 'Clous (kg)',   'materiaux',  8000.00,  60, 'en_attente'),
(21, 'Argent',       'argent',         1.00, 6000000, 'en_attente'),

(22, 'Riz (kg)',     'nature',    3000.00,  600, 'en_attente'),
(23, 'Eau (L)',      'nature',    1000.00, 1000, 'en_attente'),
(24, 'Bâche',        'materiaux', 15000.00, 150, 'en_attente'),
(25, 'Bois',         'materiaux', 10000.00, 100, 'en_attente'),
(26, 'Argent',       'argent',         1.00, 8000000, 'en_attente'),

(27, 'Riz (kg)',     'nature',    3000.00,  300, 'en_attente'),
(28, 'Haricots',     'nature',    4000.00,  200, 'en_attente'),
(29, 'Tôle',         'materiaux', 25000.00,  40, 'en_attente'),
(30, 'Clous (kg)',   'materiaux',  8000.00,  30, 'en_attente'),
(31, 'Argent',       'argent',         1.00, 4000000, 'en_attente'),

(32, 'Riz (kg)',     'nature',    3000.00,  700, 'en_attente'),
(33, 'Eau (L)',      'nature',    1000.00, 1200, 'en_attente'),
(34, 'Bâche',        'materiaux', 15000.00, 180, 'en_attente'),
(35, 'Bois',         'materiaux', 10000.00, 150, 'en_attente'),
(36, 'Argent',       'argent',         1.00, 10000000, 'en_attente')");
echo "  26 besoins insérés\n";

// ============================================================
// 6. Insertion des sinistres
// ============================================================
echo "\n=== Insertion des sinistres ===\n";
$pdo->exec("INSERT INTO `sinistres` (`ville_id`, `besoin_id`) VALUES
(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),
(8,17),(8,18),(8,19),(8,20),(8,21),
(9,22),(9,23),(9,24),(9,25),(9,26),
(10,27),(10,28),(10,29),(10,30),(10,31),
(11,32),(11,33),(11,34),(11,35),(11,36)");
echo "  26 sinistres insérés\n";

// ============================================================
// 7. Insertion des dons
// ============================================================
echo "\n=== Insertion des dons ===\n";
$pdo->exec("INSERT INTO `dons` (`id`, `nom`, `montant`, `date_don`, `ordre`) VALUES
(5,  'Don cash 5M - 16/02',               5000000.00, '2026-02-16', 'prioritaire'),
(6,  'Don cash 3M - 16/02',               3000000.00, '2026-02-16', 'prioritaire'),
(7,  '400 kg Riz - 16/02',                1200000.00, '2026-02-16', 'prioritaire'),
(8,  '600 L Eau - 16/02',                  600000.00, '2026-02-16', 'prioritaire'),
(9,  'Don cash 4M - 17/02',               4000000.00, '2026-02-17', 'prioritaire'),
(10, 'Don cash 1.5M - 17/02',             1500000.00, '2026-02-17', 'prioritaire'),
(11, 'Don cash 6M - 17/02',               6000000.00, '2026-02-17', 'prioritaire'),
(12, '50 tôles - 17/02',                  1250000.00, '2026-02-17', 'prioritaire'),
(13, '70 bâches - 17/02',                 1050000.00, '2026-02-17', 'prioritaire'),
(14, '100 kg Haricots - 17/02',            400000.00, '2026-02-17', 'prioritaire'),
(15, '88 kg Haricots - 17/02',             352000.00, '2026-02-17', 'prioritaire'),
(16, '2000 kg Riz - 18/02',               6000000.00, '2026-02-18', 'prioritaire'),
(17, '300 tôles - 18/02',                 7500000.00, '2026-02-18', 'prioritaire'),
(18, '5000 L Eau - 18/02',                5000000.00, '2026-02-18', 'prioritaire'),
(19, 'Don cash 20M - 19/02',             20000000.00, '2026-02-19', 'prioritaire'),
(20, '500 bâches - 19/02',                7500000.00, '2026-02-19', 'prioritaire')");
echo "  16 dons insérés\n";

// ============================================================
// 8. Vérification
// ============================================================
echo "\n=== VÉRIFICATION ===\n";
$checks = [
    'region' => 'SELECT COUNT(*) FROM region',
    'ville' => 'SELECT COUNT(*) FROM ville',
    'besoins' => 'SELECT COUNT(*) FROM besoins',
    'sinistres' => 'SELECT COUNT(*) FROM sinistres',
    'dons' => 'SELECT COUNT(*) FROM dons',
    'achats' => 'SELECT COUNT(*) FROM achats',
];
foreach ($checks as $table => $query) {
    $count = $pdo->query($query)->fetchColumn();
    echo "  $table: $count enregistrements\n";
}

echo "\n--- Besoins par ville ---\n";
$rows = $pdo->query("
    SELECT v.nom AS ville, b.id, b.nom AS besoin, b.type_besoin, b.prix, b.quantite, (b.prix * b.quantite) AS montant_total
    FROM besoins b
    JOIN sinistres s ON s.besoin_id = b.id
    JOIN ville v ON v.id = s.ville_id
    ORDER BY v.nom, b.id
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo sprintf("  %-12s | #%-3d %-15s | %-10s | %12s Ar x %-10s = %15s Ar\n",
        $r['ville'], $r['id'], $r['besoin'], $r['type_besoin'],
        number_format($r['prix'], 2, ',', ' '),
        number_format($r['quantite'], 0, ',', ' '),
        number_format($r['montant_total'], 2, ',', ' ')
    );
}

echo "\n--- Dons ---\n";
$rows = $pdo->query("SELECT id, nom, montant, date_don, ordre FROM dons ORDER BY date_don, id")->fetchAll(PDO::FETCH_ASSOC);
$totalDons = 0;
foreach ($rows as $r) {
    echo sprintf("  #%-3d %-35s | %15s Ar | %s | %s\n",
        $r['id'], $r['nom'],
        number_format($r['montant'], 2, ',', ' '),
        $r['date_don'], $r['ordre']
    );
    $totalDons += $r['montant'];
}
echo "  TOTAL DONS: " . number_format($totalDons, 2, ',', ' ') . " Ar\n";

echo "\nTerminé avec succès !\n";
