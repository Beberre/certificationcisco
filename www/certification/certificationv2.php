<?php

$idetudiant =3;
$localhost = "localhost";
$login = "root";
$pwd= "";
$bdname = "certificationcisco";
$port = "3308";

$titre = "Cisco Certification";

$htmlstart = "<!DOCTYPE html>";
$htmlstart .= "<html>";

$head = "<head>";
$head .= "<meta charset=\"utf-8\" />";
$head .= "<link rel=\"stylesheet\" href=\"../styles.css\" />";
$head .= "<title>$titre</title>";
$head .= "</head>";

$bodystart = "<body>" ;

$header = "<header>";        
$header .= "<img class=\"banner\" src=\"../images/banner.jpg\" alt=\"banner\" title=\"bannière du site de certification CISCO CCNA\"/>";
$header .= "</header>";


$nav = "<nav>";
$nav .= "<ul class=\"navigation\">" ;
$nav .="<li><a href=\"../index.html\">Accueil </a></li>";
$nav .="</ul>";
$nav .= "</nav>";

$sectionstart ="<section>";
$sectionend ="</section>";

$footer ="<footer>";
$footer .="Contact, Crédit";        
$footer .="<footer>";

$bodystart = "</body>" ;
$htmlend = "</html>" ;

function fctMiseAJourEvaluer($idetudiant, $tabQ, $note)
{
	global $localhost,$login,$pwd,$bdname,$port ;
	$connexionMaj = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("eruu");
	
	for ($iNumQuestion=0 ; $iNumQuestion<count($tabQ); $iNumQuestion++)
	{
		// vérification que l'enregistrement n'existe pas dans la table Evaluer
		$query ="SELECT "; 
		$query.="    E.idetudiant," ; 
		$query.="    E.idquestion, " ; 
		$query.="    E.note, " ; 
		$query.="    E.dtobtention " ; 
		$query.="FROM " ; 
		$query.="	evaluer E " ; 
		$query.="WHERE ";
		$query.="    E.idetudiant=$idetudiant ";
		$query.="AND E.idquestion=$tabQ[$iNumQuestion] ";

			if($result = $connexionMaj->query($query))
			{
				if($row = $result->fetch_row()){

					// L'enregistrement existe, on réaliser un update
					$query ="UPDATE evaluer set "; 
					$query.="    note = $note, " ; 
					$query.="    dtobtention =  date_format(now(),\"%Y-%m-%d\") " ; 
					$query.="WHERE ";
					$query.="    idetudiant=$idetudiant ";
					$query.="AND idquestion=$tabQ[$iNumQuestion] ";		


				}else {
					// L'enregistrement n'existe pas, on réaliser un insert
					$query ="INSERT INTO evaluer (idetudiant, idquestion, note, dtobtention) values "; 
					$query.="($idetudiant, $tabQ[$iNumQuestion], $note, date_format(now(),\"%Y-%m-%d\") ); " ; 
				}
				//echo "<br>" . $query ;
				$result = $connexionMaj->query($query);
			}
	}
	$connexionMaj->close();	
}
echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;
echo $sectionstart;

$connexion = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("erreur connexion");
if (!empty($_POST))
{	$QuestionCorrecte = 0;
	$tabQok = array();
	$tabQko = array();
	foreach ($_POST as $keyQuestion => $idRep)
    {
    	//echo $keyQuestion . "=> " . $idRep . "</br>";
		$query ="SELECT "; 
		$query.="    Q.id," ; 
		$query.="    Q.question, " ; 
		$query.="    R.id, " ; 
		$query.="    R.reponse, " ; 
		$query.="    R.solution, " ; 
		$query.="    IFNULL(Q.explication,\"No explication!\") " ; 
		$query.="FROM " ; 
		$query.="	questions Q INNER JOIN reponses R on Q.id = R.idquestion " ; 
		$query.="WHERE ";
		$query.="Q.id=$keyQuestion ";
		$query.="ORDER BY Q.id, R.id";    
		echo "<pr>";
		if($result = $connexion->query($query))
		{
			$idQuestion_prev="";
			$idReponse_prev ="";
			

			while($row = $result->fetch_row())
			{
				$idQuestion = $row[0];
				$TextQuestion = $row[1];
				$idReponse = $row[2];
				$TextReponse = $row[3];
				$Solution = $row[4];
				$explication = $row[5];

				if($idQuestion_prev<>$idQuestion )
				{	
					echo $TextQuestion ."</br>";
					$idQuestion_prev = $idQuestion ;
				}
				if($idReponse_prev<>$idReponse)
				{
					if ($idRep==$idReponse )
						if ($Solution=="O" )
						{
							echo "Ok" . $idReponse . "-" ."<label>$TextReponse</label></br>" ;	
							$QuestionCorrecte++;
							$tabQok[] = $idQuestion;
						} else {
							echo "Ko" . $idReponse . "-" ."<label>$TextReponse</label></br>" ;
							echo "$explication</br>" ;
							$tabQko[]=$idQuestion;

						}
					$idReponse_prev = $idReponse ;
				}
			}	
	    }
		echo "</pr>";
		echo "</br>";
	}
	echo "</br>";
	echo " Note : $QuestionCorrecte / 10" ;
	fctMiseAJourEvaluer($idetudiant, $tabQok, 1);
	fctMiseAJourEvaluer($idetudiant, $tabQko, 0);
}
else
{
	$query ="SELECT "; 
	$query.="    Q.id," ; 
	$query.="    Q.question, " ; 
	$query.="    R.id, " ; 
	$query.="    R.reponse " ; 
	$query.="FROM " ; 
	$query.="	questions Q INNER JOIN reponses R on Q.id = R.idquestion " ; 
	$query.="ORDER BY Q.id, R.id";

	if($result = $connexion->query($query))
	{
		$idQuestion_prev="";
		$idReponse_prev ="";
		$NbrQuestion = 0;
		echo "<form method=\"post\" action=\"certification.php\">";
		while($row = $result->fetch_row())
		{

			$idQuestion = $row[0];
			$TextQuestion = $row[1];
			$idReponse = $row[2];
			$TextReponse = $row[3];

			if($idQuestion_prev<>$idQuestion )
			{	
				$NbrQuestion++;
				if ($NbrQuestion>10)
					break;
				echo ($idQuestion_prev=="") ? "" : "</pr></br>" ;
				echo "<pr>";
				echo $NbrQuestion . "-" .$TextQuestion ."</br>";	
				$idQuestion_prev = $idQuestion ;

			}
			if($idReponse_prev<>$idReponse)
			{
				echo $idReponse . "-" ."<label><input type=\"checkbox\" name=\"$idQuestion\" value=\"$idReponse\" />$TextReponse<label></br>" ;	
				$idQuestion_prev = $idQuestion ;
			}

		}
		echo "<input type=\"submit\" value=\"Valider\" />";
		echo "</form>";
	}
}
$connexion->close();
echo $sectionend;
echo $footer;
echo $bodystart;
echo $htmlend;

?>
