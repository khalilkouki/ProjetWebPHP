<?php
require_once '../../config/database.php';
require_once '../../controllers/DepartementController.php';

$database = new Database();
$db = $database->getConnection();

$departementController = new DepartementController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $departementController->creerDepartement($_POST);
    } elseif (isset($_POST['update'])) {
        $departementController->mettreAJourDepartement($_POST['id'], $_POST);
    } elseif (isset($_POST['delete'])) {
        $departementController->supprimerDepartement($_POST['id']);
    }
}

$departements = json_decode($departementController->lireDepartements(), true);
$title = "Gestion des Départements";
ob_start();
?>

<h2>Créer un nouveau département</h2>
<form action="" method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="create">Créer</button>
</form>

<h2>Liste des départements</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Date de Création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($departements as $departement): ?>
        <tr>
            <td><?= $departement['id'] ?></td>
            <td><?= $departement['nom'] ?></td>
            <td><?= $departement['description'] ?></td>
            <td><?= $departement['date_creation'] ?></td>
            <td>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $departement['id'] ?>">
                    <input type="text" name="nom" value="<?= $departement['nom'] ?>" required>
                    <textarea name="description"><?= $departement['description'] ?></textarea>
                    <button type="submit" name="update">Mettre à jour</button>
                </form>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $departement['id'] ?>">
                    <button type="submit" name="delete">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include 'base_back.php';
?>
