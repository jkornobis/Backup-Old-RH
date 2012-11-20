<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "!!! TEST REALISÉ !!!";
require_once('menus.php');

echo $doctype.$menuprincipale.$menurealise;

if(isset($_GET['channee'])){$anneeactuel = $_GET['channee'];}


$tempshpj=$connexion->query('
				SELECT *
				FROM `UserObm`
				WHERE `userobm_id` = "'.$utilisateur.'"
;');
$tempshpj->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $tempshpj->fetch())
{
	$useridmem = $ligne->userobm_id;
	$moisnormal = ($ligne->temps_hpj/5);
	$compteurrtt =	$ligne->userobm_congesrrtnt/24;
	$hpj = ($ligne->temps_hpj/5);
	$contratdebut = $ligne->contrat_debut;
	$contratfin = $ligne->contrat_fin; 
	$compteurconges = $ligne->userobm_congesnormale;
	// $compteur_memoire = $ligne->compteur_memoire;
}
// if(isset($compteur_memoire)){$minitable='<table style="width:300px;"><tr><th>Solde année précédente:</th><td  style="text-align:center;width:60px;">'.$compteur_memoire.' h</td></tr></table><br/>';}

echo '<div id="content">
<h2>Réalisé de l\'année - '.$nomsession.' :<a href="monrealiseannuel.php?channee='.($anneeactuel-1).'">'.$flprecedent.'</a><span>'.$anneeactuel.'</span><a href="monrealiseannuel.php?channee='.($anneeactuel+1).'">'.$flsuivant.'</a>
<span style="font-size:16px;margin-left:320px;">'.date("d/m/Y H\Hi").'</span></h2>';
/*////////////////////////////////////////////////////////////////////////////
									1er Tableau
///////////////////////////////////////////////////////////////////////////*/
$resultat=$connexion->query('
	SELECT *
	FROM EventLink
	INNER JOIN Event ON event_id = eventlink_event_id
	INNER JOIN EventCategory1 ON event_category1_id = eventcategory1_id
	INNER JOIN UserEntity ON userentity_entity_id = eventlink_entity_id
	INNER JOIN UserObm ON userobm_id = userentity_user_id
	WHERE userentity_user_id = "'.$useridmem.'"
	AND event_date >= "'.$anneeactuel.'-01-01"
	AND event_date <= "'.$anneeactuel.'-12-31"
	ORDER BY event_date
;');
$resultat->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultat->fetch()){
	$catcode= $ligne->eventcategory1_code;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($ligne->event_date,"%d-%d-%d %d:%d:%d");
	if($catcode < "900"){
			$totaljour[$mois][$jour] = $totaljour[$mois][$jour] + ($ligne->event_duration/3600);
			$totalmois[$mois] = $totalmois[$mois] + ($ligne->event_duration/3600);
	}else{
		switch($catcode){
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////  																	COMPTEUR FIXE À LA JOURNNÉE       												          /////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case '901':
				$rh[$mois][$jour] = 'CP';
				$totalcp[$mois] = $totalcp[$mois]+1;
				
			break;
			case '902':
				$rh[$mois][$jour] = 'Cex';
				$totalcex[$mois] = $totalcex[$mois]+1;
			break;
			case '909':
				$rh[$mois][$jour] = 'Canc';
				$totalcanc[$mois] = $totalcanc[$mois]+1;
			break;
			case '910':
				$rh[$mois][$jour] = 'Cet';
				$totalcet[$mois] = $totalcet[$mois]+1;
			break;
				case '911':
				$rh[$mois][$jour] = 'A.T';
				$totalmal[$mois] = $totalmal[$mois]+1;
			break;
			case '999':
				$rh[$mois][$jour] = 'F';
				$totalferie[$mois] = $totalferie[$mois]+1;
			break;

//$rheventcp .= '<b>'.$ligne->event_date.' / '.$ligne->event_title.' / '.$catcode.' / '.($ligne->event_duration/3600).'H / '.$ligne->userobm_login.'</b><br/>';
//$rheventferie .= '<b>'.$ligne->event_date.' / '.$ligne->event_title.' / '.$catcode.' / '.($ligne->event_duration/3600).'H / '.$ligne->userobm_login.'</b><br/>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////  														COMPTEUR POTENTIELLEMENT SUR LA DEMI JOURNNÉE       						          /////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			case '903':
				$rh[$mois][$jour] = 'Cem';
				if(($ligne->event_duration/3600) <= ($hpj/2)){
					$totalcem[$mois] = ($totalcem[$mois]+0.5);
				}else{
					if(($ligne->event_duration/3600) <= 4 ){
						$warning .= '<p style="color:#A00;font-size:14px;font-weight:bold;"> ! Attention ! le Congés Enfant Malade pris le '.$jour.'/'.$mois.'/'.$annee.' dépasse votre demi-journée de travail, vous aurez donc '.(($hpj/2) - ($ligne->event_duration/3600)).' heures dans votre réalisé. -----> Veuillez Corriger. </p>';
					}else{
						$totalcem[$mois] = $totalcem[$mois]+1;
					}
				}
			break;
			case '904':
				$rh[$mois][$jour] = 'RTT';
				$totaljour[$mois][$jour] = $totaljour[$mois][$jour] + ($ligne->event_duration/3600);
				if(($ligne->event_duration/3600) <= ($hpj/2)){
					$totalrtt[$mois] = ($totalrtt[$mois]+ '0.5');
				}else{
					if(($ligne->event_duration/3600) <= 4 ){
						$warning .= '<p style="color:#A00;font-size:14px;font-weight:bold;"> ! Attention ! le RTT pris le '.$jour.'/'.$mois.'/'.$annee.' dépasse votre demi-journée de travail, vous aurez donc '.(($hpj/2) - ($ligne->event_duration/3600)).' heures dans votre réalisé. -----> Veuillez Corriger. </p>';
						$totalrtt[$mois] = ($totalrtt[$mois]+ '0.5');
					}else{
						$totalrtt[$mois] = $totalrtt[$mois]+1;
					}
				}
			break;
			case '905':
				$rh[$mois][$jour] = 'RC';
				$totaljour[$mois][$jour] = $totaljour[$mois][$jour] + ($ligne->event_duration/3600);
				$totalrc[$mois] = $totalrc[$mois] + ($ligne->event_duration/3600);			
			break;
			case '906':
				$rh[$mois][$jour] = 'Mal';
				if(($ligne->event_duration/3600) <= ($hpj/2)){
					$totalmal[$mois] = ($totalmal[$mois]+0.5);
				}else{
					if(($ligne->event_duration/3600) <= 4 ){
						$warning .= '<p style="color:#A00;font-size:14px;font-weight:bold;"> ! Attention ! le Congés Enfant Malade pris le '.$jour.'/'.$mois.'/'.$annee.' dépasse votre demi-journée de travail, vous aurez donc '.(($hpj/2) - ($ligne->event_duration/3600)).' heures dans votre réalisé. -----> Veuillez Corriger. </p>';
					}else{
						$totalmal[$mois] = $totalmal[$mois]+1;
					}
				}
			break;
		}
	}
}
echo '<table>
<tr>
	<th>M</th><th>01</th><th>02</th><th>03</th><th>04</th>
	<th>05</th><th>06</th><th>07</th><th>08</th><th>09</th><th>10</th>
	<th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
	<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th>
	<th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th>
	<th>29</th><th>30</th><th>31</th>
</tr>
';

for($mcomp = 1 ; $mcomp <= 12 ; $mcomp++){

	$timestamp[$mcomp] = mktime (0, 0, 0, $mcomp, 1, $anneeactuel);
	$nbjours[$mcomp] = date('t', $timestamp[$mcomp]);
	
	echo '<tr onClick="window.location=\'monrealise.php?uti='.$utilisateur.'&chmois=0'.$mcomp.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#FFF\';"><th>'.$mcomp.'</th>';

	for($jcomp = 1 ; $jcomp <= 31 ; $jcomp++ ){

		if($jcomp > $nbjours[$mcomp]){
			echo '<td style="background:black;"></td>';
		}else{
			$timestamp[$jcomp] = mktime (0, 0, 0, $mcomp, $jcomp, $anneeactuel);
			if(date("l" , $timestamp[$jcomp]) == 'Sunday' || date("l", $timestamp[$jcomp]) == 'Saturday' ){
				echo '<td style="background:#AAA;text-align:center;"> . </td>';
				$weekend[$mcomp] = $weekend[$mcomp]+1;
			}else{
				if (isset($rh[$mcomp][$jcomp])){
					switch($rh[$mcomp][$jcomp]){
						case 'F':
							echo '<td style="background:#333;color:#FFF;font-weight:bold;">F</td>';
						break;
						case 'CP':
							echo '<td style="background:yellow;color:#000;font-weight:bold;">CP</td>';		
						break;
						case 'Cex':
							echo '<td style="background:blue;color:#FFF;font-weight:bold;">CEx</td>';		
						break;
						case 'Canc':
							echo '<td style="background:#CC9966;color:#000;font-weight:bold;">C.Anc</td>';		
						break;
						case 'Cet':
							echo '<td style="background:#366;color:#FFF;font-weight:bold;">C.ET</td>';		
						break;

						case 'Cem':
							echo '<td style="background:orange;color:#000;font-weight:bold;">CEm</td>';		
						break;		
						case 'RTT':
							echo '<td style="background:#F90;color:#000;font-weight:bold;">RTT</td>';	
						break;
						case 'Mal':
							echo '<td style="background:#F96;color:#000;font-weight:bold;">Mal</td>';	
						break;
						case 'A.T':
							echo '<td style="background:#F96;color:#000;font-weight:bold;">A.T</td>';	
						break;
						case 'RC':
							echo '<td style="color:black;background:url(../img/rc.png)bottom right no-repeat #FFF;text-align:left;margin:0; padding:0;padding-left:2px;font-weight:bold;font-size:11px;height:30px;" valign="top">'.$totaljour[$mcomp][$jcomp].'</td>';	
						break;
					}
				}else{
					echo '<td style="background:white;">'.$totaljour[$mcomp][$jcomp].'</td>';
					
				}
			}
		}
	}
	echo '</tr>';
}
echo '</table><br/>';

echo '
<table style="border:none;">
	<tr>
		<th style="width:20px;">M</th>
		<th style="width:3px;border:none;background-color:white;"></th>
		<th title="Congés Payés">C.P</th>
		<th title="Compte Épargne Temps">C.ET</th>
		<th title="Congés Ancienneté">C.Anc</th>
		<th title="Congés Exceptionnel">C.Exp</th>
		<th title="Congés Enfant Malade"">C.Em</th>
		<th title="Maladie - Accident du travail">Mal</th>
		<th title="Repos Compensateur">RC</th>
		<th title="RTT">RTT</th>
		<th>Fériés</th>
		<th style="width:3px;border:none;background-color:white;"></th>
		<th>Jours</th>
		<th title="Week-End">WE</th>
		<th style="width:3px;border:none;background-color:white;"></th>
		<th title="Jours Travaillés">J.T</th>
		<th title="Équivalence en Heures">E.H</th>
		<th title="Temps Travaillés">Tps Tr</th>
		<th>Solde Mois</th>
		<th>Solde Année</th>
	</tr>
	<tr style="height:5px;border:none;background:white;">
	</tr>
';

for($mcomp = 1 ; $mcomp <= 12 ; $mcomp++){

	if($totalmois[$mcomp] == ''){$totalmois[$mcomp] = '0';}
	if($totalcp[$mcomp] == ''){$totalcp[$mcomp] = '0';}
	if($totalcet[$mcomp] == ''){$totalcet[$mcomp] = '0';}
	if($totalcex[$mcomp] == ''){$totalcex[$mcomp] = '0';}
	if($totalcem[$mcomp] == ''){$totalcem[$mcomp] = '0';}
	if($totalcanc[$mcomp] == ''){$totalcanc[$mcomp] = '0';}
	if($totalmal[$mcomp] == ''){$totalmal[$mcomp] = '0';}
	if($totalrc[$mcomp] == ''){$totalrc[$mcomp] = '0';}
	if($totalrtt[$mcomp] == ''){$totalrtt[$mcomp] = '0';}
	if($totalferie[$mcomp] == ''){$totalferie[$mcomp] = '0';}

	if($totalrc[$mcomp] >= 7){ $totalrc[$mcomp] = round(($totalrc[$mcomp]/7),2);}
	if($totalrtt[$mcomp] >= 7){ $totalrtt[$mcomp] = round(($totalrtt[$mcomp]/7),2);}

	$jourt[$mcomp] = $nbjours[$mcomp]-($weekend[$mcomp]+$totalcp[$mcomp]+$totalcet[$mcomp]+$totalcanc[$mcomp]+$totalcex[$mcomp]+$totalferie[$mcomp]+$totalcet[$mcomp]);	
	$equivh[$mcomp] = $jourt[$mcomp]*$hpj;
	$soldemois[$mcomp] = ($totalmois[$mcomp] - $equivh[$mcomp]);

	$totalhtheo = $totalhtheo + $equivh[$mcomp];
	$totalhrealise = $totalhrealise + $totalmois[$mcomp];	
	$totalanneeconges = $totalanneeconges + $totalcet[$mcomp] + $totalcp[$mcomp];
	$totalanneecongesexcp = $totalanneecongesexcp + $totalcex[$mcomp];
	$totalenfant = $totalenfant + $totalcem[$mcomp];
	$totalmaladie = $totalmaladie + $totalmal[$mcomp];
	$totalanneeferie = $totalanneeferie + $totalferie[$mcomp];
	$totalanneerc = $totalanneerc + $totalsrc[$mcomp];
	$totalanneertt = $totalanneertt + $totalrtt[$mcomp];

	
	for($sacomp = 1; $sacomp < 13; $sacomp++){
		$soldeannee[$sacomp] = $soldeannee[$sacomp] + $soldemois[$mcomp];
		$soldeannee[$sacomp] = round($soldeannee[$sacomp], 2);
		if($soldeannee[$sacomp] >= 0){ $stylesolde = 'text-align:center;font-weight:bold;background-color:#66CC33;';}else{
			if($totalmois[$mcomp] == 0){
				$stylesolde = 'text-align:center;font-weight:bold;background-color:#FF3333;color:#FF3333;';
				$stylesoldemois = 'color:white;';
			}else{
				$stylesolde = 'text-align:center;font-weight:bold;background-color:#FF3333;';
				$stylesoldemois = 'color:black;';
			}
		}
	}
	echo '
		<tr>
			<th>'.$mcomp .'</th>
			<td style="width:1px;border:none;background-color:white;"></td>
			<td style="background:yellow;color:#000;font-weight:bold;" title="Congés Payés"><span title="Congés Payés">'.$totalcp[$mcomp].'</span></td>
			<td style="background:#366;color:#FFF;font-weight:bold;" title="Compte Épargne Temps">'.$totalcet[$mcomp].'</td>
			<td style="background:#C96;color:#000;font-weight:bold;" title="Congés Anciennetés">'.$totalcanc[$mcomp].'</td>
			<td style="background:#36F;color:#FFF;font-weight:bold;" title="Congés Exceptionnel">'.$totalcex[$mcomp].'</td>
			<td style="background:#693;color:#000;font-weight:bold;" title="Congés Enfant Malade">'.$totalcem[$mcomp].'</td>
			<td style="background:#F96;color:#000;font-weight:bold;" title="Congés Maladie">'.$totalmal[$mcomp].'</td>
			<td style="background:#093;color:#000;font-weight:bold;" title="RC">'.$totalrc[$mcomp].'</td>
			<td style="background:#F90;color:#000;font-weight:bold;" title="RTT">'.$totalrtt[$mcomp].'</td>
			<td style="background:#333;color:white;font-weight:bold;" title="Fériés">'.$totalferie[$mcomp].'</td>
			<td style="width:1px;border:none;background-color:white;"></td>
			<td title="Nombre de jour dans le mois">'.$nbjours[$mcomp].'</td>
			<td title="Nombre de Week-end dans le mois">'.$weekend[$mcomp].'</td>
			<td style="width:1px;border:none;background-color:white;"></td>
			<td title="Nombre de jour travaillés: Jours - (Week-end + Fériés + CP + C.ET + C.anc + C.Exp + C.ET)">'.$jourt[$mcomp].'</td>
			<td title="Jours travaillés * temps d\'une journée de travail de votre contrat">'.$equivh[$mcomp].'</td>
			<td style="font-weight:bold;color:#060;text-align:left;padding-left:15px;" title="Le temps que vous avez fait">'.$totalmois[$mcomp].'</td>
			<td style="font-weight:bold;" title="Solde du mois">'.$soldemois[$mcomp].'</td>
			<td style="'.$stylesolde.'" title="Solde Année">'.$soldeannee[$mcomp].'</td>
		</tr>
	';
}
echo '</table><br/>';
if (isset($warning)){
	echo '<h2 style="color:#A00;text-decoration:underline;">Erreurs constatées:</h2>';
	echo $warning;
}

echo '<h2 style="margin-bottom:0;margin-top:-10px;">Récapitulatif annuel:</h2>
<table>
	<tr>
		<th>Heures réalisées</th><th>Congés</th><th>Exceptionnels</th>
		<th>Enfant Malade</th><th>Maladie</th><th>Fériés</th><th>RC</th><th>RTT</th>
	</tr>
	<tr>
		<td style="text-align:center;">'.$totalhrealise.' ch/'.$totalhtheo.'ch</td>
		<td style="text-align:center;">'.$totalanneeconges.' jours</td>
		<td style="text-align:center;">'.$totalanneecongesexcp.' jours</td>
		<td style="text-align:center;">'.$totalenfant.' jours</td>
		<td style="text-align:center;">'.$totalmaladie.' jours</td>
		<td style="text-align:center;">'.$totalanneeferie.' jours</td>
		<td style="text-align:center;">'.$totalanneerc.' h</td>
		<td style="text-align:center;">'.$totalanneertt.' jours</td>

	</tr>
	</table><br/>';
echo '</div></body></html>';
?>
