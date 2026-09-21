<?php
$conn = pg_connect("host=localhost dbname=Projet-memoire user=postgres password=1234");

if (!$conn) {
    die("Erreur de connexion.");
}
?>