<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle d'employé
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employe') {
    header("Location: /public/login.php");
    exit();
}

require_once '../../config/database.php';
require_once '../../controllers/UtilisateurController.php';

$database = new Database();
$db = $database->getConnection();

$utilisateurController = new UtilisateurController($db);

// Récupérer les informations de l'utilisateur
$utilisateur = $utilisateurController->lireUnUtilisateur($_SESSION['id']);

$title = "Mon Profil";
ob_start();
?>

<h2>Mon Profil</h2>
<p>Voici les informations de votre profil :</p>

<table border="1">
    <tr>
        <th>Nom</th>
        <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($utilisateur['email']) ?></td>
    </tr>
    <tr>
        <th>Rôle</th>
        <td><?= htmlspecialchars($utilisateur['role']) ?></td>
    </tr>
</table>

<h2>Mettre à jour les informations personnelles</h2>
<form action="update_profile.php" method="POST">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required><br>
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required><br>
    <button type="submit">Mettre à jour</button>
</form>

<h2>Évaluations de performance</h2>
<p>Visualisez vos évaluations de performance ci-dessous :</p>

<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Évaluation</th>
            <th>Commentaires</th>
        </tr>
    </thead>
    <tbody>
        <!-- Exemple statique -->
        <tr>
            <td>2024-01-15</td>
            <td>Excellent</td>
            <td>Très bon travail cette année.</td>
        </tr>
        <tr>
            <td>2023-06-10</td>
            <td>Bon</td>
            <td>Bons résultats, mais peut s'améliorer sur certains aspects.</td>
        </tr>
        <!-- Vous pouvez dynamiser ces données avec une base de données -->
    </tbody>
</table>

<?php
$content = ob_get_clean();
include 'base_front.php';
?>
