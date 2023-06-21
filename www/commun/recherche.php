<?php
function fctRecherArticle($Mots) 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	
?>
