<?php
$titre = "Cisco Certification";
require "../commun/site.php" ;
require "../commun/config.php";

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;
echo $sectionstart;

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

	while($row=$result->fetch_row())
	{
		if ($idQuetion_pre <> $row[0]) {
			$icptQuestion ++ ;
			if ($icptQuestion >5)
				break;

			echo "</br>";
			echo  $row[0] ." - " .  $row[1] ."</br>";
			$idQuetion_pre =$row[0];

		}
		echo $row[2] . " - " .$row[3] . "</br>";

	}
}
$connection->close();	

echo $sectionend;
echo $footer;
echo $bodystart;
echo $htmlend;

?>
