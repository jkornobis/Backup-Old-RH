<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mon Réalisé - Module Personnel";
require_once('menus.php');

echo $doctype.$menuprincipale.$menurealise;

echo '<div id="content">
<h2>Réalisé: <a href="monrealise.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="monrealise.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'">'.$flsuivant.'</a> (!Seul les Heures Travaillées sont Réelles !)</h2>
<table style="width:750px;"><tr><th style="width:30px;">Jour</th><th>H Travailllés</th><th>Congés Payés</th><th>Congés Exceptionnel</th><th>Maladie</th><th>Fériés</th><th>RTT</th><th>RC</th></tr>
';

for( $i = 1; $i <= 31; $i ++){
	$resultats=$connexion->query('
	SELECT *
	FROM `Event`, `EventCategory1`, `UserObm`
	WHERE (`userobm_id` = "'.$utilisateur.'" OR `userobm_login` = "admin")
	AND `event_usercreate` = `userobm_id`
	AND `event_category1_id` = `eventcategory1_id`
	AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-'.$i.'"
	AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-'.($i+1).'"
	');
	$timestamp[$i] = mktime (0, 0, 0, $moisactuel, $i, $anneeactuel);

	$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
	while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
	{
		if($ligne->eventcategory1_code < 900 || $ligne->eventcategory1_code == 907 || $ligne->eventcategory1_code == 908){
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalmoishtr = $totalmoishtr + ($ligne->event_duration/3600);
		}
		if($ligne->eventcategory1_code == 999){
			$totalferie = $totalferie + ($ligne->temps_hpj/5);
			$totalmoisferie = $totalmoisferie + ($ligne->temps_hpj/5);
		}
		if($ligne->eventcategory1_code == 901){
			if(($ligne->event_duration/3600) > ($ligne->temps_hpj/5)/2){
				$totalcong = $totalcong + (($ligne->temps_hpj/5));		
				$totalmoiscong = $totalmoiscong + (($ligne->temps_hpj/5));
			}else{
				if( ($ligne->event_duration/3600) <= ($ligne->temps_hpj/5)/2 ){
					$totalcong = $totalcong + (($ligne->temps_hpj/5)/2);	
					$totalmoiscong = $totalmoiscong + (($ligne->temps_hpj/5)/2);
				}else{
					$totalcong = $totalcong + ($ligne->temps_hpj/5);
					$totalmoiscong = $totalmoiscong + (($ligne->temps_hpj/5));
				}
			}
		}
		if($ligne->eventcategory1_code == 902){
			if(($ligne->event_duration/3600) > ($ligne->temps_hpj/5)/2){
				$totalexcp = $totalexcp + (($ligne->temps_hpj/5));		
				$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5));
			}else{
				if( ($ligne->event_duration/3600) <= ($ligne->temps_hpj/5)/2 ){
					$totalexcp = $totalexcp + (($ligne->temps_hpj/5)/2);	
					$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5)/2);
				}else{
					$totalexcp = $totalexcp + ($ligne->temps_hpj/5);
					$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5));
				}
			}
		}
		if($ligne->eventcategory1_code == 903){
			if(($ligne->event_duration/3600) > ($ligne->temps_hpj/5)/2){
				$totalexcp = $totalexcp + (($ligne->temps_hpj/5));		
				$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5));
			}else{
				if( ($ligne->event_duration/3600) <= ($ligne->temps_hpj/5)/2 ){
					$totalexcp = $totalexcp + (($ligne->temps_hpj/5)/2);	
					$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5)/2);
				}else{
					$totalexcp = $totalexcp + ($ligne->temps_hpj/5);
					$totalmoisexcp = $totalmoisexcp + (($ligne->temps_hpj/5));
				}
			}
		}
		if($ligne->eventcategory1_code == 904){
			$totalrtt = $totalrtt + ($ligne->event_duration/3600);		
			$totalmoisrtt = $totalmoisrtt + ($ligne->event_duration/3600);
			/*if(($ligne->event_duration/3600) > ($ligne->temps_hpj/5)/2){
				$totalrtt = $totalrtt + (($ligne->temps_hpj/5));		
				$totalmoisrtt = $totalmoisrtt + (($ligne->temps_hpj/5));
			}else{
				if( ($ligne->event_duration/3600) <= ($ligne->temps_hpj/5)/2 ){
					$totalrtt = $totalrtt + (($ligne->temps_hpj/5)/2);	
					$totalmoisrtt = $totalmoisrtt + (($ligne->temps_hpj/5)/2);
				}else{
					$totalrtt = $totalrtt + ($ligne->temps_hpj/5);
					$totalmoisrtt = $totalmoisrtt + (($ligne->temps_hpj/5));
				}
			}*/
		}
		if($ligne->eventcategory1_code == 905){
			$totalrc = $totalrc  + ($ligne->event_duration/3600);
			$totalmoisrc = $totalmoisrc + ($ligne->event_duration/3600);
		}
		if($ligne->eventcategory1_code == 906){
			if(($ligne->event_duration/3600) > ($ligne->temps_hpj/5)/2){
				$totalmal = $totalmal + (($ligne->temps_hpj/5));		
				$totalmoismal = $totalmoismal + (($ligne->temps_hpj/5));
			}else{
				if( ($ligne->event_duration/3600) <= ($ligne->temps_hpj/5)/2 ){
					$totalmal = $totalmal + (($ligne->temps_hpj/5)/2);	
					$totalmoismal = $totalmoismal + (($ligne->temps_hpj/5)/2);
				}else{
					$totalmal = $totalmal + ($ligne->temps_hpj/5);
					$totalmoismal = $totalmoismal + (($ligne->temps_hpj/5));
				}
			}
		}
	}
	if(date("l" , $timestamp[$i]) == 'Sunday' || date("l", $timestamp[$i]) == 'Saturday'){
		if($i < 10){$i = '0'.$i;}
		echo '<tr style="background-color:#CCC;""><td style="background-color:lightblue;text-align:center;">'.$i.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	}else{
		if(isset($totalglobal) || isset($totalau) || isset($totalcong) || isset($totalmal) || isset($totalexcp) || isset($totalferie) || isset($totalrtt) || isset($totalrc)){
		if($i < 10){$i = '0'.$i;}
		echo '<tr onClick="window.location=\'monrealisecontrole.php?uti='.$utilisateur.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'&chjour='.$i.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'FFF\';"><td style="background-color:lightblue;width:20px;text-align:center;">'.$i.'</td><td>'.$totalglobal.'</td><td>'.$totalcong.'</td><td>'.$totalexcp.'</td><td>'.$totalmal.'</td><td>'.$totalferie.'</td><td>'.$totalrtt.'</td><td>'.$totalrc.'</td></tr>';
		}else{
			if($i < 10){$i = '0'.$i;}
			echo '<tr onClick="window.location=\'monrealisecontrole.php?uti='.$utilisateur.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'&chjour='.$i.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';"onMouseOut="this.style.backgroundColor=\'FFF\';"><td style="background-color:lightblue;width:20px;text-align:center;">'.$i.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
		}
	}
	$totalglobal = $totalcong = $totalau = $totalexcp = $totalrc = $totalrtt = $totalmal = $totalferie = NULL;
}
$resultats->closeCursor(); // on ferme le curseur des résultats
echo '<tr><th>Totaux</td><th>'.$totalmoishtr.'</th><th style="font-size:16px;">'.$totalmoiscong.'</th><th>'.$totalmoisexcp.'</th><th></th><th></th><th></th><th></th></tr></table><br/></div></body></html>';
?>
