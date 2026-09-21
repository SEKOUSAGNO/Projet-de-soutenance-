<?php

session_start();


if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}


require_once("../database.php");



/*=====================================
= Vérifier l'identifiant de l'offre
=====================================*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    die("Offre invalide.");

}


$id = intval($_GET['id']);




/*=====================================
= Récupérer l'offre
=====================================*/


$sql = "

SELECT *

FROM offre

WHERE id=$1

LIMIT 1

";



$result = pg_query_params(

    $conn,

    $sql,

    array($id)

);



if (!$result) {

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}



if (pg_num_rows($result)==0) {

    die("Cette offre n'existe pas.");

}



$offre = pg_fetch_assoc($result);



?>


<!DOCTYPE html>

<html lang="fr">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1">


<title>

<?php echo htmlspecialchars($offre['titre']); ?>

</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">



<style>


body{

    background:#eef5ff;

    font-family:Arial, Helvetica, sans-serif;

}



.container{

    margin-top:40px;

}



.card{

    border-radius:15px;

    box-shadow:0px 0px 15px rgba(0,0,0,0.2);

}



.card-header{

    background:#0d6efd;

    color:white;

    text-align:center;

    font-size:25px;

    font-weight:bold;

}



.titre-offre{

    color:#0d6efd;

    font-weight:bold;

}



.info-box{

    background:#f8f9fa;

    padding:15px;

    border-radius:10px;

}



.description{

    background:white;

    border-left:5px solid #0d6efd;

    padding:20px;

    border-radius:10px;

    line-height:1.7;

}



.btn{

    border-radius:10px;

}



</style>



</head>



<body>



<div class="container">



<div class="card shadow">



<div class="card-header">


📄 Détail de l'offre


</div>



<div class="card-body">



<h2 class="titre-offre">

<?php echo htmlspecialchars($offre['titre']); ?>

</h2>



<hr>



<div class="row">



<div class="col-md-6">



<div class="info-box">


<p>

<strong>Entreprise :</strong><br>

<?php echo htmlspecialchars($offre['entreprise']); ?>

</p>



<p>

<strong>Type d'offre :</strong><br>

<?php echo htmlspecialchars($offre['type_offre']); ?>

</p>



<p>

<strong>Lieu :</strong><br>

<?php echo htmlspecialchars($offre['lieu']); ?>

</p>


</div>



</div>




<div class="col-md-6">



<div class="info-box">



<p>

<strong>Date publication :</strong><br>


<?php

echo date(

"d/m/Y",

strtotime($offre['date_publication'])

);

?>


</p>



<p>

<strong>Date limite :</strong><br>


<?php

echo date(

"d/m/Y",

strtotime($offre['date_limite'])

);

?>


</p>




<p>

<strong>Mode candidature :</strong><br>


<span class="badge bg-primary">

<?php echo htmlspecialchars($offre['mode_candidature']); ?>

</span>


</p>


</div>



</div>



</div>



<hr>



<h4 class="text-primary">

Description complète

</h4>



<div class="description">


<?php

echo nl2br(

htmlspecialchars($offre['description'])

);

?>


</div>



<hr>



<div class="alert alert-info">


<strong>Email de réception :</strong>

<br>


<?php

echo htmlspecialchars($offre['email_reception']);

?>


</div>





<div class="row mt-4">



<div class="col-md-4 mb-3">



<a

href="telecharger_offre.php?id=<?php echo $offre['id']; ?>"

class="btn btn-danger w-100">


📄 Télécharger PDF


</a>



</div>





<div class="col-md-4 mb-3">



<a

href="envoyer_candidature.php?id=<?php echo $offre['id']; ?>"

class="btn btn-success w-100">


✉ Envoyer ma candidature


</a>



</div>





<div class="col-md-4 mb-3">



<a

href="offres.php"

class="btn btn-secondary w-100">


⬅ Retour


</a>



</div>



</div>





</div>



</div>



</div>



</body>


</html>