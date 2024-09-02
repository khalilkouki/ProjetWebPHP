<?php
require_once '../config/database.php';
require_once '../controllers/UtilisateurController.php';
require_once '../controllers/DepartementController.php';
require_once '../controllers/CategorieController.php';

$database = new Database();
$db = $database->getConnection();

$utilisateurController = new UtilisateurController($db);
$departementController = new DepartementController($db);
$categorieController = new CategorieController($db);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion RH</title>
    <link rel="stylesheet" href="../css/back.css"> <!-- Ajoutez ici le lien vers votre fichier CSS -->
</head>
<body>
    <header>
        <h1>Tableau de Bord</h1>
        <nav>
            <a href="/views/back/users.php">Gestion des Utilisateurs</a>
            <a href="/views/back/departements.php">Gestion des Départements</a>
            <a href="/views/back/categories.php">Gestion des Catégories</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <h2>Bienvenue dans le système de gestion des ressources humaines</h2>
        <p>Utilisez les liens de navigation ci-dessus pour gérer les utilisateurs, les départements et les catégories.</p>

        <!-- Vous pouvez ajouter des statistiques ou des résumés ici -->
        <section>
            <h3>Statistiques générales</h3>
            <ul>
                <li>Nombre total d'utilisateurs : <?= $utilisateurController->compterUtilisateurs(); ?></li>
                
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Votre Société. Tous droits réservés.</p>
    </footer>
</body>
</html>
