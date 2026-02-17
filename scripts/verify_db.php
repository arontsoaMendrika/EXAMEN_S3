<?php
// Verify DB setup
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
$cfg = require 'app/config/config.php';
$db = $cfg['database'];
$pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'].';charset=utf8mb4', $db['user'], $db['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
echo 'Tables: ' . implode(', ', $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)) . "\n";
echo 'Besoins sample:' . "\n";
foreach ($pdo->query('SELECT id, nom, type_besoin, statut FROM besoins LIMIT 3')->fetchAll() as $r) {
    echo "  {$r['id']}: {$r['nom']} - {$r['type_besoin']} - {$r['statut']}\n";
}
echo 'Dons sample:' . "\n";
foreach ($pdo->query('SELECT id, nom, ordre FROM dons LIMIT 3')->fetchAll() as $r) {
    echo "  {$r['id']}: {$r['nom']} - {$r['ordre']}\n";
}
?>