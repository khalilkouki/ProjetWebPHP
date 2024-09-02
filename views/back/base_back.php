<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - BackOffice</title>
    <link rel="stylesheet" href="/css/back.css"> <!-- Corrigez le chemin vers votre CSS -->
</head>
<body>
    <header>
        <h1>Gestion BackOffice</h1>
        <nav>
            <a href="/views/back/users.php">Gestion des Utilisateurs</a>
            <a href="/views/back/departements.php">Gestion des Départements</a>
            <a href="/views/back/categories.php">Gestion des Catégories</a>
            <a href="/public/logout.php">Déconnexion</a> <!-- Assurez-vous que logout.php est accessible ici -->
        </nav>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
        <p>&copy; 2024 Votre Société. Tous droits réservés.</p>
    </footer>
</body>
</html>
