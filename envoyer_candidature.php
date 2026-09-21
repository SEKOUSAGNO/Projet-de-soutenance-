<?php

session_start();


if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}


require_once("../database.php");



/*=====================================
= PHPMailer
=====================================*/

require "../PHPMailer-master/PHPMailer-master/src/Exception.php";
require "../PHPMailer-master/PHPMailer-master/src/PHPMailer.php";
require "../PHPMailer-master/PHPMailer-master/src/SMTP.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




/*=====================================
= Vérifier l'offre
=====================================*/

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    die("Offre invalide.");

}


$offre_id = intval($_GET['id']);





/*=====================================
= Récupérer étudiant
=====================================*/


$sqlEtudiant = "

SELECT

id,
utilisateur_id,
prenom,
nom,
cv

FROM etudiant

WHERE utilisateur_id=$1

";



$resultEtudiant = pg_query_params(

$conn,

$sqlEtudiant,

array($_SESSION['id'])

);



if(!$resultEtudiant || pg_num_rows($resultEtudiant)==0){

    die("Profil étudiant introuvable.");

}



$etudiant = pg_fetch_assoc($resultEtudiant);



$etudiant_id = $etudiant['id'];

$cv = $etudiant['cv'];






/*=====================================
= Email étudiant
=====================================*/


$sqlEmail = "

SELECT email

FROM utilisateur

WHERE id=$1

";



$resultEmail = pg_query_params(

$conn,

$sqlEmail,

array($_SESSION['id'])

);



$email_etudiant="";



if($resultEmail && pg_num_rows($resultEmail)>0){

    $ligne = pg_fetch_assoc($resultEmail);

    $email_etudiant = $ligne['email'];

}





/*=====================================
= Récupérer offre
=====================================*/


$sqlOffre = "

SELECT *

FROM offre

WHERE id=$1

";



$resultOffre = pg_query_params(

$conn,

$sqlOffre,

array($offre_id)

);



if(!$resultOffre || pg_num_rows($resultOffre)==0){

    die("Offre inexistante.");

}



$offre = pg_fetch_assoc($resultOffre);






/*=====================================
= Vérifier candidature existante
=====================================*/


$sqlExiste = "

SELECT id

FROM candidature

WHERE etudiant_id=$1

AND offre_id=$2

";



$resultExiste = pg_query_params(

$conn,

$sqlExiste,

array(

$etudiant_id,

$offre_id

)

);



if(pg_num_rows($resultExiste)>0){


echo "

<script>

alert('Vous avez déjà envoyé une candidature pour cette offre.');

window.location='candidatures.php';

</script>

";


exit();


}





/*=====================================
= Envoi email
=====================================*/


$mail = new PHPMailer(true);



try{


$mail->isSMTP();


$mail->Host="smtp.gmail.com";


$mail->SMTPAuth=true;


/*
REMPLACER PAR VOS INFORMATIONS
*/

$mail->Username="sekouvintsagno@gmail.com";

$mail->Password="jqlebkaljnzbnrum";


$mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;


$mail->Port=587;


$mail->CharSet="UTF-8";





$mail->setFrom(

"votre_email@gmail.com",

"SIMAN-JOB"

);



$mail->addAddress(

$offre['email_reception']

);



$mail->isHTML(true);



$mail->Subject="Candidature - ".$offre['titre'];





$mail->Body="

<h3>Nouvelle candidature SIMAN-JOB</h3>

<p>

Bonjour,

</p>


<p>

Je vous adresse ma candidature pour l'offre :

<strong>".$offre['titre']."</strong>

</p>


<p>

Candidat :

".$etudiant['prenom']." ".$etudiant['nom']."

</p>


<p>

Email :

".$email_etudiant."

</p>


<p>

Merci de prendre en considération ma candidature.

</p>


<hr>

<p>

Envoyé depuis la plateforme SIMAN-JOB

</p>

";





/*=====================================
= Ajouter CV
=====================================*/


if(!empty($cv)){


$cheminCV="../".$cv;


if(file_exists($cheminCV)){


$mail->addAttachment($cheminCV);


}


}






$mail->send();







/*=====================================
= Enregistrer candidature
=====================================*/


$sqlInsert="

INSERT INTO candidature

(

etudiant_id,

offre_id,

date_candidature,

statut,

cv_personnalise

)


VALUES

(

$1,

$2,

CURRENT_TIMESTAMP,

'En attente',

$3

)

";





$resultInsert=pg_query_params(

$conn,

$sqlInsert,

array(

$etudiant_id,

$offre_id,

$cv

)

);





if(!$resultInsert){


die(

"Erreur insertion candidature : "

.pg_last_error($conn)

);


}







echo "

<script>

alert('Votre candidature a été envoyée avec succès.');

window.location='candidatures.php';

</script>

";




}

catch(Exception $e){


echo "

<script>

alert('Erreur PHPMailer : ".$mail->ErrorInfo."');

history.back();

</script>

";


}



?>