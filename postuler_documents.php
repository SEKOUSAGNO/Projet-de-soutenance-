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
= Vérifier qu'une candidature existe
=========================================================*/

if (!isset($_SESSION['candidature_id'])) {

    die("Aucune candidature en cours.");

}


$candidature_id = $_SESSION['candidature_id'];



/*=========================================================
= Vérifier l'offre
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}


$offre_id = $_SESSION['offre_id'];



/*=========================================================
= Récupérer les informations de l'offre
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

Étape 7 - Documents joints

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

<i class="fa-solid fa-file-circle-check"></i>

Candidature sur la plateforme

</h3>


<h5>

Étape 7 / 8

</h5>


</div>



<div class="progress mt-3">


<div

class="progress-bar"

style="width:87.5%">

</div>


</div>



<p class="mt-3 text-muted">

Ajoutez les documents nécessaires à votre candidature.

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

action="enregistrer_documents.php"

method="POST"

enctype="multipart/form-data">



<div id="conteneurDocuments">



<!--========================
DOCUMENT 1
=========================-->

<div class="card border-primary mb-4 document-item">


<div class="card-header bg-primary text-white">


<h5>

<i class="fa-solid fa-file"></i>

Document 1

</h5>


</div>



<div class="card-body">


<div class="row">


<div class="col-md-6 mb-3">


<label>

Nom du document

</label>


<input

type="text"

name="nom_document[]"

class="form-control"

placeholder="Ex : Lettre de motivation"

required>


</div>




<div class="col-md-6 mb-3">


<label>

Fichier PDF

</label>


<input

type="file"

name="fichier[]"

class="form-control"

accept=".pdf"

required>


</div>



</div>


</div>


</div>



</div>




<button

type="button"

id="ajouterDocument"

class="btn btn-success btn-lg">

<i class="fa-solid fa-plus"></i>

Ajouter un document

</button>



<hr>



<div class="d-flex justify-content-between">


<a

href="postuler_references.php"

class="btn btn-secondary btn-lg">

<i class="fa-solid fa-arrow-left"></i>

Précédent

</a>



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


let numeroDocument = 1;



document.getElementById("ajouterDocument").addEventListener("click",function(){


numeroDocument++;


let html = `


<div class="card border-primary mb-4 document-item">


<div class="card-header bg-primary text-white d-flex justify-content-between">


<h5>

<i class="fa-solid fa-file"></i>

Document ${numeroDocument}

</h5>



<button

type="button"

class="btn btn-danger btn-sm supprimerDocument">

<i class="fa-solid fa-trash"></i>

</button>


</div>



<div class="card-body">


<div class="row">


<div class="col-md-6 mb-3">


<label>

Nom du document

</label>


<input

type="text"

name="nom_document[]"

class="form-control"

required>


</div>




<div class="col-md-6 mb-3">


<label>

Fichier PDF

</label>


<input

type="file"

name="fichier[]"

class="form-control"

accept=".pdf"

required>


</div>


</div>


</div>


</div>


`;



document.getElementById("conteneurDocuments")
.insertAdjacentHTML("beforeend",html);



});





document.addEventListener("click",function(e){


if(e.target.closest(".supprimerDocument")){


e.target.closest(".document-item").remove();


}


});


</script>



</body>

</html>