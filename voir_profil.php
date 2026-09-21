<?php

session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

$utilisateur_id = $_SESSION['id'];


// Récupérer le profil de l'entreprise

$sql = "SELECT 
            nom_entreprise,
            adresse,
            telephone,
            secteur_activite
        FROM recruteur_entreprise
        WHERE utilisateur_id = $1";


$result = pg_query_params(
    $conn,
    $sql,
    array($utilisateur_id)
);


if (!$result) {

    die("Erreur PostgreSQL : " . pg_last_error($conn));

}


if (pg_num_rows($result) == 0) {

    die("Aucun profil entreprise trouvé.");

}


$profil = pg_fetch_assoc($result);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Profil Entreprise</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{

    background:#eef5ff;

}


.card{

    margin-top:50px;

    border-radius:15px;

}


.titre{

    color:#0d6efd;

}


.info{

    font-size:18px;

    margin-bottom:15px;

}


.icon{

    font-size:22px;

}


</style>


</head>


<body>


<div class="container">


<div class="card shadow">


<div class="card-body">


<h2 class="text-center titre">

🏢 Profil de l'entreprise

</h2>


<hr>


<div class="info">

<span class="icon">🏢</span>

<strong>Nom de l'entreprise :</strong>

<?php echo htmlspecialchars($profil['nom_entreprise']); ?>

</div>



<div class="info">

<span class="icon">📍</span>

<strong>Adresse :</strong>

<?php echo htmlspecialchars($profil['adresse']); ?>

</div>



<div class="info">

<span class="icon">☎</span>

<strong>Téléphone :</strong>

<?php echo htmlspecialchars($profil['telephone']); ?>

</div>



<div class="info">

<span class="icon">💼</span>

<strong>Secteur d'activité :</strong>

<?php echo htmlspecialchars($profil['secteur_activite']); ?>

</div>



<hr>



<div class="text-center">


<a href="profil.php" 
class="btn btn-warning btn-lg">

✏️ Modifier le profil

</a>



<a href="publier_offre.php" 
class="btn btn-primary btn-lg">

📝 Publier une offre

</a>


<a href="dashboard.php"
class="btn btn-secondary btn-lg">

Retour

</a>


</div>


</div>


</div>


</div>


</body>


</html>