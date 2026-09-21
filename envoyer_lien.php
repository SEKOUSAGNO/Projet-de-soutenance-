<?php

session_start();

require "database.php";


/*=========================================================
    CHARGEMENT PHPMailer
=========================================================*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/PHPMailer-master/src/SMTP.php';



/*=========================================================
    Vérifier l'email reçu
=========================================================*/

if (!isset($_POST['email']) || empty($_POST['email'])) {

    die("Email obligatoire.");

}


$email = trim($_POST['email']);



/*=========================================================
    Vérifier l'utilisateur
=========================================================*/


$sql = "

SELECT id, nom, prenom, email

FROM utilisateur

WHERE email = $1

";


$resultat = pg_query_params(

    $conn,

    $sql,

    [$email]

);



if (!$resultat) {

    die("Erreur de connexion à la base.");

}



if (pg_num_rows($resultat) == 0) {

    die("Cette adresse email n'existe pas.");

}



$user = pg_fetch_assoc($resultat);



/*=========================================================
    Création du token
=========================================================*/


$token = bin2hex(random_bytes(32));


$expiration = date(
    "Y-m-d H:i:s",
    strtotime("+1 heure")
);



/*=========================================================
    Sauvegarde du token
=========================================================*/


$sql = "

UPDATE utilisateur

SET reset_token = $1,
    reset_expiration = $2

WHERE id = $3

";


$update = pg_query_params(

    $conn,

    $sql,

    [
        $token,
        $expiration,
        $user['id']
    ]

);



if (!$update) {

    die("Erreur lors de la sauvegarde du token.");

}



/*=========================================================
    Création du lien
=========================================================*/


$lien = 

"http://localhost:8081/memoire/nouveau_mot_de_passe.php?token=".$token;




/*=========================================================
    ENVOI EMAIL PHPMailer
=========================================================*/


$mail = new PHPMailer(true);


try {


    $mail->isSMTP();


    $mail->Host = "smtp.gmail.com";


    $mail->SMTPAuth = true;



    /*
    =====================================================
    REMPLACEZ SEULEMENT CES DEUX VALEURS
    =====================================================
    */


$mail->isSMTP();

$mail->Host = "smtp.gmail.com";

$mail->SMTPAuth = true;

$mail->Username = "sekouvintsagno@gmail.com";

$mail->Password = "jqlebkaljnzbnrum";

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

$mail->Port = 587;


$mail->setFrom(
    "sekouvintsagno@gmail.com",
    "Plateforme Emploi et Stage UKAG"
);


    /*
       Destinataire
    */


    $mail->addAddress(

        $user['email'],

        $user['prenom']." ".$user['nom']

    );



    $mail->isHTML(true);



    $mail->Subject = 
    "Réinitialisation du mot de passe";



    $mail->Body = "

    <html>

    <body>

    <h2>
    Plateforme Emploi et Stage UKAG
    </h2>


    <p>
    Bonjour <b>".$user['prenom']." ".$user['nom']."</b>
    </p>


    <p>
    Vous avez demandé la réinitialisation de votre mot de passe.
    </p>


    <p>
    Cliquez sur le lien suivant :
    </p>


    <p>

    <a href='".$lien."'>

    Réinitialiser mon mot de passe

    </a>

    </p>


    <p>
    Ce lien expire dans une heure.
    </p>


    </body>

    </html>

    ";



   echo "

<div style='
width:400px;
margin:50px auto;
padding:25px;
background:#d4edda;
color:#155724;
text-align:center;
border-radius:10px;
font-family:Arial;
'>

<h3>
Email envoyé avec succès
</h3>

<p>
Consultez votre boîte mail pour réinitialiser votre mot de passe.
</p>


<a href='login_public.php'

style='

display:inline-block;
margin-top:20px;
padding:12px 25px;
background:#3498db;
color:white;
text-decoration:none;
border-radius:5px;

'>

Retour à la page de connexion

</a>


</div>

";



}


catch(Exception $e){


    echo "

    <h3 style='color:red;text-align:center'>

    Erreur d'envoi :

    </h3>


    <p style='text-align:center'>

    ".$mail->ErrorInfo."

    </p>

    ";


}


?>