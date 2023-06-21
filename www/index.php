<?php
$titre = "Accueil : Cisco Certification";
require "commun/site.php" ;
require "commun/config.php" ;
require "commun/index.php" ;

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;

echo $sectionstart;

	if (empty($_POST))
		$recherche = "";
	else {
	 	$recherche = $_POST["recherche"];
	 } 
	fctAfficherArticles($recherche);
echo $sectionend;

echo $footer;
echo $bodystart;
echo $htmlend;

?>