<?php
require_once('config.php');
$titlepage = "Information sur un utilisateur - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuuserinfos;
require_once('tests.php');
/*///////////////////////////////////////////////////////////////////
			Traitement Formulaire
///////////////////////////////////////////////////////////////////*/
if (isset($_POST['button'])){
	if (isset($_POST['accepter'])){

		if(isset($_POST['cdannee']) && isset($_POST['cdmois']) && isset($_POST['cdjour'])){
			if ($_POST['cdjour'] <10){$jour = '0'.$_POST['cdjour'];}else{$jour = $_POST['cdjour'];}
			$contratdebut = $_POST['cdannee'].'-'.$_POST['cdmois'].'-'.$jour;
		}else{$contratdebut = null;}
		if(isset($_POST['cfannee']) && isset($_POST['cfmois']) && isset($_POST['cfjour'])){
			if ($_POST['cdjour'] <10){$jour = '0'.$_POST['cfjour'];}else{$jour = $_POST['cfjour'];}
			$contratfin = $_POST['cfannee'].'-'.$_POST['cfmois'].'-'.$jour;
		}else{$contratfin = null;}

		$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('
			UPDATE  `UserObm`, `UserObmRH` 
			SET  
				`UserObm`.`userobm_lastname` =  "'.$_POST['userlastname'].'", 
				`UserObm`.`userobm_firstname` =  "'.$_POST['userfirstname'].'",
				`UserObm`.`userobm_email` =  "'.$_POST['usermail'].'",
				`UserObm`.`userobm_password` =  "'.$_POST['password'].'",
				`UserObmRH`.`temps_hpj` =  "'.$_POST['userhpj'].'",
				`UserObm`.`userobm_delegation_target` = "'.$_POST['admincompte'].'",
				`UserObmRH`.`rh_congepaye` =  "'.($_POST['usercg']).'",
				`UserObmRH`.`rh_congesexcep` =  "'.($_POST['usercexcp']).'",
				`UserObmRH`.`rh_congesanciennete` =  "'.($_POST['usercancien']).'",
				`UserObmRH`.`rh_compteepargnetemps` =  "'.($_POST['userccompte']).'",
				`UserObmRH`.`rh_rtt` =  "'.($_POST['userrttnt']).'",
				`UserObmRH`.`rh_rc` =  "'.($_POST['userrc']).'",
				`UserObmRH`.`rh_maladie` =  "'.($_POST['usermal']).'"
			WHERE `userobm_id` ='.$_POST['userid'].'
			AND `userobm_id` = `userobmrh_id`
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$maj = '<h2 style="color:green;">Mise à Jour réussit !</h2>';
	}else{
	$maj = '<h2 style="color:red;">Re-modifiez et cochez le bouton pour pour valider la modification !</h2>';
	}
}
/*///////////////////////////////////////////////////////////////////
		Première requète
///////////////////////////////////////////////////////////////////*/
$resultats=$connexion->query('
	SELECT *
	FROM `UserObm`
		LEFT OUTER JOIN `UserObmRH`
			ON `userobm_id` = `userobmrh_id`
	WHERE `userobm_login` = "'.$_POST['chuti'].'"
');
$resultats->setFetchMode(PDO::FETCH_OBJ);
$ligne = $resultats->fetch();

/*///////////////////////////////////////////////////////////////////
		Seconde requète
///////////////////////////////////////////////////////////////////*/
$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
mysql_select_db("$PARAM_nom_bd",$db); 

$sql = ('
	SELECT COUNT(`event_id`) AS events
	FROM `Event`, `EventCategory1`
	WHERE `event_usercreate` = "'.$ligne->userobm_id.'"
	AND `event_category1_id` = `eventcategory1_id`
;');

$req = mysql_query($sql);
$events = mysql_fetch_array($req);;
mysql_close($db);

/*///////////////////////////////////////////////////////////////////
							Boucle Options Date
///////////////////////////////////////////////////////////////////*/
/*/////////////////// option date Début /////////////////////*/
for($cdjour = 1; $cdjour <= 31; $cdjour++){
	$optioncdjour .= '<option value="'.$cdjour.'" >'.$cdjour.'</option>';
}
for($cdmois = 1; $cdmois <= 12; $cdmois++){
	$optioncdmois .= '<option value="'.$cdmois.'" >'.$cdmois.'</option>';
}
for($cdannee = date('Y'); $cdannee >= 1980; $cdannee--){
	$optioncdannee .= '<option value="'.$cdannee.'" >'.$cdannee.'</option>';
}
/*/////////////////// option date fin ///////////////////////*/
for($cfjour = 1; $cfjour <= 31; $cfjour++){
	$optioncfjour .= '<option value="'.$cfjour.'">'.$cfjour.'</option>';
}
for($cfmois = 1; $cfmois <= 12; $cfmois++){
	$optioncfmois .= '<option value="'.$cfmois.'" >'.$cfmois.'</option>';
}
for($cfannee = date('Y'); $cfannee >= 1980; $cfannee--){
	$optioncfannee .= '<option value="'.$cfannee.'">'.$cfannee.'</option>';
}
/*///////////////////////////////////////////////////////////////////
						Affichage Des informations
///////////////////////////////////////////////////////////////////*/
echo'
<div id="contentadmin">
	'.$maj.'
	<h2>Page d\'information sur un utilisateur</h2>
	<div id="donnees">
		<fieldset><legend>Données de '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</legend>
			<table style="width:500px;font-size:15px;font-weight:bold;">
				<tr><td>Numéro :</td><td>'.$ligne->userobm_id.'</td><tr>
				<tr><td>Nom :</td><td>'.$ligne->userobm_lastname.'</td><tr>
				<tr><td>Prénom :</td><td>'.$ligne->userobm_firstname.'</td><tr>
				<tr><td>Email :</td><td>'.$ligne->userobm_email.'</td><tr>
				<tr><td>Login :</td><td>'.$ligne->userobm_login.'</td><tr>
				<tr><td>Login :</td><td>'.$ligne->userobm_password.'</td><tr>
				<tr><td>Temps horaire :</td><td>'.$ligne->temps_hpj.'</td><tr>
				<tr><td>Administrateur du compte:</td><td><i>'.$ligne->userobm_delegation_target.'</i></td><tr>
				<tr><td>Congés normaux :</td><td>'.($ligne->rh_congepaye).'</td><tr>
				<tr><td>Congés Exceptionnel:</td><td>'.($ligne->rh_congesexcep).'</td><tr>
				<tr><td>Congés Ancienneté :</td><td>'.($ligne->rh_congesanciennete).'</td><tr>
				<tr><td>Congés Compte Épargne Temps:</td><td>'.($ligne->rh_compteepargnetemps).'</td><tr>
				<tr><td>RTT :</td><td>'.($ligne->rh_rtt).'</td><tr>
				<tr><td>RC normaux :</td><td>'.($ligne->rh_rc).'</td><tr>
				<tr><td>Maladie normaux :</td><td>'.($ligne->userobm_maladie).'</td><tr>
				<tr><td>Nombre d\'événement catégorisés:</td><td>'.$events['events'].'</td><tr>
			</table>
		</fieldset>
	</div>
	<div id="barresep"></div>
	<div id="actions">
		<fieldset><legend>Modifier les données de '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</legend>
			<form method="post" action="infosuser.php">
			<table style="width:550px;font-size:15px;font-weight:bold;">
				<tr><td>Numéro :</td>
				<td><input type="hidden" name="userid" id="userid" value="'.$ligne->userobm_id.'"><span>'.$ligne->userobm_id.'</span></td><tr>
				<tr><td>Nom :</td>
				<td><input type="text" size="15" value="'.$ligne->userobm_lastname.'" name="userlastname"/></td></td><tr>
				<tr><td>Prénom :</td>
				<td><input type="text" size="15" value="'.$ligne->userobm_firstname.'" name="userfirstname"/></td></td><tr>
				<tr><td>Email :</td>
				<td><input type="text" size="15" value="'.$ligne->userobm_email.'"  name="usermail"/></td></td><tr>
				<tr><td>Login :</td>
				<td><input type="hidden" name="chuti" id="chuti" value="'.$ligne->userobm_login.'"><span>'.$ligne->userobm_login.'</span></td></td><tr>
				<tr><td>Mot de Passe :</td>
				<td><input type="text" size="15" value="'.$ligne->userobm_password.'" name="password"/></td></td><tr>
				<tr>
				<tr><td>Temps horaire :</td>
				<td><input type="text" size="15" value="'.$ligne->temps_hpj.'" name="userhpj"/></td></td><tr>
				<tr>
					<td>Administrateur du compte:</td>
					<td>
						<select id="admincompte" name="admincompte" style="height:28px;">
							<option value="'.$ligne->userobm_delegation_target.'">'.$ligne->userobm_delegation_target.'</option>
							'.$listeadmin.'
						</select>
					<td>
				</tr>
				<tr><td>Congés normaux :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_congepaye).'" name="usercg"/></td><tr>
				<tr><td>Congés Exceptionnel :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_congesexcep).'"  name="usercexcp"/></td><tr>
				<tr><td>Congés Ancienneté :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_congesanciennete).'"  name="usercancien"/></td><tr>
				<tr><td>Congés Compte Épargne :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_compteepargnetemps).'"  name="userccompte"/></td><tr>
				<tr><td>RTT :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_rtt).'"  name="userrttnt"/></td><tr>
				<tr><td>RC :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_rc).'"  name="userrc"/></td><tr>
				<tr><td>Maladie :</td>
				<td><input type="text" size="15" value="'.($ligne->rh_maladie).'"  name="usermal"/></td><tr>
				<tr><td>Cocher la case pour accepter la modification:</td>
				<td><input type="checkbox" name="accepter" value="ok"></td><tr>
				<tr><td></td>
				<td><button type="submit" style="height:24px;" name="button" id="button">! Modifier ces données !</button></td><tr>
			</table>
			</form>
		</fieldset>
	</div>
</div>
</body></html>
';
?>
