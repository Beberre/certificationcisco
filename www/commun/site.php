<?php
$htmlstart = "<!DOCTYPE html>";
$htmlstart .= "<html>";


$head = "<head>";
$head .= "<meta charset=\"utf-8\" />";

$head .= "<link rel=\"stylesheet\" href=\"../css/bootstrap.min.css\" />";

$head .= "<link rel=\"stylesheet\" href=\"../css/styles.css\" />";
$head .= "<title>$titre</title>";
$head .= "</head>";

$bodystart = "<body>" ;

$header = "<header>";        
$header .= "<img class=\"banner\" src=\"../images/banner.jpg\" alt=\"banner\" title=\"bannière du site de certification CISCO CCNA\"/>";
if ($titre == "Cisco : Classement"){	
		$header .= "<script src=\"../js/Chart.min.js\"></script>";
		$header .= "<script src=\"../js/utils.js\"></script>";
		$header .= "<script src=\"../js/analyser.js\"></script>";
}
$header .= "</header>";

$nav = "<nav>";
$nav .= "<ul class=\"navigation\">" ;
$nav .="<li><a href=\"../index.php\">Accueil </a></li>";

if ($titre == "Cisco Certification"){	
	$nav .="<li><a href=\"../certification/etape5.php\">Nouveau test </a></li>";
}
if ($titre == "Accueil : Cisco Certification"){	
	$nav .="<li><a href=\"../certification/etape5.php\">Certification </a></li>";
	$nav .="<li><a href=\"../classement/classement.php\">Classement </a></li>";
	
	$nav .="<li>";
	$nav .="<form action=\"index.php\" method=\"POST\"> ";
	$nav .="<input class=rechercher name=recherche type=text>";			
	$nav .="<input class=rechercher type=submit value=rechercher >";						
	$nav .="</form>" ;			
	$nav .= "</li>";					
}

$nav .="</ul>";
$nav .= "</nav>";

$sectionstart ="<section>";
$sectionend ="</section>";


	

$footer ="<footer>";
$footer .="<a href=\"contact/contact.php\" >Contactez moi</a>";        
$footer .="<footer>";

$bodystart = "</body>" ;
$htmlend = "</html>" ;
?>
