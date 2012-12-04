<?php
require_once('config.php');
$titlepage = "Réalisé individuel - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menurealise;
require_once ('tests.php');

$anneeactuel = $_POST['channee'];
if(isset($_GET['chuti'])){
		$utilisateur = $_GET['chuti'];
}else{
	if(isset($_POST['chuti'])){
		$utilisateur = $_POST['chuti'];
	}
}

$tempshpj=$connexion->query('
	SELECT *
	FROM `UserObm`, `UserObmRH`
	WHERE `userobm_login` = "'.$utilisateur.'"
	AND `userobm_id` = `userobmrh_id`
;');
$tempshpj->setFetchMode(PDO::FETCH_OBJ);
$tempshpj->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $tempshpj->fetch())
{
	$useridmem = $ligne->userobm_id;
	$hpj = ($ligne->temps_hpj/5);
	$compteurconges = $ligne->userobm_congesnormale;
	$datedebut = $ligne->userobm_datebegin;
	$nomuser = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
	$compteur_jourdebut = $ligne->compteur_jourdebut;
	list($anneedebut,$moisdebut,$jourdebut)=sscanf($datedebut,"%d-%d-%d");
}
if(!isset($moisdebut) || $anneedebut != date("Y")){$moisdebut = 1; $jourdebut = 1;}
if(!isset($moisfin) || $anneefin != date("Y")){$moisfin = 12; $jourfin = 31;}

echo '<div id="content">
<h2>Réalisé de l\'année '.$anneeactuel.'- '.$nomuser.'
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
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'CPv';
						$totalcp[$mois] = $totalcp[$mois]+1;
					break;
					case"non":
						$rh[$mois][$jour] = 'CPr';
					break;
					default:
						$rh[$mois][$jour] = 'CP';
					break;
				}
			break;
			case '902':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'Cexv';
						$totalcex[$mois] = $totalcex[$mois]+1;
					break;
					case"non":
						$rh[$mois][$jour] = 'Cexr';
					break;
					default:
						$rh[$mois][$jour] = 'Cex';
					break;
				}
			break;
			case '909':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'Cancv';
						$totalcanc[$mois] = $totalcanc[$mois]+1;
					break;
					case"non":
						$rh[$mois][$jour] = 'Cancr';
					break;
					default:
						$rh[$mois][$jour] = 'Canc';
					break;
				}
			break;
			case '910':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'Cetv';
					$totalcet[$mois] = $totalcet[$mois]+1;
					break;
					case"non":
						$rh[$mois][$jour] = 'Cetr';
					break;
					default:
						$rh[$mois][$jour] = 'Cet';
					break;
				}
			break;
			case '911':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'A.Tv';
						$totalmal[$mois] = $totalmal[$mois]+1;
					break;
					case"non":
						$rh[$mois][$jour] = 'A.Tr';
					break;
					default:
						$rh[$mois][$jour] = 'A.T';
					break;
				}
			break;
			case '999':
				$rh[$mois][$jour] = 'F';
				$totalferie[$mois] = $totalferie[$mois]+1;
			break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////  														COMPTEUR POTENTIELLEMENT SUR LA DEMI JOURNNÉE       						          /////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			case '903':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'Cemv';
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
					case"non":
						$rh[$mois][$jour] = 'Cemr';
					break;
					default:
						$rh[$mois][$jour] = 'Cem';
					break;
			}
			break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case '904':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'RTTv';
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
					case"non":
						$rh[$mois][$jour] = 'RTTr';
					break;
					default:
						$rh[$mois][$jour] = 'RTT';
					break;
				}
			break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case '905':	
				$rh[$mois][$jour] = 'RC';		
			break;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			case '906':
				switch($ligne->event_description){
					case "ok":
						$rh[$mois][$jour] = 'Malv';
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
					case"non":
						$rh[$mois][$jour] = 'Malr';
					break;
					default:
						$rh[$mois][$jour] = 'Mal';
					break;
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

for($mcomp = $moisdebut ; $mcomp <= $moisfin ; $mcomp++){

	$timestamp[$mcomp] = mktime (0, 0, 0, $mcomp, 1, $anneeactuel);
	$nbjours[$mcomp] = date('t', $timestamp[$mcomp]);
	
	echo '<tr onClick="window.location=\'monrealise.php?uti='.$utilisateur.'&chmois=0'.$mcomp.'\'"><th>'.$mcomp.'</th>';

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
						//		Congés Payés
						case 'CP':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">CP</td>';		
						break;
						case 'CPv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">CP</td>';		
						break;
						case 'CPr':
							echo '<td style="background:red;color:#000;font-weight:bold;text-align:center;">CP</td>';		
						break;
						//		Congés Exceptionnel
						case 'Cex':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">CEx</td>';		
						break;
						case 'Cexv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">CEx</td>';		
						break;
						case 'Cexr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">CEx</td>';		
						break;
						//		Congés Ancienneté
						case 'Canc':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">C.Anc</td>';		
						break;
						case 'Cancv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">C.Anc</td>';		
						break;
						case 'Cancr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">C.Anc</td>';		
						break;
						//		Colmpte Épargne temps
						case 'Cet':
							echo '<td style="background:orange;color:#FFF;font-weight:bold;text-align:center;">C.ET</td>';		
						break;
						case 'Cetv':
							echo '<td style="background:#FF3;color:#FFF;font-weight:bold;text-align:center;">C.ET</td>';		
						break;
						case 'Cetr':
							echo '<td style="background:#A00;color:#FFF;font-weight:bold;text-align:center;">C.ET</td>';		
						break;
						//		Congés Enfant Malade
						case 'Cem':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">CEm</td>';		
						break;
						case 'Cemv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">CEm</td>';		
						break;
						case 'Cemr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">CEm</td>';		
						break;
						//		RTT
						case 'RTT':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">RTT</td>';	
						break;
						case 'RTTv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">RTT</td>';	
						break;
						case 'RTTr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">RTT</td>';	
						break;
						//		Maladie
						case 'Mal':
							echo '<td style="background:orange;color:#000;font-weight:bold;text-align:center;">Mal</td>';	
						break;
						case 'Malv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">Mal</td>';	
						break;
						case 'Malr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">Mal</td>';	
						break;
						//		Accident du travail
						case 'A.T':
							echo '<td style="background:#F96;color:#000;font-weight:bold;text-align:center;">A.T</td>';	
						break;
						case 'A.Tv':
							echo '<td style="background:#FF3;color:#000;font-weight:bold;text-align:center;">A.T</td>';	
						break;
						case 'A.Tr':
							echo '<td style="background:#A00;color:#000;font-weight:bold;text-align:center;">A.T</td>';	
						break;
						////		RC		////
						case 'RC':
							echo '<td style="color:black;background:url(../img/rc.png)bottom right no-repeat #FFF;text-align:left;margin:0; padding:0;padding-left:2px;font-weight:bold;font-size:11px;height:30px;" valign="top">'.$totaljour[$mcomp][$jcomp].'</td>';	
						break;
					}
				}else{
					if($totaljour[$mcomp][$jcomp] > "12"){
						echo '<td style="color:white;background:#A00;text-align:left;font-weight:bold;text-align:center;">! E !</td>';	
					}else{
						echo '<td style="background:white;">'.$totaljour[$mcomp][$jcomp].'</td>';
					}
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
		<th title="Congés Exceptionnel">C.Excp</th>
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

for($mcomp = $moisdebut ; $mcomp <= $moisfin ; $mcomp++){

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

	if($mcomp == $moisdebut){
		$jourt[$mcomp] = $nbjours[$mcomp]-($weekend[$mcomp]+$totalcp[$mcomp]+$totalcet[$mcomp]+$totalcanc[$mcomp]+$totalcex[$mcomp]+$totalferie[$mcomp]+$totalmal[$mcomp]+$totalcem[$mcomp]+$compteur_jourdebut);	
	}else{
		$jourt[$mcomp] = $nbjours[$mcomp]-($weekend[$mcomp]+$totalcp[$mcomp]+$totalcet[$mcomp]+$totalcanc[$mcomp]+$totalcex[$mcomp]+$totalferie[$mcomp]+$totalmal[$mcomp]+$totalcem[$mcomp]);	
	}

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
			<td style="background:#FF3;color:#000;font-weight:bold;" title="Congés Payés"><span title="Congés Payés">'.$totalcp[$mcomp].'</span></td>
			<td style="background:#FF9;color:#000;font-weight:bold;" title="Compte Épargne Temps">'.$totalcet[$mcomp].'</td>
			<td style="background:#FF3;color:#000;font-weight:bold;" title="Congés Anciennetés">'.$totalcanc[$mcomp].'</td>
			<td style="background:#FF9;color:#000;font-weight:bold;" title="Congés Exceptionnel">'.$totalcex[$mcomp].'</td>
			<td style="background:#FF3;color:#000;font-weight:bold;" title="Congés Enfant Malade">'.$totalcem[$mcomp].'</td>
			<td style="background:#FF9;color:#000;font-weight:bold;" title="Congés Maladie">'.$totalmal[$mcomp].'</td>
			<td style="background:#FF3;color:#000;font-weight:bold;" title="RC">'.$totalrc[$mcomp].'</td>
			<td style="background:#FF9;color:#000;font-weight:bold;" title="RTT">'.$totalrtt[$mcomp].'</td>
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
