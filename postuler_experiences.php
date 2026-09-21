<?php
session_start();

/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}


/*=========================================================
= Vérifier qu'une offre existe
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}

$offre_id = $_SESSION['offre_id'];


/*=========================================================
= Vérifier que les formations existent
=========================================================*/

if (!isset($_SESSION['formations'])) {

    header("Location: postuler_formations.php");
    exit();

}


require_once("../database.php");


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

Étape 4 - Expériences professionnelles

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

<i class="fa-solid fa-briefcase"></i>

Candidature sur la plateforme

</h3>


<h5>

Étape 4 / 7

</h5>


</div>


<div class="progress mt-3">


<div

class="progress-bar"

style="width:57.14%">

</div>


</div>


<p class="mt-3 text-muted">

Ajoutez vos expériences professionnelles.

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

action="enregistrer_experiences.php"

method="POST"

id="formExperience">



<div id="conteneurExperience">



<!--=====================================================
PREMIERE EXPERIENCE
======================================================-->


<div class="card border-primary mb-4 experience-item">


<div class="card-header bg-primary text-white">


<h5 class="mb-0">


<i class="fa-solid fa-user-tie"></i>


Expérience professionnelle 1


</h5>


</div>



<div class="card-body">


<div class="row">



<div class="col-md-6 mb-3">


<label>

Entreprise

</label>


<input

type="text"

name="entreprise[]"

class="form-control"

placeholder="Nom de l'entreprise"

required>


</div>




<div class="col-md-6 mb-3">


<label>

Poste occupé

</label>


<input

type="text"

name="poste[]"

class="form-control"

placeholder="Ex : Développeur Web"

required>


</div>




<div class="col-md-6 mb-3">


<label>

Date début

</label>


<input

type="date"

name="date_debut[]"

class="form-control"

required>


</div>




<div class="col-md-6 mb-3">


<label>

Date fin

</label>


<input

type="date"

name="date_fin[]"

class="form-control">


</div>




<div class="col-md-12 mb-3">


<label>

Description

</label>


<textarea

name="description[]"

class="form-control"

rows="3"

placeholder="Décrivez vos missions">

</textarea>


</div>


</div>


</div>


</div>


</div>




<hr>


<div class="d-flex justify-content-between">



<a

href="postuler_formations.php"

class="btn btn-secondary btn-lg">


<i class="fa-solid fa-arrow-left"></i>

Précédent


</a>




<button

type="button"

id="ajouterExperience"

class="btn btn-success btn-lg">


<i class="fa-solid fa-plus"></i>

Ajouter une expérience


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





<script>


let numeroExperience = 1;



/*=========================================================
AJOUTER UNE EXPERIENCE
=========================================================*/


document.getElementById("ajouterExperience")
.addEventListener("click",function(){


numeroExperience++;


let html = `


<div class="card border-primary mb-4 experience-item">


<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">


<h5 class="mb-0">

<i class="fa-solid fa-user-tie"></i>

Expérience professionnelle ${numeroExperience}

</h5>



<button

type="button"

class="btn btn-danger btn-sm supprimerExperience">


<i class="fa-solid fa-trash"></i>


</button>


</div>



<div class="card-body">


<div class="row">



<div class="col-md-6 mb-3">

<label>Entreprise</label>

<input

type="text"

name="entreprise[]"

class="form-control"

required>

</div>



<div class="col-md-6 mb-3">

<label>Poste occupé</label>

<input

type="text"

name="poste[]"

class="form-control"

required>

</div>



<div class="col-md-6 mb-3">

<label>Date début</label>

<input

type="date"

name="date_debut[]"

class="form-control"

required>

</div>



<div class="col-md-6 mb-3">

<label>Date fin</label>

<input

type="date"

name="date_fin[]"

class="form-control">

</div>



<div class="col-md-12 mb-3">

<label>Description</label>

<textarea

name="description[]"

class="form-control"

rows="3">

</textarea>

</div>



</div>


</div>


</div>



`;



document.getElementById("conteneurExperience")
.insertAdjacentHTML("beforeend",html);



});




/*=========================================================
SUPPRIMER EXPERIENCE
=========================================================*/


document.addEventListener("click",function(e){


if(e.target.closest(".supprimerExperience")){


e.target.closest(".experience-item").remove();


}



});


</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>