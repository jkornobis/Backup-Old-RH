<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menurecap;
require_once('tests.php');

echo ('<div id="content">
<h2>Page en travaux ! : <a href="recap.php?chmois=0'.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="recap.php?chmois=0'.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a></h2>
<table>
<tr>
<th style="width:300px;">Nom</th>
<th>Absence Maladie</th>
<th>Absence Maternité</th>
<th>CP</th>
<th>Congés Exceptionnels</th>
<th>Frais KM</th>
<th>Frais divers</th>
</tr>
');

$resultats=$connexion->query("SELECT * FROM UserObm ORDER BY userobm_lastname ASC");
$resultats->setFetchMode(PDO::FETCH_OBJ);
///////////////////////////////////////////////////////////////////
////			 BOUCLE POUR LA SÉLECTION DES UTILISATEURS	 				 ////
///////////////////////////////////////////////////////////////////
while( $ligne = $resultats->fetch() )
{
if($ligne->userobm_lastname == NULL || $ligne->userobm_lastname == 'admin' || $ligne->userobm_lastname == 'Admin Lastname'
	|| $ligne->userobm_lastname == 'MLA' || $ligne->userobm_lastname == 'Secrétaires'  || $ligne->userobm_statut == "non" || $ligne->userobm_lastname == 'congés'){
	}else{
		echo '<tr><td>'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</td>';
		$recap = $connexion->query('
			SELECT *
			FROM `UserObm`, `FraisEvent`, `Event`, `EventCategory1`
			WHERE `userobm_id` = `fraisevent_userobmid`
			AND `userobm_id` = `event_usercreate`
			AND `event_category1_id` = `eventcategory1_id`
			AND `userobm_id` = "'.$ligne->userobm_id.'"
			AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-00"
			AND `event_date` <= "'.$anneeactuel.'-'.$moisactuel.'-33"
		;');
		$recap->setFetchMode(PDO::FETCH_OBJ);
		while( $ligne = $recap->fetch()){
			if($ligne->eventcategory1_code >= '901' && $ligne->eventcategory1_code <= '999'){
				switch ($ligne->eventcategory1_code){
					case '906': $totalabsmaladie = $totalabsmaladie + 1; break;
					case '902': $totalabsmaternite = $totalabsmaternite + 1; break;
					case '903': $totalabsmaladie = $totalabsmaladie + 1; break;
					case '901': $totalcp = $totalcp + 1; break;
					case '902': $totalcexcept = $totalcexcept + 1; break;
				}
			}
			if(isset($ligne->fraisevent_note)){
				switch ($ligne->fraisevent_note){
					case '1': $totalfraiskm = $totalfraiskm + $ligne->fraisevent_prix; break;
					case '2': $totalfraisdivers = $totalfraisdivers + $ligne->fraisevent_prix; break;
				}
			}else{
				$totalfraiskm = $totalfraisdivers  = NULL;
			}
		}
		echo '<td>'.$totalabsmaladie.'</td><td>'.$totalabsmaternite.'</td><td>'.$totalcp.'</td><td>'.$totalcexcept.'</td><td>'.$totalfraiskm.' €</td><td>'.$totalfraisdivers.' €</td>';
		$totalabsmaladie = $totalabsmaternite = $totalcp = $totalcexcept = $totalfraiskm = $totalfraisdivers  = NULL;
		echo '</tr>';
	}
}
echo '</body></html>';
?>
