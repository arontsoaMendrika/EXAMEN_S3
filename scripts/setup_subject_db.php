<?php
// Setup database following the exam subject: import base dump, normalize type_besoin, apply schema updates
$ds = DIRECTORY_SEPARATOR;
$root = __DIR__ . $ds . '..';

// Minimal Flight stub
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

$cfg = require $root . $ds . 'app' . $ds . 'config' . $ds . 'config.php';
$db = $cfg['database'] ?? null;
if (!$db) { echo "Database config not found.\n"; exit(1); }

$host = $db['host'] ?? '127.0.0.1';
$name = $db['dbname'] ?? 'cyclone';
$user = $db['user'] ?? 'root';
$pass = $db['password'] ?? '';

try {
    $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Setting up database '$name' following exam subject...\n";

// Drop database if exists
$pdo->exec("DROP DATABASE IF EXISTS `{$name}`");
echo "Dropped existing database.\n";

// Create database
$pdo->exec("CREATE DATABASE `{$name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "Created database.\n";

// Reconnect to the database
$pdo = new PDO("mysql:host={$host};dbname={$name};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
echo "Reconnected to database.\n";

// Import cyclone.sql (base dump) - remove CREATE DATABASE and USE
$dumpPath = $root . $ds . 'cyclone.sql';
if (!file_exists($dumpPath)) {
    echo "cyclone.sql not found in project root.\n";
    exit(1);
}
$sql = file_get_contents($dumpPath);
if ($sql === false) {
    echo "Failed to read cyclone.sql\n";
    exit(1);
}
// Remove the CREATE DATABASE and USE lines
$sql = preg_replace('/CREATE DATABASE.*;\s*/i', '', $sql);
$sql = preg_replace('/USE.*;\s*/i', '', $sql);
$sql = preg_replace('/SET SQL_MODE.*;\s*/i', '', $sql);
$sql = preg_replace('/START TRANSACTION;\s*/i', '', $sql);
$sql = preg_replace('/SET time_zone.*;\s*/i', '', $sql);
$sql = preg_replace('/\/\*!\d+.*?\*\//s', '', $sql); // remove mysql comments
$sql = preg_replace('/COMMIT;\s*$/i', '', $sql);
$sql = preg_replace('/^--.*$/m', '', $sql); // remove comment lines
$sql = preg_replace('/^\s*$/m', '', $sql); // remove empty lines
echo "SQL length after clean: " . strlen($sql) . "\n";
try {
    // Split by semicolon
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    echo "Number of statements: " . count($statements) . "\n";
    $i = 0;
    foreach ($statements as $stmt) {
        if (!empty($stmt) && !preg_match('/^--/', $stmt) && !preg_match('/^\/\*!/', $stmt)) { // skip comments
            if ($i < 3) echo "Stmt $i: " . substr($stmt, 0, 50) . "...\n";
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                echo "Failed stmt $i: " . substr($stmt, 0, 100) . "... => " . $e->getMessage() . "\n";
                // continue
            }
            $i++;
        }
    }
    echo "Imported base dump from cyclone.sql\n";
} catch (PDOException $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if tables exist
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables created: " . implode(', ', $tables) . "\n";
if (!in_array('besoins', $tables)) {
    echo "besoins table not found, import failed.\n";
    exit(1);
}

// Now normalize type_besoin in besoins table
echo "Normalizing type_besoin values...\n";
$updates = [
    "UPDATE besoins SET type_besoin = 'nature' WHERE type_besoin IN ('Alimentation', 'Hydratation', 'en nature', 'en_nature')",
    "UPDATE besoins SET type_besoin = 'materiaux' WHERE type_besoin IN ('Abri', 'Vêtements', 'en materiel', 'en_materiel', 'materiaux')",
    "UPDATE besoins SET type_besoin = 'argent' WHERE type_besoin IN ('Santé', 'argent', 'en argent')"
];
foreach ($updates as $update) {
    $pdo->exec($update);
}
echo "Normalized type_besoin.\n";

// Create achats table if not exists
$pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS `achats` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `besoin_id` INT DEFAULT NULL,
  `don_id` INT DEFAULT NULL,
  `montant` DECIMAL(10,2) NOT NULL,
  `date_achat` DATE NOT NULL,
  FOREIGN KEY (besoin_id) REFERENCES besoins(id),
  FOREIGN KEY (don_id) REFERENCES dons(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
);
echo "Created achats table.\n";

// Apply schema updates from 10_07.sql (ENUM)
echo "Applying ENUM constraint on type_besoin...\n";
$pdo->exec("ALTER TABLE besoins MODIFY COLUMN type_besoin ENUM('nature','materiaux','argent') NOT NULL");
echo "ENUM applied.\n";

// Apply schema updates from 10_38.sql (statut and ordre)
echo "Adding statut column to besoins...\n";
$pdo->exec("ALTER TABLE besoins ADD COLUMN statut VARCHAR(32) NOT NULL DEFAULT 'en_attente'");
echo "Added statut.\n";

echo "Adding ordre column to dons...\n";
$pdo->exec("ALTER TABLE dons ADD COLUMN ordre VARCHAR(32) NOT NULL DEFAULT 'prioritaire'");
echo "Added ordre.\n";

echo "Updating statut based on achats...\n";
$pdo->exec("UPDATE besoins b LEFT JOIN (SELECT besoin_id, COALESCE(SUM(montant),0) AS mont_total FROM achats GROUP BY besoin_id) a ON a.besoin_id = b.id SET b.statut = CASE WHEN COALESCE(a.mont_total,0) >= (b.prix * b.quantite) THEN 'comble' ELSE 'en_attente' END");
echo "Updated statut.\n";

echo "Adding index on besoins.statut...\n";
try { $pdo->exec("CREATE INDEX idx_besoins_statut ON besoins(statut)"); } catch (Exception $e) { /* ignore if exists */ }
echo "Index added.\n";

echo "Database setup complete per exam subject.\n";
echo "Tables: region, ville, besoins (with statut, enum type_besoin), sinistres, dons (with ordre), achats\n";
echo "Sample data imported and normalized.\n";
?>