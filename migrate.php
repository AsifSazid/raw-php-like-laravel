<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

$db = new Database();
$pdo = $db->connect();

// Ensure the migrations table exists
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
");

$command = $argv[1] ?? 'migrate';
$argument = $argv[2] ?? null;

switch ($command) {
    case 'make':
        if (!$argument) {
            echo "Usage: php migrate.php make create_tableName_table\n";
            exit;
        }
        makeMigration($argument);
        break;

    case 'migrate':
        migrateUp($pdo);
        break;

    case 'rollback':
        migrateDown($pdo);
        break;

    case 'status':
        showStatus($pdo);
        break;


    default:
        echo "Usage: php migrate.php [make|migrate|rollback|status]\n";
        break;
}

function makeMigration($name)
{
    $timestamp = date('Y_m_d_His');
    $fileName = "{$timestamp}_{$name}.php";
    $filePath = __DIR__ . "/app/migrations/{$fileName}";
    $className = migrationClassName($name);
    $tableName = tableName($fileName);

    $template = <<<PHP
<?php
class {$className}
{
    private \$pdo;
    public function __construct(\$pdo) { \$this->pdo = \$pdo; }

    public function up()
    {
        \$sql = "
        CREATE TABLE IF NOT EXISTS {$tableName} (
            id INT AUTO_INCREMENT PRIMARY KEY,

            -- add your columns here

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            
            -- Foreign key relationship
        ) ENGINE=InnoDB;
        ";
        \$this->pdo->exec(\$sql);
    }

    public function down()
    {
        \$sql = "DROP TABLE IF EXISTS {$tableName};";
        \$this->pdo->exec(\$sql);
    }
}
PHP;

    file_put_contents($filePath, $template);
    echo "✅ Migration created: app/migrations/{$fileName}\n";
}

function migrateUp($pdo)
{
    $migrationsDir = __DIR__ . '/app/migrations';
    $ran = $pdo->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
    $files = glob("$migrationsDir/*.php");
    $batch = ((int)$pdo->query("SELECT MAX(batch) FROM migrations")->fetchColumn()) + 1;

    foreach ($files as $file) {
        $migrationName = basename($file, '.php');
        if (in_array($migrationName, $ran)) continue;

        require_once $file;
        $className = migrationClassName($migrationName);

        if (class_exists($className)) {
            echo "🔼 Running: $migrationName\n";
            $migration = new $className($pdo);
            $migration->up();

            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$migrationName, $batch]);
            echo "✅ Done: $migrationName\n";
        }
    }
}

function migrateDown($pdo)
{
    $lastBatch = $pdo->query("SELECT MAX(batch) FROM migrations")->fetchColumn();
    if (!$lastBatch) {
        echo "⚠️ No migrations to roll back.\n";
        return;
    }

    $migrations = $pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
    $migrations->execute([$lastBatch]);

    foreach ($migrations->fetchAll(PDO::FETCH_COLUMN) as $migrationName) {
        $file = __DIR__ . "/app/migrations/$migrationName.php";
        if (!file_exists($file)) continue;

        require_once $file;
        $className = migrationClassName($migrationName);

        if (class_exists($className)) {
            echo "🔽 Rolling back: $migrationName\n";
            $migration = new $className($pdo);
            if (method_exists($migration, 'down')) {
                $migration->down();
                echo "✅ Rolled back: $migrationName\n";
            }
        }

        $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migrationName]);
    }
}

function migrationClassName($migrationName)
{
    $parts = explode('_', $migrationName);
    $filtered = array_filter($parts, fn($p) => !is_numeric($p));
    return implode('', array_map('ucfirst', $filtered));
}

function tableName($fileName)
{
    $parts = explode('_', $fileName);
    $table_name_parts = array_slice($parts, 5, -1);
    $table_name = implode('_', $table_name_parts);
    return $table_name;
}

function showStatus($pdo)
{
    $migrationsDir = __DIR__ . '/app/migrations';
    $allFiles = glob("$migrationsDir/*.php");
    $allMigrations = array_map(fn($f) => basename($f, '.php'), $allFiles);

    $ranData = $pdo->query("SELECT migration, batch FROM migrations")->fetchAll(PDO::FETCH_KEY_PAIR);

    echo str_pad("Migration", 50) . str_pad("Batch", 15) . "Status\n";
    echo str_repeat("-", 75) . "\n";

    foreach ($allMigrations as $migration) {
        $batch = $ranData[$migration] ?? "N/A";
        $status = isset($ranData[$migration]) ? "✅ Ran" : "⏳ Pending";

        echo str_pad($migration, 50) . str_pad($batch, 15) . $status . "\n";
    }
}
