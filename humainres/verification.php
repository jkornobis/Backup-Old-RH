<?php
require_once('config.php');
$titlepage = "Réalisés - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menurealise;
require_once ('tests.php');

$realise=$connexion->query('
		SELECT *
		FROM `Event`, `EventCategory1`, `UserObm`
		WHERE `event_usercreate` = `userobm_id`
		AND `event_category1_id` = `eventcategory1_id`
		AND `event_date` >= "'.$anneeactuel.'-01-01"
		AND `event_date` < "'.$anneeactuel.'-12-31";
');
$realise->setFetchMode(PDO::FETCH_OBJ); 
while( $ligne = $realise->fetch() )
{
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($ligne->event_date,"%d-%d-%d %d:%d:%d");
	if($h >= "18" || $h <= "5"){
		$warning .= '<p style="color:#A00;font-size:14px;font-weight:bold;"> '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.': <i>"'.	$ligne->event_title.'"</i> du <b>'.$jour.'/'.$mois.'/'.$annee.'</b> à <b>'.($h+1).'H'.$m.'min</b> ne fais pas partie 	des horaires habituelles de travail.</p>';
	}
}
echo '<div id="content">';
echo $warning;
echo '</div></body></html>';
?>
