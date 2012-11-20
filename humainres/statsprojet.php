<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menustats;
require_once('tests.php');

	$catcode = $_POST['projet'];

	$labelcompt = 1;
	$nombrelabel = 1;
	$a = 0;

	$labelsql=$connexion->query('
		SELECT *
		FROM `Event`, `EventCategory1`
		WHERE `event_category1_id` = `eventcategory1_id`
		AND `eventcategory1_code` = "'.$catcode.'"
	;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$labelsql->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $labelsql->fetch()){
		$label = $ligne->eventcategory1_label;
	}

	for ($i = 1; $i <= 1000; $i++){
		echo'<tr>';
		for($cmois = 1 ; $cmois <= 12; $cmois++){ 
			$requetesql = '
				SELECT *
				FROM `Event` , `UserObm` , `EventCategory1`
				WHERE `event_usercreate` = `userobm_id`
				AND `userobm_id`= "'.$i.'"
				AND `event_category1_id` = `eventcategory1_id`
				AND `event_date` >= "'.$anneeactuel.'-'.$cmois.'-01"
				AND `event_date` <= "'.$anneeactuel.'-'.($cmois+1).'-01"
				AND `eventcategory1_code` = "'.$catcode.'"
			;';
			$resultats=$connexion->query($requetesql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			while($ligne = $resultats->fetch()){
				$total[$i][$cmois] = $total[$i][$cmois] + ($ligne->event_duration/3600);
				$totalmois[$cmois] = $totalmois[$cmois] + ($ligne->event_duration/3600);
				$totalannee = $totalannee + ($ligne->event_duration/3600);
			}
		}
	}
	echo '<div id="content"><br/>';
	echo '<h2 style="width:800px;"> <span style="color:#07A;font-size:26px;font-weight:bold;">'.$label.'</span> pour l\'année: '.$anneeactuel.'</h2>';
	echo '
	<table>
		<tr>
			<th style="width:300px;">Nom</th>
			<th>Janvier</th>
			<th>Février</th>
			<th>Mars</th>
			<th>Avril</th>
			<th>Mai</th>
			<th>Juin</th>
			<th>Juillet</th>
			<th>Aout</th>
			<th>Septembre</th>
			<th>Octobre</th>
			<th>Novembre</th>
			<th>Décembre</th>
		</tr>
	';

	$user = $connexion->query('
		SELECT *
		FROM `UserObm`
		ORDER BY `userobm_lastname` ASC
	');

	$user->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $user->fetch()){
	$userid = $ligne->userobm_id;

		if (isset($total[$userid])){
			echo
				'<tr>
					<td>'.$ligne->userobm_lastname .' '.$ligne->userobm_firstname.'</td>
					<td>'.$total[$userid][1].'</td><td>'.$total[$userid][2].'</td><td>'.$total[$userid][3].'</td>
					<td>'.$total[$userid][4].'</td><td>'.$total[$userid][5].'</td>
					<td>'.$total[$userid][6].'</td><td>'.$total[$userid][7].'</td>
					<td>'.$total[$userid][8].'</td><td>'.$total[$userid][9].'</td><td>'.$total[$userid][10].'</td>
					<td>'.$total[$userid][11].'</td><td>'.$total[$userid][12].'</td>
				</tr>';
		}
	}
echo '</table>
<h2>Récapitulatifs des heures</h2>
<table style="border:none;font-size:13px;">
	<tr>
		<th style="border:none;background:#FFF;"></th>
		<th>Janvier</th>
		<th>Février</th>
		<th>Mars</th>
		<th>Avril</th>
		<th>Mai</th>
		<th>Juin</th>
		<th>Juillet</th>
		<th>Aout</th>
		<th>Septembre</th>
		<th>Octobre</th>
		<th>Novembre</th>
		<th>Décembre</th>
	<th>Total Annuel</th>
	</tr>
';

echo '
<tr><th style="width:170px;">Total: (cH)</th>';
for($cmois = 1 ; $cmois <= 12; $cmois++){ 
	$pourcentaxe[$cmois] = round(($totalmois[$cmois]*100)/$totalannee, 2);
	$totalpourcentage = $totalpourcentage + $pourcentaxe[$cmois];
	echo '<td style="text-align:center;font-weight:bold;">'.$totalmois[$cmois].'</td>';
}
echo '<td style="text-align:center;font-weight:bold;">'.$totalannee.' cH</td>';
echo '</tr><tr><th>Pourcents:</th>';
for($cmois = 1 ; $cmois <= 12; $cmois++){
	echo '<td style="text-align:center;font-weight:bold;">'.$pourcentaxe[$cmois].' %</td>';
}

echo '<td style="text-align:center;font-weight:bold;">'.round($totalpourcentage).' %</td></tr>';

echo '</table>';

echo '<h2>Graphique des pourcentages:</h2><p>
<img src="graphmois.php?ax1='.$pourcentaxe[1].'&ax2='.$pourcentaxe[2].'& ax3='.$pourcentaxe[3].'&ax4='.$pourcentaxe[4].'&ax5='.$pourcentaxe[5].'&ax6='.$pourcentaxe[6].'&ax7='.$pourcentaxe[7].'&ax8='.$pourcentaxe[8].'&ax9='.$pourcentaxe[9].'&ax10='.$pourcentaxe[10].'&ax11='.$pourcentaxe[11].'&ax12='.$pourcentaxe[12].' " alt="Mon graphique"/>';
echo '</p></div></body></html>';
?>
