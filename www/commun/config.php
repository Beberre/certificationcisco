<?php

// Fichier de configuraiton 
//-----------------------------------------------------------------
// JFM 	2020-05-06		Création du fichier	
$idetudiant =3;						// 	Numéro Id de l'étudiant. Cet ID est utilisé pour sauvegarder les résultats des QCM
if (file_exists(dirname(__FILE__) ."/password"))
{
	if($file = fopen (dirname(__FILE__) . "/password","rt"))
	{
		fscanf($file,"%d;%s",$idetudiant, $login);
		fclose($file);
	}	
}
$nbrquestions=5;					//	Nombre de question que composera le QCM
$localhost = "localhost";			//	
$login = "root";		
$pwd= "";
$bdname = "certificationcisco";
$port = "3306";

?>