<?php

// Connexion PostgreSQL
// Sur Render : utilisation de DATABASE_URL
// En local : utilisation de la base Projet-memoire

if (getenv('DATABASE_URL')) {

    // Connexion à PostgreSQL sur Render
    $conn = pg_connect(getenv('DATABASE_URL'));

} else {

    // Connexion PostgreSQL en local
    $conn = pg_connect(
        "host=localhost " .
        "port=5432 " .
        "dbname=Projet-memoire " .
        "user=postgres " .
        "password=1234"
    );
}

// Vérification de la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

?>
