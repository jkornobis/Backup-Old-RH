<?php
require_once('config.php');
$titlepage = "Administration - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuadmin;
/*///////////////////////////////////////////////////////////////////
					Traitement Formulaire
///////////////////////////////////////////////////////////////////*/
if (isset($_GET['raz'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('
			UPDATE  `UserObm` 
			SET `userobm_congesnormale` =  "'.(33*24).'"
			WHERE `userobm_id` > "1"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$raz = '<h2 style="color:green;">Remise à 33 Jours pour tous les utilisateurs effectué !</h2>';
}
if (isset($_POST['taux'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('
			UPDATE `FraisDepVoiture`
			SET `fraisdepvoiture_tarif` =  "'.$_POST['taux'].'"
			WHERE `fraisdepvoiture_puissance` = "'.$_POST['cv'].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$raz = '<h2 style="color:green;">Remise à 33 Jours pour tous les utilisateurs effectué !</h2>';
}
	$form3data = $connexion->query('
		SELECT *
		FROM `FraisDepVoiture`
	;');
	While( $donnees = $form3data->fetch() ){
			$optiontaux = $optiontaux.'<option value="'.$donnees['fraisdepvoiture_puissance'].'">'.$donnees['fraisdepvoiture_puissance'].' CV soit: '.$donnees['fraisdepvoiture_tarif'].' €</option>';
}

if (isset($_POST['lieu'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	$req = mysql_query('
			INSERT INTO `mla`.`FraisDepLieu` (
			`fraisdeplieu_id` ,
			`fraisdeplieu_nom` ,
			`fraisdeplieu_km`
			)
			VALUES (
			NULL , "'.$_POST['lieu'].'", "'.$_POST['km'].'")
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$raz = '<h2 style="color:green;">Insertion d\'un nouveau lieu effectué !</h2>';
}

if (isset($_POST['utilimite'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	if($_POST['utilimite'] == 'globale'){
		$req = mysql_query('
			UPDATE `mla`.`Event`
			SET `event_timeupdate` = NOW(), `event_owner` = "2"
			WHERE `event_date` >= "'.$_POST['annedebut'].'-'.$_POST['moisdebut'].'-'.$_POST['jourdebut'].'"
			AND `event_date` <= "'.$_POST['annefin'].'-'.$_POST['moisfin'].'-'.($_POST['jourfin']+1).'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	}else{
		$req = mysql_query('
			UPDATE `mla`.`Event`
			SET `event_timeupdate` = NOW(), `event_owner` = "2"
			WHERE `event_date` >= "'.$_POST['annedebut'].'-'.$_POST['moisdebut'].'-'.$_POST['jourdebut'].'"
			AND `event_date` <= "'.$_POST['annefin'].'-'.$_POST['moisfin'].'-'.($_POST['jourfin']+1).'"
			AND `event_usercreate` = "'.$_POST['utilimite'].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	}
	$raz = '<h2 style="color:green;">Limitation des événements effectué !</h2>';
}

if (isset($_POST['utiretraitlimite'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	if($_POST['utiretraitlimite'] == 'globale'){
		$req = mysql_query('
			UPDATE `mla`.`Event`
			SET `event_timeupdate` = NOW(), `event_owner` = `event_usercreate`
			WHERE `event_date` >= "'.$_POST['annedebut'].'-'.$_POST['moisdebut'].'-'.$_POST['jourdebut'].'"
			AND `event_date` < "'.$_POST['annefin'].'-'.$_POST['moisfin'].'-'.($_POST['jourfin']+1).'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	}else{
		$req = mysql_query('
			UPDATE `mla`.`Event`
			SET `event_timeupdate` = NOW(), `event_owner` = `event_usercreate`
			WHERE `event_date` >= "'.$_POST['annedebut'].'-'.$_POST['moisdebut'].'-'.$_POST['jourdebut'].'"
			AND `event_date` < "'.$_POST['annefin'].'-'.$_POST['moisfin'].'-'.($_POST['jourfin']+1).'"
			AND `event_usercreate` = "'.$_POST['utiretraitlimite'].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	}
	$raz = '<h2 style="color:green;">Retrait de la Limitation des événements effectué !</h2>';
}
/*///////////////////////////////////////////////////////////////////
						Affichage infos 
///////////////////////////////////////////////////////////////////*/
echo $raz.'
<div id="contentadmin">
	<h2>Page d\'Administration'.$toto.'</h2>
	
	<div id="donnees">

		<fieldset>
		<legend>Utilisateurs</legend>
			<p>
			<form method="POST" action="infosuser.php" >
			<select id="chuti" name="chuti" style="height:25px;width:250px;">
			'.$listeusers.'
			</select>
			<button type="submit" style="height:25px;width:200px;margin-left:-20px;">Voir ses informations</button>
			</form>
			</p>
		</fieldset>

		<fieldset>
		<legend>Taux</legend>
			<p>
			<form method="POST" action="fspadpage.php" >
			<select id="cv" name="cv" >
			'.$optiontaux.'
			</select><b style="margin-left:20px;">Nouveau Taux:</b>
			<input type="text" name="taux" id="taux" size="10" style="margin-left:20px;"/>
			<button type="submit" style="margin-left:120px;padding:2px;">ok</button>
			</form>
			</p>
		</fieldset>

		<fieldset style="margin-bottom:15px;">
		<legend>Lieux Déplacements</legend>
			<p>
			<form method="POST" action="fspadpage.php">
			Lieux: <input type="text" name="lieu" id="lieu" size="20" value="Ville Départ - Ville Arrivé"/>
			<br/><br/>Kilomètres: <input type="text" name="km" id="km" size="10"/>
			<button type="submit" style="margin-left:90px;">ok</button>
			</form>
			</p>
		</fieldset>

		<fieldset><legend>Administration des Congés</legend>
			<a href="fspadpage?raz=1">Compteur congés normale à Zéro</a>
			<br/>Permet de redonner 33 jours à tous les utillisateurs.
		</fieldset>
		<fieldset><legend>Mise à jour des modèles</legend>
			<h3>! Attention ! : ne lancer cette page qu\'une fois la requete d\'insertion de modèles modifier et valide.</h3>
			<a href="">MAJ des modèles d\'événements</a> (bloquer pour l\'instant).		
		</fieldset>
	</div>

	<div id="barresep"></div>

	<div id="actions">
			<fieldset><legend>Limiter les événements</legend>
				<form method="POST" action="fspadpage.php">
				Utilisateurs:
				<select id="utilimite" name="utilimite" style="height:25px;width:250px;">
					<option value="globale">Ensemble des utilisateurs</option>
					'.$listeusersconges.'
				</select><br/>

<!-- 												Date de debut de limitaion 																-->

				Limitation à partir de:
					<select id="jourdebut" name="jourdebut" style="height:24px;">
						<option value="'.date('d').'">'.date('d').'</option>
						<option value="'.date('d').'"> ---- </option>
						'.$optionjours.'
					</select>
					<select id="moisdebut" name="moisdebut" style="height:24px;">
						<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
						<option value="'.$moisactuel.'">---------</option>
						<option value="01">Janvier</option>
						<option value="02">Fevrier</option>
						<option value="03">Mars</option>
						<option value="04">Avril</option>
						<option value="05">Mai</option>
						<option value="06">Juin</option>
						<option value="07">Juillet</option>
						<option value="08">Aout</option>
						<option value="09">Septembre</option>
						<option value="10">Octobre</option>
						<option value="11">Novembre</option>
						<option value="12">Decembre</option>
					</select>
					<select id="annedebut" name="annedebut" style="height:24px;">
						<option value="'.(date("Y")).'">'.(date("Y")).'</option>
						<option value="'.(date("Y")).'">---------</option>
						<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
						<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
						<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
						<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
						<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
					</select>
					<br/>

<!-- 												Date de fin de limitation																	-->

					Jusqu\'au:
					<select id="jourfin" name="jourfin" style="height:24px;">
						<option value="'.(date('d')+1).'">'.(date('d')+1).'</option>
						<option value="'.(date('d')+1).'"> ---- </option>
						'.$optionjours.'
					</select>
					<select id="moisfin" name="moisfin" style="height:24px;">
						<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
						<option value="'.$moisactuel.'">---------</option>
						<option value="01">Janvier</option>
						<option value="02">Fevrier</option>
						<option value="03">Mars</option>
						<option value="04">Avril</option>
						<option value="05">Mai</option>
						<option value="06">Juin</option>
						<option value="07">Juillet</option>
						<option value="08">Aout</option>
						<option value="09">Septembre</option>
						<option value="10">Octobre</option>
						<option value="11">Novembre</option>
						<option value="12">Decembre</option>
					</select>
					<select id="annefin" name="annefin" style="height:24px;">
						<option value="'.(date("Y")).'">'.(date("Y")).'</option>
						<option value="'.(date("Y")).'">---------</option>
						<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
						<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
						<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
						<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
						<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
					</select><br/><br/>
					<button type="submit" style="margin-left:0px;">Valider la Limitation</button>
				</form>
		</fieldset>

<fieldset><legend><u>Retirer</u> la limitation des événements</legend>
				<form method="POST" action="fspadpage.php">
				Utilisateurs:
				<select id="utiretraitlimite" name="utiretraitlimite" style="height:25px;width:250px;">
					<option value="globale">Ensemble des utilisateurs</option>
					'.$listeusersconges.'
				</select><br/>

<!-- 												Date de debut de limitaion 																-->

				<u>Retirer</u> la limitation à partir de:
					<select id="jourdebut" name="jourdebut" style="height:24px;">
						<option value="'.date('d').'">'.date('d').'</option>
						<option value="'.date('d').'"> ---- </option>
						'.$optionjours.'
					</select>
					<select id="moisdebut" name="moisdebut" style="height:24px;">
						<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
						<option value="'.$moisactuel.'">---------</option>
						<option value="01">Janvier</option>
						<option value="02">Fevrier</option>
						<option value="03">Mars</option>
						<option value="04">Avril</option>
						<option value="05">Mai</option>
						<option value="06">Juin</option>
						<option value="07">Juillet</option>
						<option value="08">Aout</option>
						<option value="09">Septembre</option>
						<option value="10">Octobre</option>
						<option value="11">Novembre</option>
						<option value="12">Decembre</option>
					</select>
					<select id="annedebut" name="annedebut" style="height:24px;">
						<option value="'.(date("Y")).'">'.(date("Y")).'</option>
						<option value="'.(date("Y")).'">---------</option>
						<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
						<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
						<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
						<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
						<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
					</select>
					<br/>

<!-- 												Date de fin de limitation																	-->

					Jusqu\'au:
					<select id="jourfin" name="jourfin" style="height:24px;">
						<option value="'.(date('d')+1).'">'.(date('d')+1).'</option>
						<option value="'.(date('d')+1).'"> ---- </option>
						'.$optionjours.'
					</select>
					<select id="moisfin" name="moisfin" style="height:24px;">
						<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
						<option value="'.$moisactuel.'">---------</option>
						<option value="01">Janvier</option>
						<option value="02">Fevrier</option>
						<option value="03">Mars</option>
						<option value="04">Avril</option>
						<option value="05">Mai</option>
						<option value="06">Juin</option>
						<option value="07">Juillet</option>
						<option value="08">Aout</option>
						<option value="09">Septembre</option>
						<option value="10">Octobre</option>
						<option value="11">Novembre</option>
						<option value="12">Decembre</option>
					</select>
					<select id="annefin" name="annefin" style="height:24px;">
						<option value="'.(date("Y")).'">'.(date("Y")).'</option>
						<option value="'.(date("Y")).'">---------</option>
						<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
						<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
						<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
						<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
						<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
					</select><br/><br/>
					<button type="submit" style="margin-left:0px;">Retirer Limitation</button>
				</form>
		</fieldset>

		<fieldset><legend>Outils pour la gestion du serveur:</legend>
			<a href="https://192.168.1.190:10000/" alt="webmin">Webmin</a>
			<br/>Logiciel de monitoring du serveur.<br/><br/>
			<a href="https://192.168.1.190/phpmyadmin/" alt="phpmyadmin">PhpMyAdmin</a>
			<br/>Logiciel de monitoring de la base de données.
			<br/><br/>
			<a href="http://192.168.1.190:3000/thptStats.html" alt="ntop">Moniteur Ntop</a>
			<br/>Logiciel de monitoring de la charge serveur <br/>(inclus directement sur la page moniteur serveur).
		</fieldset>

	</div>
</div>

</body>
</html>
';
?>
