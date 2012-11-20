<?php
require_once('config.php');
$titlepage = "Contrôle - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menucontrole;
require_once('tests.php');

if(isset($_POST['chmois']) && isset($_POST['channee']) && isset($_POST['chuti'])){
	$moisactuel = $_POST['chmois'];
	$anneeactuel = $_POST['channee'];
	$utilisateur = $_POST['chuti'];
	switch ($moisactuel){
		case '01': $moisactuelmot = 'Janvier'; break;
		case '02': $moisactuelmot = 'Février'; break;
		case '03': $moisactuelmot = 'Mars'; break;
		case '04': $moisactuelmot = 'Avril'; break;
		case '05': $moisactuelmot = 'Mai'; break;
		case '06': $moisactuelmot = 'Juin'; break;
		case '07': $moisactuelmot = 'Juillet'; break;
		case '08': $moisactuelmot = 'Aout'; break;
		case '09': $moisactuelmot = 'Septembre'; break;
		case '10': $moisactuelmot = 'Octobre'; break;
		case '11': $moisactuelmot = 'Novembre'; break;
		case '12': $moisactuelmot = 'Décembre'; break;
	}
	if(isset($_GET['chmois']) && isset($_GET['chuti']) ){
		$resultats=$connexion->query('
				SELECT *
				FROM `Event`, `UserObm`,  `EventCategory1`
				WHERE  `event_usercreate` = `userobm_id`
				AND `userobm_login` =  "'.$_GET['chuti'].'"
				AND `event_category1_id` = `eventcategory1_id`
				AND  `event_date` >= "'.$anneeactuel.'-'.$_GET['chmois'].'-01"
				AND `event_date` <= "'.$anneeactuel.'-'.$_GET['chmois'].'-31"
				ORDER BY  `event_date` 
	');
	}else{
		$resultats=$connexion->query('
				SELECT *
				FROM `Event`, `UserObm`,  `EventCategory1`
				WHERE  `event_usercreate` = `userobm_id`
				AND `userobm_login` =  "'.$_POST['chuti'].'"
				AND `event_category1_id` = `eventcategory1_id`
				AND  `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
				AND `event_date` <= "'.$anneeactuel.'-'.$moisactuel.'-31"
				ORDER BY  `event_date` 
	');
	}
	$titre = '<h2>Contrôle pour <u>'.$_POST['chuti'].'</u>:<u>'.$moisactuelmot.' '.$anneeactuel.'</u></h2>';

	echo '<div id="content">'.$titre.'
	<h4>Les événements avec le status répétitions doivent être modifier dans l\'agenda, afin de les comptabiliser correctement dans les statistiques !</h4>
	<table><tr><th>Event_id</th><th style="width:230px;text-align left;">Jour</th><th>Titre</th><th style="width:300px;">Catégories</th><th style="width:80px;">Status</th><th>Supprimer</th></tr>
	';

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
		$date = '<b>'.$jour.'/'.$mois.'/'.$annee.'</b> - <b>'.$h.':'.$m.'</b> à <b>'.$heurefin.':'.$mfin.'</b>';
		if($repeat == 'none'){

			if ($ligne->userobm_delegation_target == $_SESSION['login'] || $_SESSION['login'] == "KORNOBIS Jérémie" || $_SESSION['login'] == "FIERRARD Virginie"|| $_SESSION['login'] == "PECOURT Antoine" || $_SESSION['login'] == "BOUTON Michael" ){

			echo '<tr><td>'.$ligne->event_id.'</td><td>'.$date.'</td><td style="text-align:left;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td style="width:80px;">Ok</td><td>
	<a href="controleverif.php?eventid='.$eventid.'&uti='.$utilisateur.'">Supprimer</a></td></tr>';
			}
		}else{

			if ($ligne->userobm_delegation_target == $_SESSION['login'] || $_SESSION['login'] == "KORNOBIS Jérémie" || $_SESSION['login'] == "FIERRARD Virginie"|| $_SESSION['login'] == "PECOURT Antoine" || $_SESSION['login'] == "BOUTON Michael" ){

			echo '<tr><td>'.$ligne->userobm_id.'</td><td>'.$date.'</td><td style="text-align:left;padding-left:5px;">'.$ligne->event_title.'</td><td style="text-align:left;padding-left:5px;width:300px;">'.$ligne->eventcategory1_label.'</td><td style="width:180px;"><b>Répétition !!!</b></td><td><a href="controleverif.php?eventid='.$eventid.'&uti='.$utilisateur.'">Supprimer</a></td></tr>';
			}
		}
	}
	$resultats->closeCursor(); //
	echo '</table><br/></div></body></html>';

}else{
	echo '<div id="content"><h2 style="color:red;"> ! Choisir une personne et un Mois et une Année dans le sous-menu !</h2>'; 
	echo '</table><br/></div></body></html>';
}
?>
