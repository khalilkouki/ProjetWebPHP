<?php
require_once '../../config/database.php';
require_once '../../controllers/CategorieController.php';

$database = new Database();
$db = $database->getConnection();

$categorieController = new CategorieController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $categorieController->creerCategorie($_POST);
    } elseif (isset($_POST['update'])) {
        $categorieController->mettreAJourCategorie($_POST['id'], $_POST);
    } elseif (isset($_POST['delete'])) {
        $categorieController->supprimerCategorie($_POST['id']);
    }
}

$categories = json_decode($categorieController->lireCategories(), true);
$title = "Gestion des Catégories";
ob_start();
?>

<h2>Créer une nouvelle catégorie</h2>
<form action="" method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="number" name="departement_id" placeholder="ID Département" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="create">Créer</button>
</form>

<h2>Liste des catégories</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>ID Département</th>
            <th>Description</th>
            <th>Date de Création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $categorie): ?>
        <tr>
            <td><?= $categorie['id'] ?></td>
            <td><?= $categorie['nom'] ?></td>
            <td><?= $categorie['departement_id'] ?></td>
            <td><?= $categorie['description'] ?></td>
            <td><?= $categorie['date_creation'] ?></td>
            <td>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $categorie['id'] ?>">
                    <input type="text" name="nom" value="<?= $categorie['nom'] ?>" required>
                    <input type="number" name="departement_id" value="<?= $categorie['departement_id'] ?>" required>
                    <textarea name="description"><?= $categorie['description'] ?></textarea>
                    <button type="submit" name="update">Mettre à jour</button>
                </form>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $categorie['id'] ?>">
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
