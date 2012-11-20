<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menurealise;
require_once('tests.php');

echo '<div id="content">
<h2>Contrôle du: <u>'.$jour1.' '.$moisactuelmot.' '.$anneeactuel.'</u> </h2>
<table><tr><th>Jour</th><th>Titre</th><th>Catégories</th><th>Status</th><th>Supprimer</th></tr>
';
$resultats=$connexion->query('
	SELECT  `event_usercreate` ,  `event_date` ,  `event_title` ,  `event_duration` ,  `event_category1_id` ,  `eventcategory1_code` ,  `eventcategory1_label` ,  `event_repeatkind` , `event_id`
	FROM  `Event` 
	LEFT OUTER JOIN  `EventCategory1`
	ON  `event_category1_id` =  `eventcategory1_id` 
	WHERE  `event_usercreate` =  "'.$utilisateur.'"
	AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-'.$jour1.'"
	AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-'.$jour2.'"
	ORDER BY  `event_date` 
');
$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
while( $ligne = $resultats->fetch() ) // on récupère la liste des membres
{
	$datebrute = $ligne->event_date;
	$repeat = $ligne->event_repeatkind;
	$eventid = $ligne->event_id;
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
	$heurefin = ($h+$heurefin);
	$mfin = ($m+$mfin);
	if($heurefin < 10){$heurefin = '0'.$heurefin;}
	$date = '<b>'.$jour.'</b> - <b>'.$h.':'.$m.'</b> à <b>'.$heurefin.':'.$mfin.'</b>';
	if ($ligne->eventcategory1_label == ''){
		echo '<tr style="border:1px solid black;"><td style="text-align:left;width:180px;padding-left:5px;border:1px solid black;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:10px;">'.$ligne->event_title.'</td><td style="text-align:center;"><b>! Il manque la catégorie à cette événement !</b></td><td><b> !!!</b></td><td><a href="controleverif.php?eventid='.$eventid.'&uti='.$utilisateur.'">Supprimer</a></td></tr>';
	}else{
		if($repeat == 'none'){
		echo '<tr><td style="text-align:left;width:180px;padding-left:5px;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td>Ok</td><td>
<a href="controleverif.php?eventid='.$eventid.'&uti='.$utilisateur.'" style="color:red;font-weight:bold;background-color:white;margin:0; padding:0;">Supprimer</a></td></tr>';
		}else{
		echo '<tr><td style="text-align:left;width:180px;padding-left:5px;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td><b>Répétition !!!</b></td><td><a href="controleverif.php?eventid='.$eventid.'&uti='.$utilisateur.'" style="color:red;font-weight:bold;background-color:white;margin:0; padding:0;">Supprimer</a></td></tr>';
		}	
	}
}
$resultats->closeCursor(); // on ferme le curseur des résultats
echo '</table><br/></div></body></html>';
?>
