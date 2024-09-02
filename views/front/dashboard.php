<?php
$title = "Tableau de bord";
ob_start();
?>

<h2>Tableau de bord</h2>
<p>Bienvenue sur votre tableau de bord.</p>

<?php
$content = ob_get_clean();
include 'base_front.php';
?>
