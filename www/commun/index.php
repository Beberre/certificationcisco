<?php
function fctAfficherArticles($recherche) 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	$cptArticle = 0 ;
	$idtabMem = 0;
	$query  = "SELECT " ;
	$query .= "    A.id, " ;
	$query .= "    A.titre,  " ;
	$query .= "    A.description " ;
	$query .= "FROM " ;
	$query .= "    articles A " ;
	
	if ($recherche != "")
	{
		echo "<label> Recherche des articles contenant : " .$recherche. "</br>";
		$query .= "WHERE " ;
		$query .= "    A.description like \"%" . $recherche . "%\" " ;
	}
	$query .= "ORDER BY " ;
	$query .= "    A.id  " ;
	$query .= " LIMIT 3 ";

	if ($result = $connection->query($query))
	{
		echo "<ul class=\"articles\">" ;
		while($row=$result->fetch_row())
		{
			$cptArticle++;
			echo "<li>" ;
			echo "<a href=\"articles/article.php?ref=$row[0]\" > ";
			echo "<h2> $row[1] </h2></a>";
			echo "<p> $row[2] </p>" ;
			echo "<li>" ;			
		}
		echo "</ul>" ;
	}
	if ($recherche != "") 
		echo "Vous avez $cptArticle article(s) concernés.";
	$connection->close();
}
