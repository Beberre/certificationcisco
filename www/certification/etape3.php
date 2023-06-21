<?php
$titre = "Cisco Certification";
require "../commun/site.php" ;

$idetudiant =3;						// 	Numéro Id de l'étudiant. Cet ID est utilisé pour sauvegarder les résultats des QCM
$nbrquestions=5;					//	Nombre de question que composera le QCM
$localhost = "localhost";			//	
$login = "root";		
$pwd= "";
$bdname = "certificationcisco";
$port = "3308";

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;
echo $sectionstart;
// Création des QCM
$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
$query =" SELECT ";
$query .=" 	target.id, ";
$query .="     target.question, ";
$query .="     R.id, ";
$query .="     R.reponse ";
$query .=" FROM ";
$query .=" 	qcmclass target INNER JOIN reponses R ON target.id = R.idquestion ";
$query .=" ORDER BY target.id, R.id ";

if ($result = $connection->query($query)){
	$idQuetion_pre = "";
	$icptQuestion = 0;

	echo "<form method=\"GET\" action=\"etape2.php\">" ;
	while($row=$result->fetch_row())
	{
		if ($idQuetion_pre <> $row[0]) {
			$icptQuestion ++ ;
			if ($icptQuestion >5)
				break;

			echo ($idQuetion_pre <> "") ? "</p>" : "" ;
			echo "<p>";
			echo  "Q" . $row[0] ." - " .  $row[1] .	"</br>";
			$idQuetion_pre =$row[0];

		}
		echo "<label> <input type=\"checkbox\" name=\"$row[0]\" value=\"$row[2]\"\"> " .  $row[3] . "</label> </br>";

	}
	echo "<input type=\"submit\" value=\"valider\" >" ;
	echo "</form>" ;
}
$connection->close();
echo $sectionend;
echo $footer;
echo $bodystart;
echo $htmlend;

?>
