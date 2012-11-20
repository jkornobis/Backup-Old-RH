<?php
require_once('config.php');
$titlepage = "Acceuil Administration - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menustats;
echo '<div id="content"><h2>Page d\'erreurs dans le calendrier: '.date("d/m/Y H\Hi : s").'secondes</h2>'.$texteformation ;

$resultats=$connexion->query('
	SELECT *
	FROM `Event`, `UserObm`,  `EventCategory1`
	WHERE  `event_usercreate` = `userobm_id`
	AND `event_category1_id` = `eventcategory1_id`
	ORDER BY  `event_date` 
');
$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
{
	$timeupdate = $ligne->event_timeupdate;
	$timecreate = $ligne->event_timecreate;
	$eventdate = $ligne->event_date;
	$usernom = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;

	list($timeupdate_annee,$timeupdate_mois,$timeupdate_jour,$timeupdate_h,$timeupdate_m,$timeupdate_s)
	=sscanf($timeupdate,"%d-%d-%d %d:%d:%d");

	list($timecreate_annee,$timecreate_mois,$timecreate_jour,$timecreate_h,$timecreate_m,$timecreate_s)
	=sscanf($timecreate,"%d-%d-%d %d:%d:%d");

	list($eventdate_annee,$eventdate_mois,$eventdate_jour,$eventdate_h,$eventdate_m,$eventdate_s)
	=sscanf($eventdate,"%d-%d-%d %d:%d:%d");

	if($timeupdate_m >= ($eventdate_m+2 )){
		echo '<p>'.$usernom.' | '.$timeupdate.' | '.$eventdate.'</p>';
	}
}

echo '</div></body></html>';
?>
