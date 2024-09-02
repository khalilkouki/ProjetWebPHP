<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - FrontOffice</title>
    <link rel="stylesheet" href="/css/front.css">
</head>
<body>
    <header>
        <h1>Bienvenue au FrontOffice</h1>
        <nav>
            <a href="manager_dashboard.php">Dashboard</a>
            <a href="profile.php">Mon Profil</a>
            <a href="/public/logout.php">Déconnexion</a>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer>
        <p>&copy; 2024 Votre Société. Tous droits réservés.</p>
    </footer>
</body>
</html>
