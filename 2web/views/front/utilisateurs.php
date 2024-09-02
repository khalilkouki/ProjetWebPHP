<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UtilisateurController.php';

$database = new Database();
$db = $database->getConnection();

$utilisateurController = new UtilisateurController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $utilisateurController->creerUtilisateur($_POST);
    } elseif (isset($_POST['update'])) {
        $utilisateurController->mettreAJourUtilisateur($_POST['id'], $_POST);
    } elseif (isset($_POST['delete'])) {
        $utilisateurController->supprimerUtilisateur($_POST['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['nom']) || isset($_GET['email']) || isset($_GET['role']))) {
    $utilisateurs = json_decode($utilisateurController->rechercherUtilisateurs($_GET), true);
} else {
    $tri_colonne = isset($_GET['tri_colonne']) ? $_GET['tri_colonne'] : 'id';
    $tri_ordre = isset($_GET['tri_ordre']) && $_GET['tri_ordre'] === 'ASC' ? 'ASC' : 'DESC';
    $utilisateurs = json_decode($utilisateurController->lireUtilisateurs($tri_colonne, $tri_ordre), true);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
</head>
<body>
    <h1>Gestion des Utilisateurs</h1>

    <h2>Créer un nouvel utilisateur</h2>
    <form action="" method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <select name="role" required>
            <option value="administrateur">Administrateur</option>
            <option value="manager">Manager</option>
            <option value="employe">Employé</option>
        </select>
        <button type="submit" name="create">Créer</button>
    </form>

    <h2>Recherche multicritères</h2>
    <form action="" method="GET">
        <input type="text" name="nom" placeholder="Nom">
        <input type="email" name="email" placeholder="Email">
        <select name="role">
            <option value="">Tous les rôles</option>
            <option value="administrateur">Administrateur</option>
            <option value="manager">Manager</option>
            <option value="employe">Employé</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>

    <h2>Liste des utilisateurs</h2>
    <table border="1">
        <thead>
            <tr>
                <th><a href="?tri_colonne=id&tri_ordre=<?= isset($_GET['tri_ordre']) && $_GET['tri_ordre'] == 'ASC' ? 'DESC' : 'ASC' ?>">ID</a></th>
                <th><a href="?tri_colonne=nom&tri_ordre=<?= isset($_GET['tri_ordre']) && $_GET['tri_ordre'] == 'ASC' ? 'DESC' : 'ASC' ?>">Nom</a></th>
                <th><a href="?tri_colonne=email&tri_ordre=<?= isset($_GET['tri_ordre']) && $_GET['tri_ordre'] == 'ASC' ? 'DESC' : 'ASC' ?>">Email</a></th>
                <th><a href="?tri_colonne=role&tri_ordre=<?= isset($_GET['tri_ordre']) && $_GET['tri_ordre'] == 'ASC' ? 'DESC' : 'ASC' ?>">Rôle</a></th>
                <th>Date de Création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?= $utilisateur['id'] ?></td>
                <td><?= $utilisateur['nom'] ?></td>
                <td><?= $utilisateur['email'] ?></td>
                <td><?= $utilisateur['role'] ?></td>
                <td><?= $utilisateur['date_creation'] ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                        <input type="text" name="nom" value="<?= $utilisateur['nom'] ?>" required>
                        <input type="email" name="email" value="<?= $utilisateur['email'] ?>" required>
                        <select name="role" required>
                            <option value="administrateur" <?= $utilisateur['role'] === 'administrateur' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="manager" <?= $utilisateur['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                            <option value="employe" <?= $utilisateur['role'] === 'employe' ? 'selected' : '' ?>>Employé</option>
                        </select>
                        <button type="submit" name="update">Mettre à jour</button>
                    </form>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
                        <button type="submit" name="delete">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Statistiques</h2>
    <p>Nombre total d'utilisateurs : <?= $utilisateurController->compterUtilisateurs(); ?></p>
</body>
</html>
