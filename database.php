<?php

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');

echo "<h2>Test des variables Render</h2>";
echo "DB_HOST = [" . htmlspecialchars($host ?? 'NULL') . "]<br>";
echo "DB_PORT = [" . htmlspecialchars($port ?? 'NULL') . "]<br>";
echo "DB_NAME = [" . htmlspecialchars($dbname ?? 'NULL') . "]<br>";
echo "DB_USER = [" . htmlspecialchars($user ?? 'NULL') . "]<br>";

exit;
?>
