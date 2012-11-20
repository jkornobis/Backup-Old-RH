<?php
require_once('config.php');
$titlepage = "Statistiques - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menustats;
require_once('tests.php');

if(isset($_POST['chuti']) || isset($_GET['chuti'])){
	if ($_POST['chuti'] == 'globale' || $_GET['chuti'] == 'globale'){
		if( $_POST['chmois'] == 13 ){
			$resultats=$connexion->query('
				SELECT *
				FROM `Event`, `UserObm`,  `EventCategory1`
				WHERE `event_usercreate` = `userobm_id`
				AND `event_category1_id` = `eventcategory1_id`
				AND  `event_date` > "'.$anneeactuel.'-01-00"
				AND `event_date` < "'.$anneeactuel.'-12-33"
			') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$titre = '<h2>Statistiques Globales Projets/Axes de l\'année <u>'.$anneeactuel.'</u></h2>';
		}else{
			$resultats=$connexion->query('
				SELECT*
				FROM `Event`, `UserObm`,  `EventCategory1`
				WHERE `event_usercreate` = `userobm_id`
				AND `event_category1_id` = `eventcategory1_id`
				AND  `event_date` > "'.$anneeactuel.'-'.$moisactuel.'-00"
				AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
			') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$titre = '<h2>Statistiques Globales Projets/Axes:';
			if($_GET['chmois'] == "12" || $_POST['chmois'] == "12"){
				$titre .='<a href="stats.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois=1&channee='.($anneeactuel+1).'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
			}else{
				if($_GET['chmois'] == "1" || $_POST['chmois'] == "1"){
					$titre .= '<a href="stats.php?chmois=12&channee='.($anneeactuel-1).'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
				}else{
					$titre .= '<a href="stats.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
				}
			}
		}
	}else{
		if( $_POST['chmois'] == 13 ){
			$resultats=$connexion->query('
					SELECT *
					FROM `Event`, `UserObm`,  `EventCategory1`
					WHERE `event_usercreate` = `userobm_id`
					AND `userobm_login` = "'.$_POST['chuti'].'"
					AND `event_category1_id` = `eventcategory1_id`
					AND  `event_date` >= "'.$anneeactuel.'-01-01"
					AND `event_date` <= "'.$anneeactuel.'-12-31"
			');
			$titre = '<h2>Statistiques Pour <u>'.$_POST['chuti'].'</u> Projets/Axes de l\'année <u>'.$anneeactuel.'</u></h2>';
		}else{
			$resultats=$connexion->query('
					SELECT *
					FROM `Event`, `UserObm`,  `EventCategory1`
					WHERE `event_usercreate` = `userobm_id`
					AND `userobm_login` = "'.$_POST['chuti'].'"
					AND `event_category1_id` = `eventcategory1_id`
					AND  `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
					AND `event_date` <= "'.$anneeactuel.'-'.$moisactuel.'-31"
			');
			$titre = '<h2>Statistiques Pour <u>'.$_POST['chuti'].'</u> Projets/Axes: <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span></h2>';
		}
	}
}else{
	$resultats=$connexion->query('
	SELECT *
				FROM `Event`, `UserObm`,  `EventCategory1`
				WHERE `event_usercreate` = `userobm_id`
				AND `event_category1_id` = `eventcategory1_id`
				AND  `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
				AND `event_date` <= "'.$anneeactuel.'-'.$moisactuel.'-31"
	');
	$titre = '<h2>Statistiques Globales Projets/Axes:';

	if($_GET['chmois'] == "12" || $_POST['chmois'] == "12"){
		$titre .='<a href="stats.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois=1&channee='.($anneeactuel+1).'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
	}else{
		if($_GET['chmois'] == "1" || $_POST['chmois'] == "1"){
			$titre .= '<a href="stats.php?chmois=12&channee='.($anneeactuel-1).'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
		}else{
			$titre .= '<a href="stats.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="stats.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
		}
	}
}

echo'<div id="content">
'.$titre.'
<table>
	<tr>
		<th>Projets</th>
		<th>Axe 1</th>
		<th>Axe 2</th>
		<th>Axe 3</th>
		<th>Axe 4</th>
		<th>Axe 5</th>
		<th>Axe 6</th>
		<th>Total</th>
	</tr>
';
$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() ) {
$catcode = $ligne->eventcategory1_code;
	$totalglobal = $totalglobal + $ligne->event_duration;
	$totalprojet[$catcode] = $totalprojet[$catcode]  + $ligne->event_duration;
	$label[$catcode] = $ligne->eventcategory1_label;
	if( $ligne->eventcategory1_code >= 100 && $ligne->eventcategory1_code <= 199){
		$totalaxe1 = $totalaxe1 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code >= 200 && $ligne->eventcategory1_code <= 299){
		$totalaxe2 = $totalaxe2 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code >= 300 && $ligne->eventcategory1_code <= 399){
		$totalaxe3 = $totalaxe3 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code >= 400 && $ligne->eventcategory1_code <= 499){
		$totalaxe4 = $totalaxe4 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code >= 500 && $ligne->eventcategory1_code <= 599){
		$totalaxe5 = $totalaxe5 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code >= 600 && $ligne->eventcategory1_code <= 699){
		$totalaxe6 = $totalaxe6 + $ligne->event_duration;}
	if( $ligne->eventcategory1_code == 907 || $ligne->eventcategory1_code == 908){
		$totalaxe6 = $totalaxe6 + $ligne->event_duration;}
}
for( $i = 1; $i <= 999; $i++){
	if($totalprojet[$i] == NULL){
	}else{
		if( $i < 199) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 200 && $i < 299) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 300 && $i < 399) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 400 && $i < 499) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td></td>		
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 500 && $i < 599) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 600 && $i < 699) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
		</tr>';
		}
		if( $i == 907 || $i == 908) {
		echo '
		<tr>
			<td style="background-color:lightblue;">'.$label[$i].'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
		</tr>';
		}
	}
}
$totalglobal = (($totalaxe1+$totalaxe2+$totalaxe3+$totalaxe4+$totalaxe5+$totalaxe6+$totalaxe9));
$pourcentaxe1 = round(($totalaxe1*100)/$totalglobal);
$pourcentaxe2 = round(($totalaxe2*100)/$totalglobal);
$pourcentaxe3 = round(($totalaxe3*100)/$totalglobal);
$pourcentaxe4 = round(($totalaxe4*100)/$totalglobal);
$pourcentaxe5 = round(($totalaxe5*100)/$totalglobal);
$pourcentaxe6 = round(($totalaxe6*100)/$totalglobal);
echo'
<tr>
	<th>Totaux:</th>
	<th>'.($totalaxe1/3600).' ch</th>
	<th>'.($totalaxe2/3600).' ch</th>
	<th>'.($totalaxe3/3600).' ch</th>
	<th>'.($totalaxe4/3600).' ch</th>
	<th>'.($totalaxe5/3600).' ch</th>
	<th>'.($totalaxe6/3600).' ch</th>
	<th>'.($totalglobal/3600).' ch</th>
</tr>
<tr>
	<th>Pourcentages:</th>
	<th>'.$pourcentaxe1.' %</th>
	<th>'.$pourcentaxe2.' %</th>
	<th>'.$pourcentaxe3.' %</th>
	<th>'.$pourcentaxe4.' %</th>
	<th>'.$pourcentaxe5.' %</th>
	<th>'.$pourcentaxe6.' %</th>
	<th>'.round(($totalglobal*100)/$totalglobal).' %</th>
</tr>
';
echo '</table>';
echo('<br/><div><img src="graph.php?ax1='.$pourcentaxe1.'&ax2='.$pourcentaxe2.'& ax3='.$pourcentaxe3.'&ax4='.$pourcentaxe4.'&ax5='.$pourcentaxe5.'&ax6='.$pourcentaxe6.'" alt="Mon graphique"/>');
echo '</div></div></body></html>';
?>
