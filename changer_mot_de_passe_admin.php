<?php

session_start();

require_once("../database.php");


if (!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

header("Location: ../login_public.php");
exit();

}


if(isset($_POST['password'])){


$id=$_SESSION['id'];


$ancien=$_POST['ancien'];

$nouveau=$_POST['nouveau'];



// récupérer ancien mot de passe

$sql="SELECT mot_de_passe
      FROM utilisateur
      WHERE id=$1";


$resultat=pg_query_params(
$conn,
$sql,
array($id)
);


$user=pg_fetch_assoc($resultat);



if(!password_verify($ancien,$user['mot_de_passe'])){


echo "<script>

alert('Ancien mot de passe incorrect');

window.location='changer_mot_de_passe_admin.php';

</script>";

exit();

}



$hash=password_hash(
$nouveau,
PASSWORD_DEFAULT
);



$sql="UPDATE utilisateur

SET mot_de_passe=$1

WHERE id=$2";



pg_query_params(

$conn,

$sql,

array($hash,$id)

);



echo "<script>

alert('Mot de passe modifié avec succès');

window.location='profil_admin.php';

</script>";

exit();


}


?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Changer mot de passe</title>


<style>

body{

font-family:Arial;
background:#f2f2f2;

}


form{

width:400px;
margin:50px auto;
background:white;
padding:20px;
border-radius:10px;

}


input,button{

width:100%;
padding:12px;
margin:10px 0;

}


button{

background:#007bff;
color:white;
border:0;

}

</style>


</head>


<body>


<form method="POST">


<h2>
Changer mot de passe
</h2>


<input type="password"
name="ancien"
placeholder="Ancien mot de passe"
required>


<input type="password"
name="nouveau"
placeholder="Nouveau mot de passe"
required>


<button>

Modifier

</button>


</form>


</body>

</html>