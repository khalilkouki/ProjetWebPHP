<?php
session_start();
require_once '../config/database.php';
require_once '../models/Utilisateur.php';

$database = new Database();
$db = $database->getConnection();

$utilisateur = new Utilisateur($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $mot_de_passe = htmlspecialchars(strip_tags($_POST['mot_de_passe']));

    if ($utilisateur->connexion($email, $mot_de_passe)) {
        // Redirection en fonction du rôle
        if ($_SESSION['role'] == 'administrateur') {
            header("Location: /views/back/users.php");
        } elseif ($_SESSION['role'] == 'manager') {
            header("Location: /views/front/manager_dashboard.php"); // Redirection vers une page spécifique aux managers
        } elseif ($_SESSION['role'] == 'employe') {
            header("Location: /views/front/profile.php");
        }
        exit();
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/front.css"> <!-- Utilisez votre fichier CSS -->
</head>
<body>
    <h1>Connexion</h1>
    <?php if (!empty($message)) : ?>
        <p><?= $message ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <button type="submit">Connexion</button>
    </form>
</body>
</html>
