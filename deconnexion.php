<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Retourner au formulaire de connexion
header("Location: login_public.php");
exit();
?>