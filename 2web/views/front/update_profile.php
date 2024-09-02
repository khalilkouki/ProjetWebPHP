<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id']) && $_SESSION['role'] === 'employe') {
    require_once '../../config/database.php';
    require_once '../../controllers/UtilisateurController.php';

    $database = new Database();
    $db = $database->getConnection();

    $utilisateurController = new UtilisateurController($db);

    $data = [
        'nom' => htmlspecialchars(strip_tags($_POST['nom'])),
        'email' => htmlspecialchars(strip_tags($_POST['email']))
    ];

    if ($utilisateurController->mettreAJourUtilisateur($_SESSION['id'], $data)) {
        $_SESSION['nom'] = $data['nom'];
        $_SESSION['email'] = $data['email'];
        header("Location: profile.php");
    } else {
        echo "Une erreur est survenue lors de la mise à jour.";
    }
} else {
    header("Location: /public/login.php");
    exit();
}
?>
