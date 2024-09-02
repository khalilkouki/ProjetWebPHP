<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Connexion réussie à la base de données.";
} else {
    echo "Échec de la connexion.";
}
