<?php
function fctGstArticle($param_url) 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	if (isset($param_url["id"]))	
		$id = $param_url["id"] ;	
	$action="";
	if (isset($param_url["action"]))	
		$action = $param_url["action"] ;	

	switch ($action)
	{
		case "d":
			$query  = "delete from commentaires where idarticle = $id;  " ;
			$query .= "delete from articles where id = $id;  " ;
			$result = $connection->query($query) ;
			break;
		default :

	}

	$idtabMem = 0;
	$query  = "SELECT " ;
	$query .= "    A.id, " ;
	$query .= "    A.titre,  " ;
	$query .= "    A.description " ;
	$query .= "FROM " ;
	$query .= "    articles A " ;

	$result = $connection->query($query) ;
	if ($row=$result->fetch_row())
	{

		$result = $connection->query($query) ;
		echo "<ul>" ;	

		while($row=$result->fetch_row())
			echo "<li> " . $row[1] . " <a href=\"admin.php?id=$row[0]&action=d\">supprimer </a></li>" ;
		echo "</ul>" ;

	}
	else
	{
		echo "erreur article." ;
	}
	$connection->close();
}
