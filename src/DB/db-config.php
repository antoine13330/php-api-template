<?php

use AntoineD\Api\DB\Connection;

include (__DIR__.'/../autoload.php');

define('DB_HOST', 'localhost');
define('DB_NAME', 'webservice');
define('DB_USER', 'postgres');
define('DB_PASS', 'admin');
define('DB_PORT', '5432');

function connect($dbname = null)
{
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ($dbname ? ";dbname=" . $dbname : "");
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function createDatabaseIfNotExists()
{
    $pdo = connect();
    
    // Vérifier si la base de données existe
    $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = :dbname");
    $stmt->execute(['dbname' => DB_NAME]);

    if ($stmt->fetch()) {
        // echo "La base de données " . DB_NAME . " existe déjà.\n";
    } else {
        // Créer la base de données
        $sql = "CREATE DATABASE " . DB_NAME;
        $pdo->exec($sql);
        // echo "Base de données " . DB_NAME . " créée avec succès.\n";
    }
    
    // Fermer la connexion
    $pdo = null;
}

function createTablesFromSchema($pdo, $schemaFile)
{
    if (!file_exists($schemaFile)) {
        die("Le fichier de schéma n'existe pas : " . $schemaFile);
    }

    $sql = file_get_contents($schemaFile);

    try {
        $pdo->exec($sql);
        // echo "Tables créées avec succès à partir de " . $schemaFile . "\n";
    } catch (PDOException $e) {
        die("Erreur lors de la création des tables : " . $e->getMessage());
    }
}

// Créer la base de données si elle n'existe pas
createDatabaseIfNotExists();

// Se connecter à la base de données nouvellement créée
$pdo = connect(DB_NAME);

// Créer les tables à partir du fichier de schéma
createTablesFromSchema($pdo, __DIR__ . '/db-schema.sql');

?>
