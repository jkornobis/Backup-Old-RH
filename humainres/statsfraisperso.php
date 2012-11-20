<?php
require_once('config.php');
$titlepage = "Statistiques Frais Personnalisé - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menufrais;
require_once('tests.php');

echo '<div id="content"><h2>Statistiques Globales Projets/Axes: <a href="statsfraisperso.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'  ">'.$flprecedent.'</a><span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="statsfraisperso.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.' ">'.$flsuivant.'</a></h2>';

for ($i = 1; $i <= 1000; $i++){
	$requetesql = '
		SELECT *
		FROM EventLink
		INNER JOIN Event ON event_id = eventlink_event_id
		INNER JOIN EventCategory1 ON event_category1_id = eventcategory1_id
		INNER JOIN UserEntity ON userentity_entity_id = eventlink_entity_id
		INNER JOIN UserObm ON userobm_id = userentity_user_id
		INNER JOIN UserObmRH ON userobmrh_id = userentity_user_id		
		WHERE userentity_user_id = "'.$i.'"
		AND event_date >= "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND event_date <= "'.$anneeactuel.'-'.$moisactuel.'-32"
		AND `eventcategory1_code` >= "900"
		AND `event_description` = "ok"
	;';
	$resultats=$connexion->query($requetesql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$resultats->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $resultats->fetch()){
		$catcode = $ligne->eventcategory1_code;
		$hpj = ($ligne->temps_hpj)/5;
		switch($catcode){
			case '901':
				$totalcpcet[$i] = $totalcpcet[$i] + 1;
			break;
			case '910':
				$totalcpcet[$i] = $totalcpcet[$i] + 1;
			break;
			case '909':
				$totalanc[$i] = $totalanc[$i] + 1;
			break;
			case '906':
				if(($ligne->event_duration/3600) <= ($hpj/2)){
					$totalmal[$i] = ($totalmal[$i] + '0.5');
				}else{
					$totalmal[$i] = $totalmal[$i] + 1;
				}
			break;
			case '911':
				if(($ligne->event_duration/3600) <= ($hpj/2)){
					$totalmal[$i] = ($totalmal[$i] + '0.5');
				}else{
					$totalmal[$i] = $totalmal[$i] + 1;
				}
			break;
			case '910':
				$totalcpcet[$i] = $totalcpcet[$i] + 1;
			break;
		}
	}
}

for ($i = 1; $i <= 1000; $i++){
	$requetesql = '
		SELECT *
		FROM `FraisEvent`, `EventCategory1`, `UserObm`
		WHERE `fraisevent_catcode` = `eventcategory1_code`
		AND `fraisevent_userobmid` = `userobm_id`
		AND `userobm_id` = "'.$i.'"
		AND `fraisevent_date` > "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND `fraisevent_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
	;';

	$resultats=$connexion->query($requetesql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$resultats->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $resultats->fetch()){
		$typefrais = $ligne->fraisevent_note;
		switch($typefrais){
			case '1':
				$totalfraisdep[$i] = $totalfraisdep[$i] + $ligne->fraisevent_prix;
			break;
			case '2':
				$totalfraisdivers[$i] = $totalfraisdivers[$i] + $ligne->fraisevent_prix;
			break;
		}
	}
}


	echo '
	<table>
		<tr>
			<th style="width:300px;">Nom</th>
			<th>C.P + C.E.T</th>
			<th>Congés Maladie</th>
			<th>Congés Anciennetés</th>
			<th>Frais Km</th>
			<th>Frais Divers</th>
		</tr>
		<tr>
			<th style="background:#FF9;"></th>
			<th colspan="3" style="background:#FF9;color:#000;">Exprimé en cH</th>
			<th colspan="2" style="background:#FF9;color:#000;">Exprimé en Euros</th>
		</tr>
	';

$user = $connexion->query('
	SELECT *
	FROM `UserObm`, `UserObmRH`
	WHERE `userobmrh_id` = `userobm_id`
	ORDER BY `userobm_lastname` ASC
;');

$user->setFetchMode(PDO::FETCH_OBJ);
while($ligne = $user->fetch()){
	$userid = $ligne->userobm_id;
	$nomuser = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
	$hpj = ($ligne->temps_hpj)/5;

	if($ligne->userobm_lastname == NULL || $ligne->userobm_lastname == 'admin' || $ligne->userobm_lastname == 'Admin Lastname'
	|| $ligne->userobm_lastname == 'MLA' || $ligne->userobm_lastname == 'Secrétaires'  || $ligne->userobm_statut == "non" || 	
	$ligne->userobm_archive == "1" ){
	}else{
	if($totalcpcet[$userid] > 0){$stylecpcet[$userid] = 'font-weight:bold;';}
	if($totalmal[$userid] > 0){$stylemal[$userid] = 'font-weight:bold;';}
	if($totalanc[$userid] > 0){$styleanc[$userid] = 'font-weight:bold;';}
	if($totalfraisdep[$userid] > 0){$stylefraisdep[$userid] = 'font-weight:bold;';}
	if($totalfraisdivers[$userid] > 0){$stylefraisdivers[$userid] = 'font-weight:bold;';}

	if ($i%2 == 1){
		echo '<tr style="background-color:#FFF;" >';
		$i++;
	}else{
		echo '<tr style="background-color:#EEE;" >';
		$i++;
	}
	echo '
			<td style="width:300px;">'.$nomuser.'</td>
			<td style="'.$stylecpcet[$userid].'">'.($totalcpcet[$userid]*$hpj).'</td>
			<td style="'.$stylemal[$userid].'">'.($totalmal[$userid]*$hpj).'</td>
			<td style="'.$styleanc[$userid].'">'.($totalanc[$userid]*$hpj).'</td>
			<td style="'.$stylefraisdep[$userid].'">'.$totalfraisdep[$userid].'</td>
			<td style="'.$stylefraisdivers[$userid].'">'.$totalfraisdivers[$userid].'</td>
		</tr>
	';
	}
}
echo '</table>';
echo '</div></body></html>';
?>
