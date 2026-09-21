<?php

session_start();


if(!isset($_SESSION['id']) || $_SESSION['role']!="Etudiant"){

    header("Location: ../login_public.php");
    exit();

}


require_once("../database.php");

require_once("../fpdf/fpdf186/fpdf.php");



if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    die("Offre invalide.");

}


$id = intval($_GET['id']);



$sql = "

SELECT *

FROM offre

WHERE id=$1

";


$result = pg_query_params(

$conn,

$sql,

array($id)

);



if(!$result || pg_num_rows($result)==0){

    die("Offre inexistante.");

}


$offre = pg_fetch_assoc($result);





/*====================================
= Classe PDF personnalisée
====================================*/


class PDF extends FPDF
{


    function Header()
    {


        // Logo

        if(file_exists("../images/logo_siman_job.png")){


            $this->Image(

                "../images/logo_siman_job.png",

                10,

                8,

                35

            );

        }



        // Titre

        $this->SetFont(

            'Arial',

            'B',

            18

        );


        $this->SetTextColor(

            0,

            80,

            180

        );


        $this->Cell(

            0,

            15,

            utf8_decode("SIMAN-JOB"),

            0,

            1,

            "C"

        );



        $this->SetFont(

            'Arial',

            '',

            10

        );


        $this->SetTextColor(

            0,

            0,

            0

        );


        $this->Cell(

            0,

            8,

            utf8_decode("Plateforme de recherche d'emploi et de stage"),

            0,

            1,

            "C"

        );


        $this->Ln(10);


        // Ligne

        $this->Line(

            10,

            35,

            200,

            35

        );


    }




    function Footer()
    {


        $this->SetY(-20);


        $this->SetFont(

            'Arial',

            'I',

            9

        );


        $this->Cell(

            0,

            10,

            utf8_decode(

            "SIMAN-JOB - Offre générée automatiquement"

            ),

            0,

            0,

            "C"

        );


        $this->Ln(5);


        $this->Cell(

            0,

            10,

            utf8_decode(

            "Page ".$this->PageNo()

            ),

            0,

            0,

            "C"

        );


    }


}





$pdf = new PDF();



$pdf->AddPage();



$pdf->SetFont(

    'Arial',

    'B',

    15

);



$pdf->SetTextColor(

    0,

    80,

    180

);



$pdf->MultiCell(

    0,

    10,

    utf8_decode($offre['titre'])

);



$pdf->Ln(5);



/*===============================
Informations
================================*/


$pdf->SetFillColor(

    230,

    240,

    255

);



$pdf->SetFont(

    'Arial',

    '',

    12

);



$infos = "

Entreprise : ".$offre['entreprise']."



Type : ".$offre['type_offre']."



Lieu : ".$offre['lieu']."



Date publication : ".date(

"d/m/Y",

strtotime($offre['date_publication'])

)."



Date limite : ".date(

"d/m/Y",

strtotime($offre['date_limite'])

)."



Mode candidature : ".$offre['mode_candidature']."



Email : ".$offre['email_reception']."

";



$pdf->MultiCell(

    0,

    8,

    utf8_decode($infos),

    1,

    "L",

    true

);



$pdf->Ln(10);





/*===============================
Description
================================*/


$pdf->SetFont(

    'Arial',

    'B',

    14

);



$pdf->SetTextColor(

    0,

    80,

    180

);



$pdf->Cell(

    0,

    10,

    utf8_decode("Description de l'offre"),

    0,

    1

);



$pdf->SetFont(

    'Arial',

    '',

    12

);



$pdf->SetTextColor(

    0,

    0,

    0

);



$pdf->MultiCell(

    0,

    8,

    utf8_decode(

        strip_tags($offre['description'])

    )

);





$pdf->Ln(10);



$pdf->SetFont(

    'Arial',

    'I',

    10

);



$pdf->Cell(

    0,

    10,

    utf8_decode(

    "Document généré le ".date("d/m/Y à H:i")

    ),

    0,

    1

);



$nom = "Offre_SIMAN_JOB_".$offre['id'].".pdf";



$pdf->Output(

    "D",

    $nom

);


?>