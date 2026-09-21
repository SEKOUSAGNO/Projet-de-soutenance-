<?php

session_start();

require_once("../database.php");


// =====================================
// Vérifier connexion Admin
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}



// =====================================
// Vérifier que l'utilisateur est Admin principal
// =====================================

$sql_droit = "SELECT fonction
              FROM utilisateur
              WHERE id=$1
              AND role='Admin'";


$resultat_droit = pg_query_params(

    $conn,

    $sql_droit,

    array($_SESSION['id'])

);



$admin_connecte = pg_fetch_assoc($resultat_droit);



if (!$admin_connecte || $admin_connecte['fonction'] != "Administrateur principal") {


    echo "<script>

            alert('Accès refusé. Seul l’administrateur principal peut modifier un administrateur.');

            window.location='administrateurs.php';

          </script>";

    exit();

}




// =====================================
// Vérifier réception des données
// =====================================

if(!isset($_POST['id'])){

    header("Location: administrateurs.php");
    exit();

}




// =====================================
// Récupération des données
// =====================================


$id = (int) $_POST['id'];

$nom = trim($_POST['nom']);

$prenom = trim($_POST['prenom']);

$email = trim($_POST['email']);

$fonction = trim($_POST['fonction']);

$statut = trim($_POST['statut']);

$password = trim($_POST['password']);




// =====================================
// Vérifier email déjà utilisé
// =====================================


$sql = "SELECT id
        FROM utilisateur
        WHERE email=$1
        AND id<>$2";


$resultat = pg_query_params(

    $conn,

    $sql,

    array($email,$id)

);



if(pg_num_rows($resultat)>0){


echo "<script>

alert('Cet email est déjà utilisé.');

window.location='modifier_admin.php?id=$id';

</script>";

exit();

}




// =====================================
// Mise à jour avec changement mot de passe
// =====================================


if(!empty($password)){



$password_hash = password_hash(

    $password,

    PASSWORD_DEFAULT

);



$sql = "UPDATE utilisateur

SET

nom=$1,

prenom=$2,

email=$3,

fonction=$4,

statut=$5,

mot_de_passe=$6

WHERE id=$7

AND role='Admin'";



$params=array(

$nom,

$prenom,

$email,

$fonction,

$statut,

$password_hash,

$id

);



}



// =====================================
// Mise à jour sans changer mot de passe
// =====================================


else{



$sql = "UPDATE utilisateur

SET

nom=$1,

prenom=$2,

email=$3,

fonction=$4,

statut=$5

WHERE id=$6

AND role='Admin'";



$params=array(

$nom,

$prenom,

$email,

$fonction,

$statut,

$id

);



}




// =====================================
// Exécution modification
// =====================================


$resultat = pg_query_params(

$conn,

$sql,

$params

);





if($resultat){


echo "<script>

alert('Administrateur modifié avec succès.');

window.location='administrateurs.php';

</script>";



}else{


echo "<script>

alert('Erreur lors de la modification.');

window.location='administrateurs.php';

</script>";



}



exit();


?>