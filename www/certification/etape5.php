<?php
$titre = "Cisco Certification";
require "../commun/site.php" ;
require "../commun/config.php" ;
require "../commun/certification.php" ;

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;

echo $sectionstart;
	if(empty($_GET)) // sans résultat etudiant...
		fctAffichezQCM();
	else
		fctCorrigezQCM();
echo $sectionend;

echo $footer;
echo $bodystart;
echo $htmlend;

?>
