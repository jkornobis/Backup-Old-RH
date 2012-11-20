<?php
require_once('config.php');
$titlepage = "Résumés - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuetat;
require_once('tests.php');

$nompage = 'etat.php';
/*///////////////////////////////////////////////////////////////////
						Affichage infos 
///////////////////////////////////////////////////////////////////*/

	echo '
	<div id="content"><h2>Page de résumé d\'Administration: '.date("d/m/Y").'</h2>'.$texteformation.'
	<div style="float:left;width:585px;">
		<fieldset style="width:550px;"><legend>Derniers congés a valider:</legend><p>
	';
	$congesetat=$connexion->query('
		SELECT *
		FROM `Event`, `UserObm`,  `EventCategory1`
		WHERE `event_usercreate` = `userobm_id`
		AND `event_category1_id` = `eventcategory1_id`
		AND `eventcategory1_code` = 901
		AND `event_timecreate` >= "'.$anneeactuel.'-01-01"
		AND `event_timecreate` <= "'.$anneeactuel.'-12-31"
		AND (event_description = "nontraite" OR event_description = "")
		AND userobm_delegation_target = "'.$_SESSION['login'].'"
		ORDER BY `event_timecreate` DESC
		LIMIT 12
	;');
	echo '<table style="width:560px;border:none;"><tr style="width:550px;border:none;">';
	$congesetat->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $congesetat->fetch()) {
		$datebrute= $ligne->event_date;
		list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
		$h = $h + 2;
		if($jour < 10){$jour = '0'.$jour;}
		$date = '<b>'.$jour.' '.$moisactuelmot[$mois].' '.$annee.'</b>';

		$status = '<img src="../img/attente.gif" style="width:25px;" title="Non Traité"/>';
		$styles = 'color:red;font-size:12px;background-color:white;';
		$actions = '<span>
				<a href="congesuser.php?idconge='.$ligne->event_id.'&valider=1&userid='.$ligne->userobm_id.'"><img src="../img/valider.png" style="width:25px;" title="Accepter"/></a>
				<a href="congesuser.php?idconge='.$ligne->event_id.'&refus=1&userid='.$ligne->userobm_id.'"><img src="../img/supprimer.png" style="width:25px;" title="Refuser"/></a></span>';
			echo '
				<td style="width:150px;font-weight:bold;border:none;">'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</td>
				<td style="border:none;font-weight:bold;width:150px;">'.$ligne->eventcategory1_label.'</td>
				<td style="width:150px;border:none;">'.$date.'</td>
				<td style="width:61px;border:none;"> '.$actions.'</td>
		</tr>
		<tr>';
	}
	echo '<td style="border:none;padding-bottom:10px;" colspan="4">Pour la suite :</td></tr></table>';
	echo '<a href="conges.php" style="padding:4px;">Aller sur la page des Congés pour validé la suite</a>';
	echo '</p></fieldset></div><div>';

/*///////////////////////////////////////////////////////////////////
						Affichage Frais 
///////////////////////////////////////////////////////////////////*/

	echo '<fieldset style="width:550px;"><legend>Derniers Frais demandés a valider:</legend>';
		$fraisetat=$connexion->query('
			SELECT *
			FROM `EventCategory1`, `FraisEvent`, `FraisDepVoiture`,`UserObm`
			WHERE `fraisevent_catcode` = `eventcategory1_code`
			AND `fraisevent_userobmid` = `userobm_id`
			AND `fraisevent_cv` = `fraisdepvoiture_tarif`
			AND `fraisevent_date` >= "'.$anneeactuel.'-'.$moisactuel.'-'.(date(d)-4).'"
			AND `fraisevent_date` <= "'.$anneeactuel.'-'.$moisactuel.'-'.date(d).'"
			ORDER BY `fraisevent_date` DESC
			LIMIT 7
			;');
		$fraisetat->setFetchMode(PDO::FETCH_OBJ);
		while($ligne = $fraisetat->fetch()) {
			$datebrute = $ligne->fraisevent_date;
			$date = explode("-", $datebrute);
			$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
			 echo '<p><b>'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</b> | '.$fraisevent_date.' | '.$ligne->fraisevent_lieu.' | '.$ligne->fraisevent_raison.' </p>';
		}


	echo '<br/><a href="frais.php" style="padding:4px;">Aller sur la page des Frais</a></fieldset>';
/*///////////////////////////////////////////////////////////////////
						Affichage Dernières erreur de remplissage 
///////////////////////////////////////////////////////////////////*/
echo '<br/><fieldset style="width:550px;"><legend>Dernières Erreurs des Utilisateurs</legend>';
	$controle=$connexion->query('
		SELECT *
		FROM `UserObm` , `Event`
		LEFT OUTER JOIN `EventCategory1` ON `event_category1_id` = `eventcategory1_id`
		WHERE `eventcategory1_code` = ""
		AND `event_usercreate` = `userobm_id`
		AND `event_date` >= "'.$anneeactuel.'-01-01"
		AND `event_date` < "'.$anneeactuel.'-12-33"
		ORDER BY `event_date`
		LIMIT 7
	');
	$controle->setFetchMode(PDO::FETCH_OBJ);
	while( $ligne = $controle->fetch() ) // on récupère la liste des membres
	{
		$datebrute = $ligne->event_date;
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
		if ($mois <10){$mois = '0'.$mois;}
		$date = '<b style="color:blue;">'.$jour.'/'.$mois.'/'.$annee.'</b> | <b>'.$h.':'.$m.'-'.$heurefin.':'.$mfin.'</b>';

		$infos= $infos.'<p><b>'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</b> | '.$date.' | '.$ligne->event_title.' '.$ligne-> eventcategory1_code.' </p>';
	}
	if (isset($infos)){
		echo $infos;
	}else{
		echo '<p><b style="color:green;font-size:17px;">Il n\'y a actuellement pas d\'événement sans catégories</b></p>';
	}
	echo '</fieldset>';

	echo '</div>
	</div>
	</body>
	</html>
	';
?>
