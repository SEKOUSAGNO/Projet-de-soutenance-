<?php

session_start();

require_once("../database.php");


// =====================================
// Vérifier que l'utilisateur est Admin
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}



// =====================================
// Vérifier que l'utilisateur est Administrateur principal
// =====================================

$sql_droit = "SELECT fonction
              FROM utilisateur
              WHERE id = $1
              AND role = 'Admin'";


$resultat_droit = pg_query_params(
    $conn,
    $sql_droit,
    array($_SESSION['id'])
);



$admin_connecte = pg_fetch_assoc($resultat_droit);



if (!$admin_connecte || $admin_connecte['fonction'] != "Administrateur principal") {


    echo "<script>

            alert('Accès refusé. Seul l\\'administrateur principal peut modifier un administrateur.');

            window.location='administradores.php';

          </script>";

    exit();

}



// =====================================
// Vérifier l'identifiant reçu
// =====================================

if (!isset($_GET['id'])) {

    header("Location: administrateurs.php");
    exit();

}



$id = (int) $_GET['id'];




// =====================================
// Récupérer l'administrateur à modifier
// =====================================

$sql = "SELECT id,
               nom,
               prenom,
               email,
               fonction,
               statut
        FROM utilisateur
        WHERE id = $1
        AND role = 'Admin'";


$resultat = pg_query_params(
    $conn,
    $sql,
    array($id)
);



if (!$resultat || pg_num_rows($resultat)==0) {


    echo "<script>

            alert('Administrateur introuvable.');

            window.location='administrateurs.php';

          </script>";

    exit();

}



$admin = pg_fetch_assoc($resultat);


?>



<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Modifier administrateur</title>


<style>

body{

font-family:Arial;
background:#f2f2f2;

}


.container{

width:450px;
margin:40px auto;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px gray;

}


h1{

text-align:center;
color:#007bff;

}


input,select{

width:100%;
padding:10px;
margin-top:10px;

}


button{

width:100%;
padding:12px;
margin-top:20px;
background:#ff9800;
color:white;
border:none;
font-size:18px;
border-radius:5px;

}


.retour{

display:block;
margin-top:20px;
text-align:center;

}


</style>


</head>



<body>



<div class="container">



<h1>
Modifier administrateur
</h1>




<form action="mettre_a_jour_admin.php" method="POST">



<input type="hidden"
name="id"
value="<?= $admin['id']; ?>">





<input type="text"
name="nom"
value="<?= htmlspecialchars($admin['nom']); ?>"
required>





<input type="text"
name="prenom"
value="<?= htmlspecialchars($admin['prenom']); ?>"
required>





<input type="email"
name="email"
value="<?= htmlspecialchars($admin['email']); ?>"
required>





<input type="text"
name="fonction"
value="<?= htmlspecialchars($admin['fonction']); ?>"
required>





<select name="statut">



<option value="Actif"
<?php if($admin['statut']=="Actif") echo "selected"; ?>>

Actif

</option>





<option value="Désactivé"
<?php if($admin['statut']=="Désactivé") echo "selected"; ?>>

Désactivé

</option>



</select>





<input type="password"
name="password"
placeholder="Nouveau mot de passe (laisser vide pour garder l'ancien)">





<button type="submit">

Enregistrer les modifications

</button>



</form>




<a class="retour" href="administrateurs.php">

⬅ Retour

</a>



</div>



</body>

</html>