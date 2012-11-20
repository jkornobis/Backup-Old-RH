<?php
require_once('config.php');
require_once('tests.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menuresumer;

$globalstats = $connexion->query('
	SELECT `userobm_lastname`, `userobm_firstname`, `userobm_id`,`userobm_login`,
	`userobm_congesnormale`, `temps_hpj`, `userobm_congesrc`, `userobm_congesmaladie`,
	`userobm_congesrrtnt`, `userobm_congesexcep`
	FROM `UserObm`
	WHERE `userobm_id` = "'.$utilisateur.'"
;');
While($donnees = $globalstats->fetch()){
	$usernom = $donnees['userobm_lastname']." ".$donnees['userobm_firstname'];
	$congesnormale = $donnees['userobm_congesnormale']/24;
	$congesexcep = $donnees['userobm_congesexcep']/24;
	$congesrrtnt = $donnees['userobm_congesrrtnt']/24;
	$congesrc = $donnees['userobm_congesrc'];
	$congesmaladie = $donnees['userobm_congesmaladie'];
	$tempshpj = $donnees['temps_hpj'];
}$dbd = null;
echo'<br/><div id="content"><fieldset><legend style="font-size:18px;">Rapport de l\'utilisateur '.$usernom.'</legend>';
echo '<h3>Congés: </h3>';
echo 'Jours de congés normaux restant: <b>'.$congesnormale.'</b> jours à prendre avant le '.$datebutoirconges.'<br/><br/>';
echo 'Jours exceptionnel prit: <b>'.$congesexcep.'</b> jours<br/><br/>';
echo 'Jours de RTT prit: <b>'.$congesrrtnt.'</b> jours<br/><br/>';
echo 'Jours de RC prit: <b>'.$congesrc.'</b> jours<br/>';
echo '<h3>Autre: </h3>';
echo 'Temps horaire à la semaine: <b>'.$tempshpj.'</b> Heures<br/>';

echo '</fieldset></div>';
?>
