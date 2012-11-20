<?php
require_once('config.php');
require_once('menus.php');
echo $doctype.$menuprincipale.$menufrais;
require_once('tests.php');

if(isset($_GET['refus'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	$req3 = mysql_query('
	 UPDATE  `mla`.`HeureSupp` SET  `heuresupp_statut` =  "non" WHERE  `HeureSupp`.`heuresupp_id` ="'.$_GET['refus'].'" LIMIT 1 ;
	') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if(isset($_GET['accepter'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	$req3 = mysql_query('
	 UPDATE  `mla`.`HeureSupp` SET  `heuresupp_statut` =  "ok" WHERE  `HeureSupp`.`heuresupp_id` ="'.$_GET['accepter'].'" LIMIT 1 ;
	') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if(isset($_GET['raz'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	$req3 = mysql_query('
	 UPDATE  `mla`.`HeureSupp` SET  `heuresupp_statut` =  "nontraite" WHERE  `HeureSupp`.`heuresupp_id` ="'.$_GET['raz'].'" LIMIT 1 ;
	') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if(isset($_POST['chuti'])){
	if ($_POST['chuti'] == 'globale'){
			$resultats=$connexion->query('
				SELECT *
				FROM `HeureSupp`, `UserObm`
				WHERE `heuresupp_userobmid` = `userobm_id`
				AND  `heuresupp_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
				AND `heuresupp_date` <= "'.$anneeactuel.'-'.$moisactuel.'-31"
				ORDER BY `heuresupp_date`
			');
			$titre = '<h2>Statistiques Globales Projets/Axes: <a href="conges.php?chmois=0'.($moisactuel-1).'&channee='.$anneeactuel.' ">'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="conges.php?chmois=0'.($moisactuel+1).'&channee='.$anneeactuel.'">'.$flsuivant.'</a> </h2>';
	}else{
			$resultats=$connexion->query('
				SELECT *
				FROM `HeureSupp`, `UserObm`
				WHERE `heuresupp_userobmid` = `userobm_id`
				AND  `heuresupp_userobmid` =  "'.$utilisateur.'"
				AND `heuresupp_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
				AND `heuresupp_date` < "'.$anneeactuel.'-'.$moisactuel.'-31"
				ORDER BY  `heuresupp_date` 
			');
			$titre = '<h2>Statistiques Pour <u>'.$_POST['chuti'].'</u> Projets/Axes: <span>'.$moisactuelmot.' '.$anneeactuel.'</span></h2>';
	}
}else{
	$resultats=$connexion->query('
		SELECT *
		FROM `HeureSupp`, `UserObm`
		WHERE `heuresupp_userobmid` = `userobm_id`
		AND  `heuresupp_date` >= "'.$anneeactuel.'-'.$moisactuel.'-01"
		AND `heuresupp_date` <= "'.$anneeactuel.'-'.$moisactuel.'-31"
		ORDER BY `heuresupp_date`
	');
	$titre = '<h2>Statistiques Globales Projets/Axes: <a href="heuresupp.php?chmois=0'.($moisactuel-1).'&channee='.$anneeactuel.' " >'.$flprecedent.'</a> <span>'.$moisactuelmot.' '.$anneeactuel.'</span> <a href="heuresupp.php?chmois=0'.($moisactuel+1).'&channee='.$anneeactuel.'">'.$flsuivant.'</a></h2>';
}
echo'<div id="contentfrais">
'.$titre.'
<table style="border:none">
	<tr>
		<th>Nom Prénom</th>
		<th>Type</th>
		<th>Demander le:</th>
		<th>Date</th>
		<th>Temps</th>
		<th>Raison</th>
		<th>Status</th>
		<th style="width:150px;">Actions</th>
	</tr>
';
$resultats->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultats->fetch() ) {
	if ($ligne->heuresupp_type == "1"){$type= 'Heures Supplémentaires';}else{$type= 'Heures à Rattraper';}
	$datebrute = $ligne->heuresupp_date;
	$date = explode("-", $datebrute);
	$heuresupp_date = $date['2'].'/'.$date['1'].'/'.$date['0'];

	$datebruteid = $ligne->heuresupp_dateid;
	$dateid = explode("-", $datebruteid);
	$heuresupp_dateid = $dateid['2'].'/'.$dateid['1'].'/'.$dateid['0'];

	$temps = $ligne->heuresupp_temps;

	switch ($ligne->heuresupp_statut){
		case 'ok':
		$status = '<img src="../img/valider.png" style="width:40px;" title="Accepter"/>';
		$styles = 'color:white;font-size:12px;background-color:#66CC66;';
		$actions = '<a href="heuresupp.php?accepter='.$ligne->heuresupp_id.'"><img src="../img/valider.png" style="width:40px;" title="Accepter"/></a><a href="heuresupp.php?refus='.$ligne->heuresupp_id.'"><img src="../img/supprimer.png" style="width:40px;" title="Refuser"/></a><a href="heuresupp.php?raz='.$ligne->heuresupp_id.'"><img src="../img/editer.png" style="width:40px;" title="Remmettre le Statut Non Traité"/></a>'; 
		break;
		case 'nontraite':
		$status = '<img src="../img/attente.gif" style="width:40px;" title="Non Traité"/>';
		$styles = 'color:red;font-size:12px;background-color:white;';
		$actions = '<a href="heuresupp.php?accepter='.$ligne->heuresupp_id.'"><img src="../img/valider.png" style="width:40px;" title="Accepter"/></a><a href="heuresupp.php?refus='.$ligne->heuresupp_id.'"><img src="../img/supprimer.png" style="width:40px;" title="Refuser"/></a><a href="heuresupp.php?raz='.$ligne->heuresupp_id.'"><img src="../img/editer.png" style="width:40px;" title="Remmettre le Statut Non Traité"/></a>'; 
		break;
		case 'non':
		$status = '<img src="../img/supprimer.png" style="width:40px;" title="Refuser"/>';
		$styles = 'color:white;font-size:12px;background-color:orange;';
		$actions = '<a href="heuresupp.php?accepter='.$ligne->heuresupp_id.'"><img src="../img/valider.png" style="width:40px;" title="Accepter"/></a><a href="heuresupp.php?refus='.$ligne->heuresupp_id.'"><img src="../img/supprimer.png" style="width:40px;" title="Refuser"/></a><a href="heuresupp.php?raz='.$ligne->heuresupp_id.'"><img src="../img/editer.png" style="width:40px;" title="Remmettre le Statut Non Traité"/></a>'; 
		break;
	}

	if ($ligne->userobm_delegation_target == $_SESSION['login'] || $_SESSION['login'] == "KORNOBIS Jérémie" || $_SESSION['login'] == "FIERRARD Virginie"|| $_SESSION['login'] == "PECOURT Antoine" || $_SESSION['login'] == "BOUTON Michael" ){
	echo '
		<tr>
			<td>'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</td>
			<td>'.$type.'</td>
			<td>'.$heuresupp_dateid.'</td>
			<td>'.$heuresupp_date.'</td>
			<td> '.$temps.'</td>
			<td>'.$ligne->heuresupp_raison.'</td>
			<td style="'.$styles.'">'.$status.'</td>
			<td>'.$actions.'</td>
		</tr>
	';
	}
}
echo '</table>';
echo '</div></div></body></html>';
?>
