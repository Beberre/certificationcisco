<?php
$titre = "Cisco : Classement";
require "../commun/config.php";
require "../commun/site.php" ;
require "../commun/classement.php" ;

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;

	fctAfficherGraphiqueRadar();
	fctAfficherDonut();

echo $footer;
echo $bodystart;
echo $htmlend;


?>


  
   



