<?php
require_once('config.php');
$titlepage = "Statistiques - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menustats;
require_once('tests.php');

if($_POST['ok'] == 'ok'){
	$labelcompt = 1;
	$nombrelabel = 1;
	$a = 0;
	$anneeactuel = $_POST['channee'];

	for($i = 100; $i < 900; $i++){
		if (isset($_POST[$i])){
			$nombrelabel = $nombrelabel +1;
			if($a == 0){
				$champsrequete .= '`eventcategory1_code` = "'.$_POST[$i].'" ';
				$a = 1;
			}else{
				$champsrequete .= 'OR `eventcategory1_code` = "'.$_POST[$i].'" ';
			}
			$labelsql=$connexion->query('
				SELECT *
				FROM `Event`, `EventCategory1`
				WHERE `event_category1_id` = `eventcategory1_id`
				AND `eventcategory1_code` = "'.$_POST[$i].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$labelsql->setFetchMode(PDO::FETCH_OBJ);
			while($ligne = $labelsql->fetch()){
				$labeltest = $ligne->eventcategory1_label;
				if($labeltest != $labelmem && $labelcompt <= $nombrelabel){
					$label .= $labeltest.'<br/>';
					$labelmem = $labeltest;
					$labelcompt = $labelcompt+1;
				}
			}
		}
	}
	
	for ($i = 1; $i <= 1000; $i++){
		echo'<tr>';
		for($cmois = $_POST['chmoisdebut'] ; $cmois <= $_POST['chmoisfin']; $cmois++){ 
			$requetesql = '
					SELECT *
					FROM `Event` , `UserObm` , `EventCategory1`
					WHERE `event_usercreate` = `userobm_id`
					AND `userobm_id`= "'.$i.'"
					AND `event_category1_id` = `eventcategory1_id`
					AND `event_date` >= "'.$anneeactuel.'-'.$cmois.'-01"
					AND `event_date` <= "'.$anneeactuel.'-'.($cmois+1).'-01"
					AND ('.$champsrequete.')
			;';
			$resultats=$connexion->query($requetesql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			while($ligne = $resultats->fetch()){
				$total[$i][$cmois] = $total[$i][$cmois] + ($ligne->event_duration/3600);
				$totalmois[$cmois] = $totalmois[$cmois] + ($ligne->event_duration/3600);
				$totaluser[$i] = $totaluser[$i] + ($ligne->event_duration/3600);
				$totalannee = $totalannee + ($ligne->event_duration/3600);
			}
		}
	}
	echo '<div id="content"><br/>';
	echo '<fieldset>
		<legend style="padding-left:20px;padding-right:20px;">'.$_POST['titre'].' de: '.$moisactuelmot[($_POST['chmoisdebut'])].' à '.$moisactuelmot[($_POST['chmoisfin'])].' '.$anneeactuel.' </legend>
		 '.$label.'
	</fieldset>';
	echo '<br/>
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
			<th>Total Utilisateur</th>
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
			if ($i%2 == 1){
				echo '<tr style="background-color:#FFF;" >';
				$i++;
			}else{
				echo '<tr style="background-color:#EEE;" >';
				$i++;
			}
			echo
				'
					<td>'.$ligne->userobm_lastname .' '.$ligne->userobm_firstname.'</td>
					<td>'.$total[$userid][1].'</td><td>'.$total[$userid][2].'</td><td>'.$total[$userid][3].'</td>
					<td>'.$total[$userid][4].'</td><td>'.$total[$userid][5].'</td>
					<td>'.$total[$userid][6].'</td><td>'.$total[$userid][7].'</td>
					<td>'.$total[$userid][8].'</td><td>'.$total[$userid][9].'</td><td>'.$total[$userid][10].'</td>
					<td>'.$total[$userid][11].'</td><td>'.$total[$userid][12].'</td>
					<td style="text-align:left;padding-left:20px;font-weight:bold;">'.$totaluser[$userid].'</td>
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

/*///////////////////////////////////////////////////////////////////////////////
												Fin Page requete traité
///////////////////////////////////////////////////////////////////////////////*/
}else{
/*///////////////////////////////////////////////////////////////////////////////
												Page formulaire
///////////////////////////////////////////////////////////////////////////////*/
if($_GET['deminfos'] == 1){
	$texteformation = '<fieldset class="formation" style="width:1170px;font-size:15px;">
<legend><b>Formation:</b> Module Administrateur: Statistiques Personalisées</legend>
<h3>Statistiques Personalisées:</h3>
<p style="text-align:justify;">
Cette Page regroupe les différentes options et pages 
</p>
</fieldset><br/>';
}

	echo '<div id="content">';
	echo '<h2>Page de Sélection d\'ensemble de projet:</h2>'.$texteformation;
	echo'
	<form method="post" action="statscustom.php">
	<fieldset style="float:left;width:400px;"><legend>Formulaire de requete complexe:</legend>
	<input type="hidden" name="ok" id="ok" value="ok"/>
	Titre du tableau: <input type="text" name="titre" id="titre" size="40">
	<br/>( l\'Année est insérée automatiquement )<br/><br/>
	De :<select id="chmoisdebut" name="chmoisdebut" style="height:24px;">
	<option value="1">'.$moisactuelmot[1].'</option>
	<option value="2">'.$moisactuelmot[2].'</option>
	<option value="3">'.$moisactuelmot[3].'</option>
	<option value="4">'.$moisactuelmot[4].'</option>
	<option value="5">'.$moisactuelmot[5].'</option>
	<option value="6">'.$moisactuelmot[6].'</option>
	<option value="7">'.$moisactuelmot[7].'</option>
	<option value="8">'.$moisactuelmot[8].'</option>
	<option value="9">'.$moisactuelmot[9].'</option>
	<option value="10">'.$moisactuelmot[10].'</option>
	<option value="11">'.$moisactuelmot[11].'</option>
	<option value="12">'.$moisactuelmot[12].'</option>
	</select>
	à: <select id="chmoisfin" name="chmoisfin" style="height:24px;">
		<option value="12">'.$moisactuelmot[12].'</option>
		<option value="12">---------</option>
		<option value="1">'.$moisactuelmot[1].'</option>
		<option value="2">'.$moisactuelmot[2].'</option>
		<option value="3">'.$moisactuelmot[3].'</option>
		<option value="4">'.$moisactuelmot[4].'</option>
		<option value="5">'.$moisactuelmot[5].'</option>
		<option value="6">'.$moisactuelmot[6].'</option>
		<option value="7">'.$moisactuelmot[7].'</option>
		<option value="8">'.$moisactuelmot[8].'</option>
		<option value="9">'.$moisactuelmot[9].'</option>
		<option value="10">'.$moisactuelmot[10].'</option>
		<option value="11">'.$moisactuelmot[11].'</option>
		<option value="12">'.$moisactuelmot[12].'</option>
	</select><br/><br/>
	Année:<select id="channee" name="channee" style="height:24px;"><br/>
	<option value="'.(date("Y")+0).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")+0).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>

	<br/><br/>
	<button type="submit" style="height:24px;" onclick="return(confirm(\'Êtes-vous sûr de vouloir executer cette requête ?\'));">Valider</button>
	</fieldset>
	<fieldset><legend>Choix des catégories:</legend>
	'.$listeprojetsbouton.'</p>
	
	</fieldset>
	</form>
	';
	echo '</div></body></html>';
}

?> Statistiques Personalisées
