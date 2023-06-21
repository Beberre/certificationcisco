<?php
function fctAfficherGraphiqueRadar() 
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connexion = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("erreur connexion");
	$query= "SELECT prenom, nom, IFNULL(C.1,0),IFNULL(C.2,0),IFNULL(C.3,0),IFNULL(C.4,0),IFNULL(C.5,0),IFNULL(C.6,0),IFNULL(C.7,0),IFNULL(C.8,0),IFNULL(C.9,0),IFNULL(C.10,0),IFNULL(C.11,0) FROM `classement` C WHERE C.idetudiant = $idetudiant";
	if($result = $connexion->query($query))
	{
		$row = $result->fetch_row();
		$data_chart_0 = "";
		$nometudiant = $row[0] . " " . $row[1];
		for ($icptcol=2; $icptcol<12;$icptcol++)
			$data_chart_0 .= $row[$icptcol]  . ",";
		$data_chart_0 .= $row[$icptcol];
	}

	$connexion->close();

	echo "<section class=\"content1\"> " ;
	echo "			<canvas id=\"chart-0\"></canvas> " ;
	echo "			<script> " ;
	echo "			var presets = window.chartColors; ";
	echo "			var utils = Samples.utils; ";
	echo "			var inputs = { ";
	echo "				min: 0, ";
	echo "				max: 100, ";
	echo "				count: 11, ";
	echo "				decimals: 2, ";
	echo "				continuity: 1 ";
	echo "			}; ";
	echo "			var data = { ";
	echo "				labels: [\"chapitre 1\",\"chapitre 2\",\"chapitre 3\",\"chapitre 4\",\"chapitre 5\",\"chapitre 6\",\"chapitre 7\",\"chapitre 8\",\"chapitre 9\",\"chapitre 10\",\"chapitre 11\",], ";
	echo "				datasets: [{ ";
	echo "					backgroundColor: utils.transparentize(presets.red), ";
	echo "					borderColor: presets.red, ";
	echo "					data: [$data_chart_0], ";
	echo "					label: '$nometudiant' ";
	echo "				}] ";
	echo "			}; ";
	echo "			var options_1 = { ";
	echo "				maintainAspectRatio: true, ";
	echo "				spanGaps: false, ";
	echo "				elements: { ";
	echo "					line: { ";
	echo "						tension: 0.000001 ";
	echo "					} ";
	echo "				}, ";
	echo "				plugins: { ";
	echo "					filler: { ";
	echo "						propagate: false ";
	echo "					}, ";
	echo "					'samples-filler-analyser': { ";
	echo "						target: 'chart-analyser' ";
	echo "					} ";
	echo "				} ";
	echo "			}; ";
	echo "			var chart = new Chart('chart-0', { ";
	echo "				type: 'radar', ";
	echo "				data: data, ";
	echo "				options: options_1 ";
	echo "			}); ";
	echo "		</script>			 ";
    echo "    </section>	 ";
}

function fctAfficherDonut()
{
	global $idetudiant, $localhost,$login,$pwd,$bdname,$port ;
	$connexion = new mysqli($localhost,$login,$pwd,$bdname,$port) or die("erreur connexion");

	// Phasemem mémorisation id(0,1) // Phasemem mémorisation (2,3,4) // Phasemem perfectionnement (5,6,7) 
	$query= "SELECT phasemem, count(*)  FROM evaluer E WHERE E.idetudiant = $idetudiant group by phasemem order by phasemem ";
	$dataphaseme=[0,0,0];
	$data_chart_1="";
	if($result = $connexion->query($query))
	{
		while($row = $result->fetch_row())
		{
			switch ($row[0])
			{
				case 0:
				case 1:
					$dataphaseme[0]+=$row[1];
					break;
				case 2:
				case 3:
				case 4:
					$dataphaseme[1]+=$row[1];
					break;
				case 5:
				case 6:
				case 7:
					$dataphaseme[2]+=$row[1];
					break;			
			}
		}
		$data_chart_1 = implode(",", $dataphaseme);	
	}
	$connexion->close();	
     
    echo"   <section class=\"content2\">	";
	echo"				<canvas id=\"chart-1\"></canvas>	";

    echo"   	<script>	";
	echo"		        data = {	";
	echo"		    datasets: [{	";
	echo"		        data: [$data_chart_1],	";
	echo"		        backgroundColor:[\"rgb(255, 0, 0)\",\"rgb(0, 0, 0)\",\"rgb(0, 0, 255)\"]	";
	echo"		    }],	";

			    // These labels appear in the legend and in the tooltips when hovering different arcs
	echo"		    labels: [	";
	echo"		        'Phase de mémorisation',	";
	echo"		        'Phase Entretient de la mémoire',	";
	echo"		        'Phase de Perfectionnement'	";
	echo"		    ]	";
	echo"		};	";
	echo"		var options_2 = {	";
	echo"			maintainAspectRatio: true,	";
	echo"			spanGaps: false,	";
	echo"			elements: {	";
	echo"				line: {	";
	echo"					tension: 0.000001	";
	echo"				}	";
	echo"			},	";
	echo"			plugins: {	";
	echo"				filler: {	";
	echo"					propagate: false	";
	echo"				},	";
	echo"				'samples-filler-analyser': {	";
	echo"					target: 'chart-analyser'	";
	echo"				}	";
	echo"			},	";
	echo"			circumference : Math.PI,	";
	echo"			rotation: -Math.PI	";
	echo"		};	";

	echo"		var myDoughnutChart = new Chart('chart-1', {	";
	echo"		    type: 'doughnut',	";
	echo"		    data: data,			    	";
	echo"		    options: options_2	";
	echo"		});	";
	echo"		</script>     	";  
	echo"		</section> 	"; 
}
