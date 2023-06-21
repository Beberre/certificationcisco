<?php
$titre = "Cisco Certification";


$htmlstart = "<!DOCTYPE html>";
$htmlstart .= "<html>";

$head = "<head>";
$head .= "<meta charset=\"utf-8\" />";
$head .= "<link rel=\"stylesheet\" href=\"../styles.css\" />";
$head .= "<title>$titre</title>";
$head .= "</head>";

$bodystart = "<body>" ;
        
$header = "<header>";        
$header .= "<img class=\"banner\" src=\"../images/banner.jpg\" alt=\"banner\" title=\"bannière du site de certification CISCO CCNA\"/>";
$header .= "</header>";

$nav = "<nav>";
$nav .= "<ul class=\"navigation\">" ;
$nav .="<li><a href=\"../index.html\">Accueil </a></li>";
$nav .="<li><a href=\"../certification/etape1.php\">Nouveau test </a></li>";
$nav .="</ul>";
$nav .= "</nav>";

$sectionstart ="<section>";
$sectionend ="</section>";

$footer ="<footer>";
$footer .="Contact, Crédit"; 
$footer .="</footer>";

$bodyend = "</body>" ;
$htmlend = "</html>" ;


echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;

echo $nav;

echo $sectionstart;
echo $sectionend;

echo $footer;
echo $bodyend;
echo $htmlend;


?>
