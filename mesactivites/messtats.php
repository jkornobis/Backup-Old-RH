<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mes Statistiques - Module Personnel";
require_once('menus.php');

echo $doctype.$menuprincipale.$menustats;
$nompage='messtats.php';

if($formation == true){/*
	$texteformation = '
<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'">
<fieldset class="formation"><legend><b>Formation:</b> <i>"Mes Statistiques"</i></legend>
<p>
Bienvenue sur la page <i>"Mes Statistiques"</i> de votre module Personnel.<br/>
Cette page vous permet de consulter vos statistiques par mois.
</p>
</fieldset></div>';
*/
}

if( $_GET['chmois'] == 13 ){
	$resultats=$connexion->query('
		SELECT	*
		FROM	`Event`, `UserObm`,  `EventCategory1`
		WHERE 	`event_usercreate` = `userobm_id`
		AND		`userobm_id` = "'.$utilisateur.'"
		AND		`event_category1_id` = `eventcategory1_id`
		AND 	`event_date` > "'.$anneeactuel.'-01-00"
		AND 	`event_date` < "'.$anneeactuel.'-12-33"
	');
	$titre = '<h2>Statistiques Projets/Axes de l\'année: <u>'.$anneeactuel.'</u></h2>'.$texteformation;
}else{
	$resultats=$connexion->query('
		SELECT	*
		FROM	`Event`, `UserObm`,  `EventCategory1`
		WHERE 	`event_usercreate` = `userobm_id`
		AND		`userobm_id` = "'.$utilisateur.'"
		AND		`event_category1_id` = `eventcategory1_id`
		AND 	`event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND 	`event_date` <= "'.$anneeactuel.'-'.$moisactuel.'-33"
	');
	$titre = '<h2>Statistiques Projets/Axes - '.$nomsession.' :<a href="messtats.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="messtats.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'">'.$flsuivant.'</a></h2>'.$texteformation;
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
	if( $ligne->eventcategory1_code >= 100 && $ligne->eventcategory1_code <= 199){ $totalaxe1 = $totalaxe1 + $ligne->event_duration; }
	if( $ligne->eventcategory1_code >= 200 && $ligne->eventcategory1_code <= 299){	$totalaxe2 = $totalaxe2 + $ligne->event_duration;	}
	if( $ligne->eventcategory1_code >= 300 && $ligne->eventcategory1_code <= 399){	$totalaxe3 = $totalaxe3 + $ligne->event_duration;	}
	if( $ligne->eventcategory1_code >= 400 && $ligne->eventcategory1_code <= 499){	$totalaxe4 = $totalaxe4 + $ligne->event_duration;	}
	if( $ligne->eventcategory1_code >= 500 && $ligne->eventcategory1_code <= 599){	$totalaxe5 = $totalaxe5 + $ligne->event_duration;	}
	if( $ligne->eventcategory1_code >= 600 && $ligne->eventcategory1_code <= 699){	$totalaxe6 = $totalaxe6 + $ligne->event_duration;	}
	if( $ligne->eventcategory1_code == 907 && $ligne->eventcategory1_code == 908){	$totalaxe6 = $totalaxe6 + $ligne->event_duration;	}
}
for( $i = 1; $i <= 999; $i++){
	if($totalprojet[$i] == NULL){
	}else{
		if( $i < 199) {
		echo '
		<tr>
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
			<td></td>
			<td></td>		
			<td></td>
			<td>'.($totalprojet[$i]/3600).'</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>';
		}
		if( $i > 500 && $i < 599) {
		echo '
		<tr>
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
			<td style="text-align:left;padding-left:10px;">'.$label[$i].'</td>
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
if ($totalglobal != ''){
$pourcentaxe1 = round(($totalaxe1*100)/$totalglobal);
$pourcentaxe2 = round(($totalaxe2*100)/$totalglobal);
$pourcentaxe3 = round(($totalaxe3*100)/$totalglobal);
$pourcentaxe4 = round(($totalaxe4*100)/$totalglobal);
$pourcentaxe5 = round(($totalaxe5*100)/$totalglobal);
$pourcentaxe6 = round(($totalaxe6*100)/$totalglobal);
$pourcentaxe100 = round(($totalglobal*100)/$totalglobal);

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
	<th>'.$pourcentaxe100.' %</th>
</tr>
';
echo '</table>';
echo(' <br/><img src="graph.php?ax1='.$pourcentaxe1.'&ax2='.$pourcentaxe2.'& ax3='.$pourcentaxe3.'&ax4='.$pourcentaxe4.'&ax5='.$pourcentaxe5.'&ax6='.$pourcentaxe6.'" alt="Mon graphique"/></div>');
}
echo '</div></div></body></html>';
?>
