<?php

session_start();

require_once("../database.php");


// =====================================
// Vérifier connexion Admin
// =====================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");
    exit();

}



// =====================================
// Vérifier Administrateur principal
// =====================================


$sql="
SELECT fonction
FROM utilisateur
WHERE id=$1
AND role='Admin'
";


$resultat=pg_query_params(
    $conn,
    $sql,
    array($_SESSION['id'])
);


$admin=pg_fetch_assoc($resultat);



if(!$admin || $admin['fonction']!="Administrateur principal"){


    echo "

    <script>

    alert('Seul l’administrateur principal peut ajouter un administrateur.');

    window.location='administrateurs.php';

    </script>

    ";


    exit();

}


?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>
Ajouter un administrateur - SIMAN-JOB
</title>


<style>


body{

    margin:0;

    font-family:Arial, Helvetica, sans-serif;

    background:#ecf2f7;

}



.container{

    width:450px;

    margin:50px auto;

    background:white;

    padding:30px;

    border-radius:15px;

    box-shadow:0 0 15px #aaa;

}



h1{

    text-align:center;

    color:#003366;

    font-size:25px;

}



.info{

    background:#e3f2fd;

    padding:15px;

    border-radius:8px;

    margin-bottom:20px;

    font-size:14px;

}



label{

    font-weight:bold;

    display:block;

    margin-top:15px;

}



input,select{


    width:100%;

    padding:11px;

    margin-top:5px;

    border:1px solid #ccc;

    border-radius:6px;

    box-sizing:border-box;

}



button{


    width:100%;

    padding:12px;

    margin-top:25px;

    background:#003366;

    color:white;

    border:none;

    border-radius:6px;

    font-size:17px;

    cursor:pointer;


}



button:hover{

    background:#00509e;

}



.retour{


    display:block;

    text-align:center;

    margin-top:20px;

    text-decoration:none;

    color:#003366;

    font-weight:bold;

}



</style>


</head>



<body>



<div class="container">



<h1>

➕ Ajouter un administrateur

</h1>



<div class="info">

Le code concepteur est obligatoire pour créer un compte administrateur.

Seul l'administrateur principal peut effectuer cette opération.

</div>



<form action="enregistrer_admin.php" method="POST">



<label>

Nom

</label>


<input type="text"

name="nom"

required>



<label>

Prénom

</label>


<input type="text"

name="prenom"

required>




<label>

Adresse email

</label>


<input type="email"

name="email"

required>




<label>

Mot de passe

</label>


<input type="password"

name="password"

required>




<label>

Type administrateur

</label>


<select name="fonction" required>


<option value="Administrateur secondaire">

Administrateur secondaire

</option>


<option value="Administrateur principal">

Administrateur principal

</option>


</select>




<label>

Code concepteur

</label>


<input type="text"

name="code_concepteur"

placeholder="Ex: SIMAN-ADM-ABCD-1234"

required>



<button type="submit">

Créer l'administrateur

</button>



</form>




<a class="retour" href="administrateurs.php">

⬅ Retour

</a>



</div>



</body>

</html>