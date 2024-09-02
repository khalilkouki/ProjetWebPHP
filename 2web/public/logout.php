<?php
session_start(); // Démarrer la session

// Détruire toutes les données de la session
session_unset();
session_destroy();

// Rediriger l'utilisateur vers la page de connexion
header("Location: /public/login.php"); // Assurez-vous que ce chemin est correct
exit();
