<?php

session_start();

require_once("../database.php");


if (!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

header("Location: ../login_public.php");
exit();

}


$id=$_SESSION['id'];



$sql="SELECT nom,prenom,email,fonction
      FROM utilisateur
      WHERE id=$1";


$resultat=pg_query_params(
$conn,
$sql,
array($id)
);


$admin=pg_fetch_assoc($resultat);


?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Modifier profil</title>


<style>

body{
font-family:Arial;
background:#f2f2f2;
}


form{

width:450px;
margin:40px auto;
background:white;
padding:20px;
border-radius:10px;

}


input{

width:100%;
padding:10px;
margin:10px 0;

}


button{

width:100%;
padding:12px;
background:green;
color:white;
border:0;

}


</style>

</head>


<body>


<form action="profil_admin.php" method="POST">


<h2>
Modifier mon profil
</h2>


<input type="text"
name="nom"
value="<?= $admin['nom']; ?>">


<input type="text"
name="prenom"
value="<?= $admin['prenom']; ?>">


<input type="email"
name="email"
value="<?= $admin['email']; ?>">


<input type="text"
name="fonction"
value="<?= $admin['fonction']; ?>">



<button type="submit">

Enregistrer

</button>


</form>


</body>

</html>