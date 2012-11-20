<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mon Réalisé - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menurealise;

if (isset($_GET['suppr'])){
	
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	// on envoie la requête 
	$req = mysql_query('INSERT INTO mla.EventSauv (SELECT * FROM mla.Event WHERE `event_id` = "'.$_GET['suppr'].' " )') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());  

	$req2 = mysql_query('DELETE FROM `Event` WHERE  `event_id` = "'.$_GET['suppr'].' " ') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
}

echo '<div id="content">
<h2>Contrôle du: <u>'.$jour1.'/'.$moisactuel.'/'.$anneeactuel.'</u> </h2>
<h4 style="color:red;">Les événements avec le status répétitions doivent être modifier dans l\'agenda, afin de les comptabiliser correctement dans les statistiques !</h4>
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
		case '00': $mfin = '00'; break;
		case '25': $mfin = '15'; break;
		case '05': $mfin = '30'; break;
		case '75': $mfin = '45'; break;
		case '60': $modif = 1; $mfin = '00'; break;
	}
	if ($m <10){$m = $m.'0';}
	$heurefin = ($h+$heurefin);
	$mfin = ($m+$mfin);
	if($heurefin < 10){$heurefin = '0'.$heurefin;}
	if ($modif == 1){ $date = '<b>'.$jour.'</b> - <b>'.$h.':'.$m.'</b> à <b>'.$heurefin++.':'.$mfin.'</b>';}else{$date = '<b>'.$jour.'</b> - <b>'.$h.':'.$m.'</b> à <b>'.$heurefin.':'.$mfin.'</b>';}
	if ($ligne->eventcategory1_label == ''){
		echo '<tr style="border:1px solid black;"><td style="text-align:left;width:180px;padding-left:5px;border:1px solid black;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:10px;">'.$ligne->event_title.'</td><td style="text-align:center;"><b>! Il manque la catégorie à cette événement !</b></td><td><b> !!!</b></td><td><a href="controle.php?suppr='.$eventid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
	}else{
		if($repeat == 'none'){
		echo '<tr><td style="text-align:left;width:180px;padding-left:5px;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td>Ok</td><td>
<a href="controle.php?suppr='.$eventid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
		}else{
		echo '<tr><td style="text-align:left;width:180px;padding-left:5px;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td><b>Répétition !!!</b></td><td><a href="controle.php?suppr='.$eventid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
		}	
	}
}
$resultats->closeCursor(); // on ferme le curseur des résultats
echo '</table></div></body></html>';
?>
