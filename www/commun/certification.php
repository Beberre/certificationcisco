<?php
function fctMiseAJourEvaluer($tabQ, $Etat) 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	// Phasemem mémorisation id(0,1) // Phasemem mémorisation (2,3,4) // Phasemem perfectionnement (5,6,7) 
	$tabMem=[0,3,7,10,20,30,60,120];
	// Création des QCM
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	// si une évaluation (select) existe dans la table evaluer, alors je (update) mets à jours la donnée, sinon l'(insert).
	for($icpt = 0; $icpt<count($tabQ); $icpt ++)
	{
		$idtabMem = 0;
		$query  = "SELECT " ;
		$query .= "    E.idetudiant, " ;
		$query .= "    E.idquestion,  " ;
		$query .= "    E.phasemem  " ;
		$query .= "FROM " ;
		$query .= "    evaluer E " ;
		$query .= "WHERE " ;
		$query .= "    idetudiant = $idetudiant  " ;
		$query .= "AND idquestion = $tabQ[$icpt]  " ;			
		//echo $query . "</br>"; 
		if ($result = $connection->query($query))
		{

			if($row=$result->fetch_row())
			{
				$idtabMem = $row[2];
				if ($Etat == 1)
				{
					$idtabMem ++;
					if ($idtabMem > 7)
						$idtabMem =7;
					$OffsetJour = $tabMem[$idtabMem] ;
				} else
				{
					$idtabMem --;
					if ($idtabMem < 0)
						$idtabMem =0 ;
					$OffsetJour = $tabMem[$idtabMem] ;					
				}

				$query  = "UPDATE evaluer SET " ;
				$query .= " note =$Etat, " ;
				$query .= " dtobtention = DATE_ADD(DATE_FORMAT(now(),\"%Y-%m-%d\"), INTERVAL $OffsetJour DAY),  " ;	
				$query .= " phasemem =$idtabMem, " ;
				$query .= " dtexam = DATE_FORMAT(now(),\"%Y-%m-%d\") " ;
				$query .= "WHERE " ;
				$query .= "    idetudiant = $idetudiant  " ;
				$query .= "AND idquestion = $tabQ[$icpt]  " ;
				$query .= "AND dtexam <> DATE_FORMAT(now(),\"%Y-%m-%d\")  " ;

				$result = $connection->query($query);				
				//echo $query ;
			}else {
				if ($Etat == 1)
				{
					$idtabMem ++;
					$OffsetJour = $tabMem[$idtabMem] ;
				} else
				{
					$idtabMem =0 ;
					$OffsetJour = $tabMem[$idtabMem] ;					
				}
		
				$query  = "INSERT INTO evaluer (idetudiant, idquestion, note, dtobtention, phasemem, dtexam) VALUES " ;
				$query .= "( " ;
				$query .= "   $idetudiant, " ;
				$query .= "   $tabQ[$icpt], " ;
				$query .= "   $Etat,  " ;
				$query .= "   DATE_ADD(DATE_FORMAT(now(),\"%Y-%m-%d\"), INTERVAL $OffsetJour DAY) ," ;
				$query .= "   $idtabMem," ;
				$query .= "   DATE_FORMAT(now(),\"%Y-%m-%d\")" ;
				$query .= ") ";
				//echo $query . "</br>";

				$result = $connection->query($query);
			}

		}
	}
	$connection->close();
}
function fctCorrigezQCM() 
{
	
	global $localhost,$login,$pwd,$bdname,$port ;
	// Création des QCM
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;

	$tabRepOk = array();
	$tabRepKo = array();
	$NumBonneRep=0 ;
	foreach ($_GET as $idquestion => $idreponse){
		$query  = "SELECT " ;
		$query .= "    R.idquestion, " ;
		$query .= "    Q.question, " ;
		$query .= "    R.id, " ;
		$query .= "    R.reponse, " ;
		$query .= "    R.solution, " ;
		$query .= "    IFNULL(Q.explication,\"Pas d'explication !\") " ;
		$query .= "FROM " ;
		$query .= "    reponses R INNER JOIN questions Q on R.idquestion = Q.id " ;
		$query .= "WHERE " ;
		$query .= "    R.idquestion=$idquestion " ;
		$query .= "AND R.id=$idreponse ";	
		//echo 	$query  . "<br> " ;
		if ($result = $connection->query($query)){
			$row=$result->fetch_row();
			if($row[4]=="O"){
				$tabRepOk[] = $row[0];
				$NumBonneRep++;
				echo "<label class=\"question\"><a target=\"_blank\" href=\"http://www.google.fr/search?hl=fr&q=$row[1]\"> $row[1] </a></label></br>";
				echo "<label class=\"reponseOk\" > "  . $row[3] . "</label></br> <label class=\"reponseexplication\">" . $row[5] . "</label></br>" ;
			}else {
				$tabRepKo[] = $row[0];
				echo "<label class=\"question\"><a target=\"_blank\" href=\"http://www.google.fr/search?hl=fr&q=$row[1]\"> $row[1] </a></label></br>";
				echo "<label class=\"reponseKo\" > "  . $row[3] . "</label></br> <label class=\"reponseexplication\">" . $row[5] . "</label></br>" ;
				echo "</br>";
			}
		}
	}
	echo "<label class=\"note\" >$NumBonneRep/5<label>";
	$connection->close();

	fctMiseAJourEvaluer($tabRepOk,1) ;
	fctMiseAJourEvaluer($tabRepKo,0) ;

}


function fctAffichezQCM()
{
	global $idetudiant,$localhost,$login,$pwd,$bdname,$port ;
	// Création des QCM
	$connection = new mysqli($localhost,$login,$pwd,$bdname,$port) or die ("Erreur de connexion ! ") ;
	$query  =" SELECT ";
	$query .=" 	target.id \"idQuestion\", ";
	$query .="     target.question \"Question\", ";
	$query .="     R.id \"idReponse\", ";
	$query .="     R.reponse \"Reponse\", ";
	$query .="     0 \"Phasemem\"";
	$query .=" FROM ";
	$query .=" 	  qcmclass target INNER JOIN reponses R ON target.id = R.idquestion ";
	$query .=" 	  LEFT JOIN evaluer E ON target.id = E.idquestion AND E.idetudiant = $idetudiant ";
	$query .=" WHERE ";
	$query .=" 	  		IFNULL(E.note,0) = 0 ";
	$query .=" AND 	  	IFNULL(dtexam,\"1970-01-01\") <> DATE_FORMAT(now(),\"%Y-%m-%d\") ";	
	$query .="UNION " ;
	$query .=" SELECT ";
	$query .=" 	   target2.id \"idQuestion\", ";
	$query .="     target2.question \"Question\", ";
	$query .="     R2.id \"idReponse\", ";
	$query .="     R2.reponse \"Reponse\", ";
	$query .="     E.phasemem \"Phasemem\"";
	$query .=" FROM ";
	$query .=" 	  qcmclass target2 INNER JOIN reponses R2 ON target2.id = R2.idquestion ";
	$query .=" 	  LEFT JOIN evaluer E ON target2.id = E.idquestion AND E.idetudiant = $idetudiant ";
	$query .=" WHERE ";
	$query .=" 	  		IFNULL(E.note,0) = 1 ";
	$query .=" AND 	  	DATEDIFF(now(),dtobtention) >= 0 ";	
	$query .=" AND 	  	dtexam <> DATE_FORMAT(now(),\"%Y-%m-%d\") ";	
	$query .=" ORDER BY idQuestion, idReponse ";
	
	//echo $query ;
	if ($result = $connection->query($query)){
		$idQuetion_pre = "";
		$icptQuestion = 0;		
		echo "<form method=\"GET\" action=\"etape5.php\">" ;
		while($row=$result->fetch_row())
		{
			if ($idQuetion_pre <> $row[0]) {
				$icptQuestion ++ ;
				if ($icptQuestion >5)
					break;
				
				echo ($idQuetion_pre <> "") ? "</p>" : "" ;
				echo "<p>";
				echo  "<progress value=\"$row[4]\" max=\"7\"> $row[4] </progress></br> " ;			
				echo  "<label class=\"question\">Q $row[0] - $row[1]</label>";
				echo  "</br>" ;
				$idQuetion_pre =$row[0];

			}
			echo "<label> <input type=\"checkbox\" name=\"$row[0]\" value=\"$row[2]\"\"> " .  $row[3] . "</label> </br>";

		}
		echo "<input type=\"submit\" value=\"valider\" >" ;
		echo "</form>" ;
	}
	$connection->close();

}
function fctMiseAJourEvaluer2($idetudiant, $tabQ, $note)
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
function fctSectionCertification()
{
	global $idetudiant, $nbrquestions, $localhost,$login,$pwd,$bdname,$port ;

	if (!empty($_POST))
	{	
		$connexion = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("erreur connexion");
		$QuestionCorrecte = 0;
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
								echo "<label class=\"reponseok\">$TextReponse</label></br>" ;	
								$QuestionCorrecte++;
								$tabQok[] = $idQuestion;
							} else {
								echo "<label class=\"reponseko\">$TextReponse</label></br>" ;
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
		echo " <label class=\"note\">Note : $QuestionCorrecte /$nbrquestions</label>" ;
		fctMiseAJourEvaluer($idetudiant, $tabQok, 1);
		fctMiseAJourEvaluer($idetudiant, $tabQko, 0);
		$connexion->close();
	}
	else
	{
		$connexion = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("erreur connexion");
		$query ="SELECT "; 
		$query.="    Q.id," ; 
		$query.="    Q.question, " ; 
		$query.="    R.id, " ; 
		$query.="    R.reponse, " ; 
		$query.="    ifnull(E.note,0) note " ; 		
		$query.="FROM " ; 
		$query.="	questions Q INNER JOIN reponses R on Q.id = R.idquestion " ; 
		$query.="	INNER JOIN qcmclass Target on Target.id = Q.id " ;
		$query.="	LEFT JOIN  evaluer E on E.idetudiant = $idetudiant AND E.idquestion = Q.id " ; 
		$query.="WHERE";
		$query.="	note = 0 ";
		$query.="OR	note is NULL ";
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
					if ($NbrQuestion>$nbrquestions)
						break;
					echo ($idQuestion_prev=="") ? "" : "</pr></br>" ;
					echo "<pr>";
					echo "<label class=\"question\">Q$idQuestion-$TextQuestion</label></br>";	
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
		$connexion->close();
	}

}

?>
