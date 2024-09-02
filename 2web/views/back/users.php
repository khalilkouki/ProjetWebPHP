<?php
require_once '../../config/database.php';
require_once '../../controllers/UtilisateurController.php';

$database = new Database();
$db = $database->getConnection();

$utilisateurController = new UtilisateurController($db);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $result = $utilisateurController->creerUtilisateur($_POST);
        if (is_array($result)) {
            $errors = $result;
        } else {
            header('Location: users.php');
            exit();
        }
    } elseif (isset($_POST['update'])) {
        $utilisateurController->mettreAJourUtilisateur($_POST['id'], $_POST);
    } elseif (isset($_POST['delete'])) {
        $utilisateurController->supprimerUtilisateur($_POST['id']);
    }
}

$tri_colonne = isset($_GET['tri_colonne']) ? $_GET['tri_colonne'] : 'id';
$tri_ordre = isset($_GET['tri_ordre']) && $_GET['tri_ordre'] === 'ASC' ? 'ASC' : 'DESC';
$utilisateurs = json_decode($utilisateurController->lireUtilisateurs($tri_colonne, $tri_ordre), true);

$title = "Gestion des Utilisateurs";
ob_start();
?>

<div class="container">
    <?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h2>Créer un nouvel utilisateur</h2>
    <form id="createUserForm" action="" method="POST">
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
        <select name="tri_colonne">
            <option value="id">Trier par ID</option>
            <option value="nom">Trier par Nom</option>
            <option value="email">Trier par Email</option>
            <option value="role">Trier par Rôle</option>
        </select>
        <select name="tri_ordre">
            <option value="ASC">Ordre Ascendant</option>
            <option value="DESC">Ordre Descendant</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>

    <h2>Liste des utilisateurs</h2>
    <table border="1" class="user-table">
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
</div>

<script>
document.getElementById('createUserForm').addEventListener('submit', function(event) {
    var nom = document.querySelector('input[name="nom"]').value.trim();
    var email = document.querySelector('input[name="email"]').value.trim();
    var motDePasse = document.querySelector('input[name="mot_de_passe"]').value.trim();
    var role = document.querySelector('select[name="role"]').value;

    var errors = [];

    if (nom === '') {
        errors.push('Le nom est requis.');
    }

    if (email === '' || !validateEmail(email)) {
        errors.push('Un email valide est requis.');
    }

    if (motDePasse.length < 6) {
        errors.push('Le mot de passe doit comporter au moins 6 caractères.');
    }

    if (role === '') {
        errors.push('Le rôle est requis.');
    }

    if (errors.length > 0) {
        alert(errors.join('\n'));
        event.preventDefault();
    }
});

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+.[^\s@]+$/;
    return re.test(email);
}
</script>

<?php
$content = ob_get_clean();
include 'base_back.php';
?>
