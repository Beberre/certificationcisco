<?php
function fctLireArticle($param_url) 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	
	$id = $param_url["ref"] ;
	if (isset($param_url["commentaire"]))
	{
		$commentaire = $param_url["commentaire"] ;
		$query  = "INSERT INTO commentaires (idarticle, commentaire) VALUES  " ;
		$query .= "($id,\"$commentaire\" );" ;	
		$result = $connection->query($query) ;
	}
	$cptArticle = 0 ;
	$idtabMem = 0;
	$query  = "SELECT " ;
	$query .= "    A.id, " ;
	$query .= "    A.titre,  " ;
	$query .= "    A.description " ;
	$query .= "FROM " ;
	$query .= "    articles A " ;
	$query .= "WHERE " ;
	$query .= " A.id = $id " ;

	$result = $connection->query($query) ;
	if ($row=$result->fetch_row())
	{
		echo "article : $row[1] </br> $row[2] </br>" ;	
		
		$query  = "SELECT " ;
		$query .= "    C.id, " ;
		$query .= "    C.commentaire " ;
		$query .= "FROM " ;
		$query .= "    commentaires C "  ;
		$query .= "WHERE " ;
		$query .= " C.idarticle = $id " ;
		$query .= "ORDER BY " ;
		$query .= " C.id " ;	

		$result = $connection->query($query) ;
		echo "<ul>" ;	
		while($row=$result->fetch_row())
		{
			echo "<li>" ;
			echo " $row[1] " ;
			echo "</li>" ;			
		}
		echo "</ul>" ;
		echo "<form action=\"article.php\" method=\"GET\">" ;
		echo "commentaire : <input type=text name=commentaire size=50>" ;
		echo "<input type=hidden name=ref value=$id>" ;
		echo "<input type=submit />" ;
		echo " </form>" ;

	}
	else
	{
		echo "erreur article." ;
	}
	$connection->close();
}
