<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mon Contrôle - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menucontrole;

$nompage = 'controle.php';

if (isset($_GET['suppr'])){
	
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	// on envoie la requête 
	$req = mysql_query('INSERT INTO mla.EventSauv (SELECT * FROM mla.Event WHERE `event_id` = "'.$_GET['suppr'].' " )') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());  
	$req2 = mysql_query('DELETE FROM `Event` WHERE  `event_id` = "'.$_GET['suppr'].' " ') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
}

if($formation == true){
/*
	$texteformation = '
	<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'">
	<fieldset class="formation"><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Contrôle des Saisies"</i></legend>
	<p>
	Bienvenue sur la page <i>"Contrôle des Saisies"</i> de votre module Personnel.<br/>
	Cette page vous permet de contrôler la saisie de vos événements afin de les comptabiliser dans votre réalisé.
	</p>
	</fieldset></div>';
*/
}

echo '<div id="content">
<h2>Contrôle du mois - '.$nomsession.' :<a href="controle.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'" >'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="controle.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a></h2>'.$texteformation.'
<h3 style="color:#A00;">Les événements avec le status répétitions doivent être modifier dans l\'agenda, afin de les comptabiliser correctement dans les statistiques !</h3>
<table><tr><th>Jour</th><th>Titre</th><th>Catégories</th><th>Status</th><th>Supprimer</th></tr>
';

$resultats=$connexion->query('
	SELECT *
	FROM  `Event` 
	LEFT OUTER JOIN  `EventCategory1`
	ON  `event_category1_id` =  `eventcategory1_id` 
	WHERE  `event_usercreate` =  "'.$utilisateur.'"
	AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-00"
	AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
	ORDER BY `event_date`
;');

$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() )
{
	$datebrute = $ligne->event_date;
	$repeat = $ligne->event_repeatkind;
	$eventid = $ligne->event_id;
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	if($datebrute > date($annee.'-04-01') && $datebrute < date($annee.'-11-01')){$h = $h+2;}else{$h++;}
	if($jour < 10){$jour = '0'.$jour;}
	$heure  = $ligne->event_duration/3600;
	list($heurefin, $mfin) = explode ('.', $heure);
	if ($h <10){$h = '0'.$h;}
	switch ($mfin){
		case NULL: $mfin = '00'; break;
		case '0': $mfin = '00'; break;
		case '25': $mfin = '15'; break;
		case '5': $mfin = '30'; break;
		case '75': $mfin = '45'; break;
	}
	if ($m <10){$m = $m.'0';}
	$heurefin = ($h+$heurefin);
	$mfin = ($m+$mfin);
	if ($mfin < 10){$mfin = $mfin.'0';}
	if ($mfin == '60'){$heurefin++; $mfin ='00';}
	if($heurefin < 10){$heurefin = '0'.$heurefin;}
	$date = '<b style="color:blue;">'.$jour.'</b> | <b>'.$h.':'.$m.' - '.$heurefin.':'.$mfin.'</b>';
	if ($ligne->eventcategory1_label == ''){
		echo '<tr><td style="text-align:left;width:160px;padding-left:3px;background-color:lightblue;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:10px;">'.$ligne->event_title.'</td><td style="text-align:center;"><b>! Il manque la catégorie à cette événement !</b></td><td><b> !!!</b></td><td><a href="controle.php?suppr='.$eventid.'&uti='.$utilisateur.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
	}else{
		if($repeat == 'none'){
		echo '<tr><td style="text-align:left;width:160px;padding-left:3px;background-color:lightblue;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td>Ok</td><td>
<a href="controle.php?suppr='.$eventid.'&uti='.$utilisateur.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
		}else{
		echo '<tr><td style="text-align:left;width:160px;padding-left:3px;background-color:lightblue;">'.$date.'</td><td style="text-align:left;width:360px;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td><b>Répétition !!!</b></td><td><a href="controle.php?suppr='.$eventid.'&uti='.$utilisateur.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette événement ?\'));">Supprimer</a></td></tr>';
		}	
	}
}
$resultats->closeCursor(); // on ferme le curseur des résultats
echo '</table><br/></div></body></html>';
?>
