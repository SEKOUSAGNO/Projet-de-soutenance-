<?php
session_start();

/*=========================================================
= Vérifier que le recruteur est connecté
=========================================================*/
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*=========================================================
= Récupérer l'ID du recruteur
=========================================================*/
$sqlRecruteur = "
SELECT id
FROM recruteur_entreprise
WHERE utilisateur_id = $1
";

$resultRecruteur = pg_query_params(
    $conn,
    $sqlRecruteur,
    array($_SESSION['id'])
);

if (!$resultRecruteur || pg_num_rows($resultRecruteur) == 0) {
    die("Profil recruteur introuvable.");
}

$recruteur = pg_fetch_assoc($resultRecruteur);
$recruteur_id = $recruteur['id'];

/*=========================================================
= Récupérer toutes les offres du recruteur
=========================================================*/
$sql = "
SELECT *
FROM offre
WHERE recruteur_id = $1
ORDER BY date_publication DESC
";

$result = pg_query_params(
    $conn,
    $sql,
    array($recruteur_id)
);
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Mes offres</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef5ff;
}

.card{
    margin-top:25px;
}

.badge{
    font-size:15px;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center text-primary">
📝 Mes offres publiées
</h2>

<hr>

<?php

if(pg_num_rows($result)==0){

echo "
<div class='alert alert-info text-center'>
Vous n'avez publié aucune offre.
</div>
";

}else{

while($offre = pg_fetch_assoc($result)){

?>

<div class="card mb-4">

<div class="card-body">

<h4 class="text-primary">

<?php echo htmlspecialchars($offre['titre']); ?>

</h4>

<hr>

<p>
<strong>Description :</strong><br>
<?php echo nl2br(htmlspecialchars($offre['description'])); ?>
</p>

<p>
<strong>Type :</strong>
<?php echo htmlspecialchars($offre['type_offre']); ?>
</p>

<p>
<strong>Entreprise :</strong>
<?php echo htmlspecialchars($offre['entreprise']); ?>
</p>

<p>
<strong>Lieu :</strong>
<?php echo htmlspecialchars($offre['lieu']); ?>
</p>

<p>
<strong>Date publication :</strong>
<?php echo htmlspecialchars($offre['date_publication']); ?>
</p>

<p>
<strong>Date limite :</strong>
<?php echo htmlspecialchars($offre['date_limite']); ?>
</p>

<p>
<strong>Mode candidature :</strong>
<?php echo htmlspecialchars($offre['mode_candidature']); ?>
</p>

<p>

<strong>Statut :</strong>

<?php

if($offre['statut']=="Active"){

echo "<span class='badge bg-success'>Active</span>";

}else{

echo "<span class='badge bg-danger'>Désactivée</span>";

}

?>

</p>

<hr>

<a href="modifier_offre.php?id=<?php echo $offre['id']; ?>"
class="btn btn-warning">

Modifier

</a>

<?php

if($offre['statut']=="Active"){

?>

<a href="changer_statut.php?id=<?php echo $offre['id']; ?>"
class="btn btn-secondary">

Désactiver

</a>

<?php

}else{

?>

<a href="changer_statut.php?id=<?php echo $offre['id']; ?>"
class="btn btn-success">

Activer

</a>

<?php

}

?>

<a href="supprimer_offre.php?id=<?php echo $offre['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?');">

Supprimer

</a>

</div>

</div>

<?php

}

}

?>

<div class="text-center mt-4">

<a href="dashboard.php" class="btn btn-secondary">
Retour au tableau de bord
</a>

<a href="publier_offre.php" class="btn btn-primary">
Nouvelle offre
</a>

</div>

</div>

</div>

</div>

</body>

</html>