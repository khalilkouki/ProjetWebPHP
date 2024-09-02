<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle de manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: /public/login.php");
    exit();
}

require_once '../../config/database.php';
require_once '../../controllers/UtilisateurController.php';

$database = new Database();
$db = $database->getConnection();

$utilisateurController = new UtilisateurController($db);

$title = "Tableau de Bord Manager";
ob_start();
?>

<h2>Rechercher des employés</h2>
<form action="" method="GET">
    <input type="text" name="nom" placeholder="Nom">
    <input type="email" name="email" placeholder="Email">
    <button type="submit">Rechercher</button>
</form>

<h2>Liste des employés</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Date de Création</th>
            <!-- Ajoutez des colonnes spécifiques pour les évaluations si nécessaire -->
        </tr>
    </thead>
    <tbody>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['nom']) || isset($_GET['email']))) {
            $utilisateurs = json_decode($utilisateurController->rechercherUtilisateurs($_GET), true);
        } else {
            $utilisateurs = json_decode($utilisateurController->lireUtilisateurs(), true);
        }

        foreach ($utilisateurs as $utilisateur):
            if ($utilisateur['role'] === 'employe'): ?>
            <tr>
                <td><?= $utilisateur['id'] ?></td>
                <td><?= $utilisateur['nom'] ?></td>
                <td><?= $utilisateur['email'] ?></td>
                <td><?= $utilisateur['date_creation'] ?></td>
            </tr>
            <?php endif;
        endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include 'base_front.php';
