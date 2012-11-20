<?php
session_start();
if (isset ($_POST['mdp'])){
$_SESSION['mdp'] = $_POST['mdp'];
}
if( $_SESSION['mdp']  == 'mla62'
 || $_SESSION['login'] == "KORNOBIS Jérémie" || $_SESSION['login'] == "FIERRARD Virginie"|| $_SESSION['login'] == "PECOURT Antoine" || $_SESSION['login'] == "BOUTON Michael"
 || $_SESSION['login'] == "VANWALESCAPPEL Vincent" 
 || $_SESSION['login'] == "HERMANT Laurence" 
 || $_SESSION['login'] == "KORNOBIS Jérémie"
 || $_SESSION['login'] == "WALBROU Bernard" || $_SESSION['login'] == "HERMANT Jean Marc" || $_SESSION['login'] == "admin admin"

){
	
	$PARAM_hote='localhost'; // le chemin vers le serveur
	$PARAM_nom_bd='mla'; // le nom de votre base de données
	$PARAM_utilisateur='mlaobmmysql'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe='H864kE5XB2'; // mot de passe de l'utilisateur pour se connecter

	$moisactuel = date("m");
	$moisprochain = date("m") +1;
	$anneeactuel = date("Y");
	$jouractuel = date("d");

	switch ($moisactuel){
		case '01': 	$moisactuelmot = 'Janvier'; 		break;
		case '02': 	$moisactuelmot = 'Février';			break;
		case '03': 	$moisactuelmot = 'Mars';				break;
		case '04': 	$moisactuelmot = 'Avril'; 			break;
		case '05': 	$moisactuelmot = 'Mai'; 				break;
		case '06': 	$moisactuelmot = 'Juin'; 				break;
		case '07': 	$moisactuelmot = 'Juillet'; 		break;
		case '08': 	$moisactuelmot = 'Aout'; 				break;
		case '09': 	$moisactuelmot = 'Septembre'; 	break;
		case '10': 	$moisactuelmot = 'Octobre'; 		break;
		case '11': 	$moisactuelmot = 'Novembre'; 		break;
		case '12': 	$moisactuelmot = 'Décembre'; 		break;
	}

	try{
		$connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	}catch(Exception $e){
		echo 'Une erreur est survenue !';
		die();
	}
	$form1data = $connexion->query('
		SELECT *
		FROM `UserObm`
		ORDER BY `userobm_lastname` ASC
	;');
	$testtuser = $connexion->query('
		SELECT  `event_usercreate` ,  `event_date` ,  `event_duration` ,  `event_category1_id` ,  `eventcategory1_code` 
	FROM  `Event` ,  `EventCategory1` 
	WHERE  `event_usercreate` =  "85"
	AND  `event_category1_id` =  `eventcategory1_id` 
	AND  `event_date` >=  "2011-3-1"
	AND  `event_date` <  "2011-3-2"
	;');
	$form2data = $connexion->query('
		SELECT *
		FROM `EventCategory1`
		ORDER BY `EventCategory1`.`eventcategory1_code` ASC
	;');
}else{
	echo'
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
		<head><title>OBM - Module Administrateur</title>
					<style>
					body{color:white;margin:0;padding:0;}
					#formulaire{color:black;width:800px;margin:0 auto;padding-left:10px;text-align:center;}
					#formulaire fieldset{margin:0 auto;}
					</style>
		</head>
		<body>
		<div id="formulaire">
			<p style="font-size:12px;"><a href="mailto:jeremie.kornobis@mlartois.fr" style="color:#05F;text-decoration:none;">jeremie.kornobis@mlartois.fr</a></p>
			<p style="color:#555;">Jkornobis - Version 1.6</p>
			<img src="/images/themes/default/images/home_obm.png" alt="logo"/><br/>
			<fieldset style="width:450px;"><legend><h2 style="color:#333;"> Espace Administrateur : </h2></legend>
			<form method="POST" action="#" style="display:inline;">
				Mot de Passe: <input type="password" size="20" maxlength="20" name="mdp"/><br/>
				Entrer dans le module administrateur: <button type="submit">Valider</button>
			</form>
			</fieldset>
		</div>
		<html>
	';
}
?>
