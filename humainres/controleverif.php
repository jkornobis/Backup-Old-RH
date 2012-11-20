<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menurapport;
require_once('tests.php');

$resultats=$connexion->query('
	SELECT  `event_usercreate` ,  `event_date` ,  `event_title` ,  `event_duration` ,  `event_category1_id` ,  `eventcategory1_code` ,  `eventcategory1_label` ,  `event_repeatkind` , `event_id`
	FROM  `Event` 
	LEFT OUTER JOIN  `EventCategory1`
	ON  `event_category1_id` =  `eventcategory1_id` 
	WHERE  `event_id` =  "'.$eventid.'"
');
echo '
<div id="content">
<h1>! Page de Suppression !</h1>
<h4>Cette page vous permet de vérifier une dernière fois les informations avant la suppression définitive de l\'événement.</h4><br/>
';
$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
{
	$datebrute = $ligne->event_date;
	$eventid = $ligne->event_id;
	$repeat = $ligne->event_repeatkind;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	$h++;
	if($jour < 10){$jour = '0'.$jour;}
	$heure  = $ligne->event_duration/3600;
	list($heurefin, $mfin) = explode ('.', $heure);
	if ($h <10){$h = '0'.$h;}
	switch ($mfin){
		case NULL: $mfin = '00'; break;
		case '25': $mfin = '15'; break;
		case '5': $mfin = '30'; break;
		case '75': $mfin = '45'; break;
	}
	if ($m <10){$m = $m.'0';}
	if ($mois <10){$mois = '0'.$mois;}
	$heurefin = ($h+$heurefin);
	$mfin = ($m+$mfin);
	if($heurefin < 10){$heurefin = '0'.$heurefin;}
	$date = '<b>'.$jour.'/'.$mois.'/'.$annee.'</b> - <b>'.$h.':'.$m.'</b> à <b>'.$heurefin.':'.$mfin.'</b>';

	echo 'Nom de l\'événement : <b>'.$ligne->event_title.'</b><br/><br/>Catégorie: <b>'.$ligne->eventcategory1_label.'</b><br/><br/>Date: '.$date.'<br/><br/>Durée: <b>'.$heure.' cH</b><br/><br/> Répétition: ';
	if($repeat == 'none'){
		echo '<b> Non<b/>';	
	}else{
		echo '<b> Oui !</b>';	
	}
}
echo '<br/><br/><br/><fieldset><legend>Formulaire de validation de suppression: </legend><form method="POST" action="controlesupprimer.php"><br/>
	<input type="hidden" name="uti" value="'.$utilisateur.'"><input type="hidden" name="eventid" value="'.$eventid.'">
	Cocher le bouton: <input type="checkbox" name="accepter" value="ok"/> Pour valider la suppression<br/><br/>
	L\'événement sera déplacé dans une table de sauvegarde par sécurité (ne pas informer les utilisateurs par sécurité d\'utilisation).
<br/><br/><input type="submit" value="Valider la Suppression de l\'événement"> 
</form></fieldset>';
echo '</div></body></html>';
?>
