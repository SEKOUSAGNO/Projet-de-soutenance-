<?php
// 1. Récupération de l'URL PostgreSQL de Render
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl !== false && trim($databaseUrl) !== '') {

    // Connexion avec l'URL fournie par Render
    $conn = @pg_connect($databaseUrl);

} else {

    // 2. Connexion avec les variables individuelles
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $dbname = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    if (!$host || !$port || !$dbname || !$user || !$password) {
        die("Erreur : les variables de connexion PostgreSQL sont absentes sur Render.");
    }

    // Protection des valeurs dans la chaîne de connexion
    $quote = function ($value) {
        return "'" . str_replace(
            ["\\", "'"],
            ["\\\\", "\\'"],
            $value
        ) . "'";
    };

    $connectionString =
        "host=" . $quote($host) .
        " port=" . $quote($port) .
        " dbname=" . $quote($dbname) .
        " user=" . $quote($user) .
        " password=" . $quote($password);

    $conn = @pg_connect($connectionString);
}

// 3. Vérification de la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données PostgreSQL. Vérifiez les paramètres de Render.");
}

?>
