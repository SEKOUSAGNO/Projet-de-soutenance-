<?php
session_start();


/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}


require_once("../database.php");


/*=========================================================
= Vérifier qu'une offre existe
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}


$offre_id = $_SESSION['offre_id'];



/*=========================================================
= Vérifier que les expériences existent
=========================================================*/

if (!isset($_SESSION['experiences'])) {

    header("Location: postuler_experiences.php");

    exit();

}



/*=========================================================
= Récupérer l'offre
=========================================================*/


$sqlOffre = "

SELECT

id,
titre,
entreprise,
lieu,
type_offre

FROM offre

WHERE id=$1

";


$resultOffre = pg_query_params(

$conn,

$sqlOffre,

array($offre_id)

);



if(!$resultOffre){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}



$offre = pg_fetch_assoc($resultOffre);



?>


<!DOCTYPE html>

<html lang="fr">

<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1">


<title>

Étape 5 - Compétences

</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">



<style>


body{

background:#eef4ff;

}



.card{

border:none;

border-radius:15px;

}



.progress{

height:12px;

}



.progress-bar{

background:#0d6efd;

}



.titre{

color:#0d6efd;

font-weight:bold;

}



label{

font-weight:bold;

}


</style>



</head>



<body>



<div class="container py-4">



<div class="card shadow">



<div class="card-body">



<div class="d-flex justify-content-between align-items-center">


<h3 class="titre">


<i class="fa-solid fa-star"></i>


Candidature sur la plateforme


</h3>



<h5>

Étape 5 / 7

</h5>



</div>



<div class="progress mt-3">


<div

class="progress-bar"

style="width:71.42%">

</div>


</div>




<p class="mt-3 text-muted">

Ajoutez vos compétences professionnelles.

</p>



<hr>




<div class="alert alert-primary">


<strong>Offre :</strong>

<?php echo htmlspecialchars($offre['titre']); ?>


<br>


<strong>Entreprise :</strong>

<?php echo htmlspecialchars($offre['entreprise']); ?>


</div>





<form

action="enregistrer_competences.php"

method="POST"

id="formCompetence">





<div id="conteneurCompetence">





<!--=====================================================
PREMIERE COMPETENCE
======================================================-->



<div class="card border-primary mb-4 competence-item">



<div class="card-header bg-primary text-white">


<h5 class="mb-0">


<i class="fa-solid fa-check"></i>


Compétence 1


</h5>


</div>




<div class="card-body">



<div class="row">



<div class="col-md-6 mb-3">


<label>

Nom de la compétence

</label>


<input

type="text"

name="competence[]"

class="form-control"

placeholder="Ex : PHP, Java, Gestion de projet"

required>


</div>





<div class="col-md-6 mb-3">


<label>

Niveau

</label>


<select

name="niveau[]"

class="form-select"

required>


<option value="">Sélectionner</option>

<option value="Débutant">Débutant</option>

<option value="Intermédiaire">Intermédiaire</option>

<option value="Avancé">Avancé</option>

<option value="Expert">Expert</option>


</select>


</div>



</div>



</div>


</div>





</div>






<hr>




<div class="d-flex justify-content-between">



<a

href="postuler_experiences.php"

class="btn btn-secondary btn-lg">


<i class="fa-solid fa-arrow-left"></i>


Précédent


</a>




<button

type="button"

id="ajouterCompetence"

class="btn btn-success btn-lg">


<i class="fa-solid fa-plus"></i>


Ajouter une compétence


</button>




<button

type="submit"

class="btn btn-primary btn-lg">


Suivant


<i class="fa-solid fa-arrow-right"></i>


</button>




</div>





</form>




</div>


</div>


</div>