<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

$utilisateur_id = $_SESSION['id'];

/*==============================
 Récupérer l'étudiant
===============================*/

$sql = "SELECT id FROM etudiant WHERE utilisateur_id = $1";
$result = pg_query_params($conn, $sql, array($utilisateur_id));

if (!$result || pg_num_rows($result) == 0) {
    die("Vous devez d'abord compléter votre profil.");
}

$etudiant = pg_fetch_assoc($result);
$etudiant_id = $etudiant['id'];

/*==============================
 Récupérer le CV
===============================*/

$sql = "SELECT fichier_cv, date_depot
        FROM cv
        WHERE etudiant_id = $1";

$result = pg_query_params($conn, $sql, array($etudiant_id));

$cv = null;

if ($result && pg_num_rows($result) > 0) {
    $cv = pg_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Mon CV</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef5ff;
}

.card{
    margin-top:40px;
    border-radius:15px;
}

h2{
    color:#0d6efd;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center">

Mon Curriculum Vitae

</h2>

<hr>

<?php if($cv){ ?>

<div class="alert alert-success">

Votre CV est enregistré.

</div>

<p>

<strong>Date de dépôt :</strong>

<?php echo $cv['date_depot']; ?>

</p>

<a href="../<?php echo $cv['fichier_cv']; ?>"
target="_blank"
class="btn btn-success">

📄 Ouvrir le CV

</a>

<a href="../<?php echo $cv['fichier_cv']; ?>"
download
class="btn btn-primary">

⬇ Télécharger

</a>

<?php } else { ?>

<div class="alert alert-warning">

Aucun CV n'est enregistré.

</div>

<?php } ?>

<hr>

<a href="profil.php" class="btn btn-warning">

Modifier mon profil

</a>

<a href="dashboard.php" class="btn btn-secondary">

Retour au tableau de bord

</a>

</div>

</div>

</div>

</body>

</html>