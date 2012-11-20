<?php
require_once('config.php');
$titlepage = "Rapports - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menurapport;
require_once('tests.php');

echo '<div id="content"> <h2>Rapport Utilisateurs du <a href="rapportuser.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'  " >'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span> <a href="rapportuser.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.' ">'.$flsuivant.'</a></h2><table>
<tr><th>Utilisateurs</th><th>Axe 1</th><th>Axe 2</th><th>Axe 3</th><th>Axe 4</th><th>Axe 5</th><th>Axe 6</th><th>Axe 9</th><th>Total (ch)</th></tr>';

$resultats=$connexion->query("SELECT * FROM UserObm ORDER BY userobm_lastname ASC");
$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() )
{
	$userid = $ligne->userobm_id;
	$usernom = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
  $utilisateur[$userid] = $usernom;
	$utilisateurstatut = $ligne->userobm_statut;
	$userresultats=$connexion->query('
		SELECT *
		FROM `Event`, `UserObm`,  `EventCategory1`
		WHERE `event_usercreate` = `userobm_id`
		AND `event_category1_id` = `eventcategory1_id`
		AND `userobm_id` = "'.$userid.'"
		AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
		AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-31"
	');
	$userresultats->setFetchMode(PDO::FETCH_OBJ);
	while( $ligne = $userresultats->fetch() ) {
		if( $ligne->eventcategory1_code >= 100 && $ligne->eventcategory1_code <= 199){ 
			$totalaxe1[$userid] = $totalaxe1[$userid] + ($ligne->event_duration/3600);
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 200 && $ligne->eventcategory1_code <= 299){
			$totalaxe2[$userid] = $totalaxe2[$userid] + ($ligne->event_duration/3600); 
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 300 && $ligne->eventcategory1_code <= 399){
			$totalaxe3[$userid] = $totalaxe3[$userid] + ($ligne->event_duration/3600); 
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 400 && $ligne->eventcategory1_code <= 499){
			$totalaxe4[$userid] = $totalaxe4[$userid] + ($ligne->event_duration/3600);
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 500 && $ligne->eventcategory1_code <= 599){
			$totalaxe5[$userid] = $totalaxe5[$userid] + ($ligne->event_duration/3600); 
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 600 && $ligne->eventcategory1_code <= 699){
			$totalaxe6[$userid] = $totalaxe6[$userid] + ($ligne->event_duration/3600);
			$totalglobal = $totalglobal + ($ligne->event_duration/3600);
			$totalglobaluser[$userid] = $totalglobaluser[$userid] + ($ligne->event_duration/3600);
		}
		if( $ligne->eventcategory1_code >= 900 && $ligne->eventcategory1_code <= 999){	$totalaxe9[$userid] = $totalaxe9[$userid] + ($ligne->event_duration/3600); }			
	}
	if ( $totalglobaluser[$userid] == 0 ){
		$style[$userid] = 'background-color:orange;';
		if($utilisateur[$userid] == NULL || $utilisateur[$userid] == 'admin admin' || $utilisateur[$userid] == 'Admin Lastname Firstname' || $utilisateur[$userid] == 'MLA MLA' || $utilisateur[$userid] == 'Secrétaires Secrétaires' || $utilisateurstatut == "non"){
			}else{
			echo '<tr style="'.$style[$userid].'"><td>[ '.$userid.' ] '.$utilisateur[$userid].' - '.$utilisateurstatut.'</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
			$mauvaisuser = $mauvaisuser + 1;
		}
	}else{
			$style[$userid]= 'background-color:lightblue;';
			$usernormaux = $usernormaux + 1;
			echo '<tr><td style="'.$style[$userid].'">'.$utilisateur[$userid].'</td><td>'.$totalaxe1[$userid].'</td><td>'.$totalaxe2[$userid].'</td><td>'.$totalaxe3[$userid].'</td><td>'.$totalaxe4[$userid].'</td><td>'.$totalaxe5[$userid].'</td><td>'.$totalaxe6[$userid].'</td><td>'.$totalaxe9[$userid].'</td><th>'.$totalglobaluser[$userid].'</th></tr>';
		}
}
echo '<tr><th>Total</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>'.$totalglobal.' ch</th></tr>';
echo '</table><br/>
<fieldset style="font-size:17px;color:#000;font:Verdana;"><legend>Récapitulatifs:</legend>Sur <b>'.($usernormaux+$mauvaisuser).'</b> utilisateurs, <b>'.$mauvaisuser.'</b> n\'ont pas remplit leurs agenda (ou commit une erreur).<br/>Cela représente <b>'.round(($mauvaisuser*100/($usernormaux+$mauvaisuser)),2).' %</b> des effectifs<br/> Soit un taux de <b>'.round(($usernormaux*100/($usernormaux+$mauvaisuser)),2).' %</b> d\'utilisation</fieldset></div></body></html>';
$resultats->closeCursor();
?>
