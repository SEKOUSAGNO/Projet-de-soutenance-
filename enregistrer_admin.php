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

    alert('Seul l’administrateur principal peut créer un administrateur.');

    window.location='administrateurs.php';

    </script>

    ";

    exit();

}




// =====================================
// Récupération des données
// =====================================


$nom=trim($_POST['nom']);

$prenom=trim($_POST['prenom']);

$email=trim($_POST['email']);

$password=password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);


$fonction=$_POST['fonction'];

$code_concepteur=trim($_POST['code_concepteur']);






// =====================================
// Vérifier champs obligatoires
// =====================================


if(empty($nom) || 
   empty($prenom) || 
   empty($email) || 
   empty($code_concepteur)){


    echo "

    <script>

    alert('Veuillez remplir tous les champs.');

    window.location='ajouter_admin.php';

    </script>

    ";

    exit();

}





// =====================================
// Vérifier email existant
// =====================================


$sql="
SELECT id
FROM utilisateur
WHERE email=$1
";


$resultat=pg_query_params(

    $conn,

    $sql,

    array($email)

);



if(pg_num_rows($resultat)>0){


    echo "

    <script>

    alert('Cette adresse email existe déjà.');

    window.location='ajouter_admin.php';

    </script>

    ";

    exit();

}







// =====================================
// Vérifier code concepteur
// =====================================


$sql_code="

SELECT id

FROM code_concepteur

WHERE code=$1

AND actif=true

AND utilise=false

";



$resultat_code=pg_query_params(

    $conn,

    $sql_code,

    array($code_concepteur)

);





if(!$resultat_code || pg_num_rows($resultat_code)==0){


    echo "

    <script>

    alert('Code concepteur invalide, désactivé ou déjà utilisé.');

    window.location='ajouter_admin.php';

    </script>

    ";

    exit();

}



$code=pg_fetch_assoc($resultat_code);

$id_code=$code['id'];








// =====================================
// Création administrateur
// =====================================


$sql_insert="

INSERT INTO utilisateur

(

nom,

prenom,

email,

mot_de_passe,

role,

statut,

fonction

)


VALUES

(

$1,

$2,

$3,

$4,

'Admin',

'En attente',

$5

)

";




$creation=pg_query_params(

    $conn,

    $sql_insert,

    array(

        $nom,

        $prenom,

        $email,

        $password,

        $fonction

    )

);






if(!$creation){


    echo "

    <script>

    alert('Erreur lors de la création du compte.');

    window.location='ajouter_admin.php';

    </script>

    ";

    exit();

}







// =====================================
// Marquer le code concepteur utilisé
// =====================================


$sql_update="

UPDATE code_concepteur

SET utilise=true

WHERE id=$1

";



pg_query_params(

    $conn,

    $sql_update,

    array($id_code)

);







// =====================================
// Message final
// =====================================


echo "

<script>

alert('Administrateur créé avec succès. Il doit être activé par l’administrateur principal avant connexion.');

window.location='administrateurs.php';

</script>

";


exit();


?>