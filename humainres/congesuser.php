<?php
require_once('config.php');
$titlepage = "Congés - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuconges;
require_once ('tests.php');

/////////////////////// Sauvegarde du Mois ////////////////////////
if($_POST['g'] == 'od'){$moisactuel = $_POST['sauvmois'];}
/////////////////////////// ////////////// ///////////////////////

////////////////////////////////////////////////////////////////////////////////
////							 Traitement de la validation ou du refus 					  		  ////
////////////////////////////////////////////////////////////////////////////////

/////////////////////////// Demande Validé ///////////////////////
if($_GET['valider'] == "1"){
	$validation = $connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `event_id` = "'.$_GET['idconge'].'"
	;');
	$validation->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $validation->fetch()){
		if($ligne->event_description == "ok"){
			echo '<h3>Accepter: '.$ligne->event_id.' / '.$ligne->eventcategory1_code.' - '.$ligne->event_date.' - '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.' - Déjà Validé</h3>';
		}else{
			$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
			mysql_select_db("$PARAM_nom_bd",$db);  
			mysql_set_charset("utf8", $db);
			// on envoie la requête 
			$req1 = mysql_query('
			 UPDATE  `mla`.`Event` SET  `event_description` =  "ok" WHERE  `Event`.`event_id` ="'.$ligne->event_id.'" LIMIT 1 ;
			') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$req3 = mysql_query('
	 			UPDATE  `mla`.`Event` SET  `event_tag_id` = "2", `event_owner`="2" WHERE  `Event`.`event_id` ="'.$ligne->event_id.'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

			switch($ligne->eventcategory1_code){
				case 901:
					/// Conges Payes ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congepaye` =  `rh_congepaye`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 902:
					/// Conges Exceptionnels ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congesexcep` =  `rh_congesexcep`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 903:
					/// Conges Enfant Malade ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_enfantmalade` =  `rh_enfantmalade`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 904:
					/// Conges RTT ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_rtt` =  `rh_rtt`+1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 905:
					/// Conges RC ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_rc` =  `rh_rc`+1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 906:
					/// Conges Maladie ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_maladie` =  `rh_maladie`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 909:
					/// Conges Ancienneté ///
					$req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congesanciennete` =  `rh_congesanciennete`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 999:
					/// Férié ///
				break;
				}

			mysql_close();
			echo '<h3>Accepter: '.$ligne->event_id.' / '.$ligne->eventcategory1_code.' - '.$ligne->event_date.' - '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.' - Validé</h3>';
		}
	}
	$resultats=$connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `eventcategory1_code` >= "900"
			AND `event_date` >= "'.$_POST['channee'].'-'.$_POST['chmois'].'-01"
			AND `event_date` <= "'.$_POST['channee'].'-'.$_POST['chmois'].'-31"
			ORDER BY `event_date`
	;');
}
/////////////////////////// Demande Refusé ///////////////////////
if($_GET['refus'] == "1"){
	$validation = $connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `event_id` = "'.$_GET['idconge'].'"
	;');
	$validation->setFetchMode(PDO::FETCH_OBJ);
	while($ligne = $validation->fetch()){
		if($ligne->event_description == "ok"){
			echo '<h3>Refus: '.$ligne->event_id.' / '.$ligne->eventcategory1_code.' - '.$ligne->event_date.' - '.$ligne->userobm_lastname.'  '.$ligne->userobm_firstname.' - Déjà Validé</h3>';
		}else{
			$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
			mysql_select_db("$PARAM_nom_bd",$db);  
			mysql_set_charset("utf8", $db);
			// on envoie la requête 
			$req1 = mysql_query('
			 UPDATE  `mla`.`Event` SET  `event_description` =  "non" WHERE  `Event`.`event_id` ="'.$ligne->event_id.'" LIMIT 1 ;
			') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			$req2 = mysql_query('
	 			UPDATE  `mla`.`Event` SET  `event_tag_id` = "3", `event_owner`="2" WHERE  `Event`.`event_id` ="'.$ligne->event_id.'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
			
			echo '<h3>Refus: '.$ligne->event_id.' / '.$ligne->eventcategory1_code.' - '.$ligne->event_date.' - '.$ligne->userobm_lastname.'  '.$ligne->userobm_firstname.' - Pas Encore Validé</h3>';
		mysql_close();
		}
	}
	$resultats=$connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `eventcategory1_code` >= "900"
			AND `event_date` >= "'.$_POST['channee'].'-'.$_POST['chmois'].'-01"
			AND `event_date` <= "'.$_POST['channee'].'-'.$_POST['chmois'].'-31"
			ORDER BY `event_date`
	;
	');
}
/////////////////////////////////////////////////////////////////////////////////
////												 FORCAGE DES DROITS ADMIN 									   	 ////
/////////////////////////////////////////////////////////////////////////////////
if($_SESSION['login'] == 'KORNOBIS Jérémie'){
	$formgod = '
		<form method="post" action="congesuser.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>		
			<input type="hidden" name="channee" id="channee" value="'.$_POST['channee'].'"/>
			<input type="hidden" name="chmois" id="chmois" value="'.$_POST['chmois'].'"/>
			<input type="hidden" name="chuti" id="chuti" value="'.$_POST['chuti'].'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
if($_SESSION['login'] == 'PECOURT Antoine'){
	$formgod = '
		<form method="post" action="congesuser.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>
			<input type="hidden" name="channee" id="channee" value="'.$_POST['channee'].'"/>
			<input type="hidden" name="chmois" id="chmois" value="'.$_POST['chmois'].'"/>
			<input type="hidden" name="chuti" id="chuti" value="'.$_POST['chuti'].'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
if($_SESSION['login'] == 'FIERRARD Virginie'){
	$formgod = '
		<form method="post" action="congesuser.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>
			<input type="hidden" name="channee" id="channee" value="'.$_POST['channee'].'"/>
			<input type="hidden" name="chmois" id="chmois" value="'.$_POST['chmois'].'"/>
			<input type="hidden" name="chuti" id="chuti" value="'.$_POST['chuti'].'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
/////////////////////////////////////////////////////////////////////////////////
////														 AFFICHAGE DE LA PAGE										   	 ////
/////////////////////////////////////////////////////////////////////////////////
if($_GET['idconge']){
	$recupinfo=$connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `event_id` = "'.$_GET['idconge'].'"
			;');
	$recupinfo->setFetchMode(PDO::FETCH_OBJ);
	while( $champs = $recupinfo->fetch() )
	{
			$datebrute = $champs->event_date;
			list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");

			$resultats=$connexion->query('
				SELECT *
				FROM `Event`, `EventCategory1`, `UserObm`, `UserObmRH`
				WHERE `userobm_id` = "'.$_GET['userid'].'"
				AND `userobm_id` = `userobmrh_id`
				AND `event_usercreate` = `userobm_id`
				AND `event_category1_id` = `eventcategory1_id`
				AND `eventcategory1_code` >= "900"
				AND `event_date` >= "'.$annee.'-'.$mois.'-01"
				AND `event_date` <= "'.$annee.'-'.$mois.'-31"
				ORDER BY `event_date`
			;
			');
	}
	$moisactuel = $mois;
}else{
	if(isset($_GET['chuti'])){
		$resultats=$connexion->query('
					SELECT *
					FROM `Event`, `EventCategory1`, `UserObm`, `UserObmRH`
					WHERE `userobm_id` = "'.$_GET['chuti'].'"
					AND `userobm_id` = `userobmrh_id`
					AND `event_usercreate` = `userobm_id`
					AND `event_category1_id` = `eventcategory1_id`
					AND `eventcategory1_code` >= "900"
					AND `event_date` >= "'.$_GET['channee'].'-'.$_GET['chmois'].'-01"
					AND `event_date` <= "'.$_GET['channee'].'-'.$_GET['chmois'].'-31"
					ORDER BY `event_date`
		;');
	}else{
		$resultats=$connexion->query('
				SELECT *
				FROM `Event`, `EventCategory1`, `UserObm`, `UserObmRH`
				WHERE `userobm_id` = "'.$_POST['chuti'].'"
				AND `userobm_id` = `userobmrh_id`
				AND `event_usercreate` = `userobm_id`
				AND `event_category1_id` = `eventcategory1_id`
				AND `eventcategory1_code` >= "900"
				AND `event_date` >= "'.$_POST['channee'].'-'.$_POST['chmois'].'-01"
				AND `event_date` <= "'.$_POST['channee'].'-'.$_POST['chmois'].'-31"
				ORDER BY `event_date`
			;
			');
	}
}
echo '<div id="content">
<h2>Congés de <a href="congesuser.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="congesuser.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H:i:s').' : '.$formgod.'</h2>
<table> <tr><th>Nom</th><th>Date</th><th>Type de congés</th><th>Titre</th><th>Date de la Demande</th><th>Temps/Restant</th><th>Actions</th></tr>';

$resultats->setFetchMode(PDO::FETCH_OBJ);
////////////////////////////////////////////////////////////////////////////////
////			 BOUCLE POUR LA SÉLECTION DES UTILISATEURS	 				 ////
////////////////////////////////////////////////////////////////////////////////
while( $ligne = $resultats->fetch() )
{
	////////////  NETTOYAGE DES COMPTES SPÉCIAUX  ////////////
	if($ligne->userobm_lastname == NULL || $ligne->userobm_lastname == 'admin' || $ligne->userobm_lastname == 'Admin Lastname'
	|| $ligne->userobm_lastname == 'MLA' || $ligne->userobm_lastname == 'Secrétaires'  || $ligne->userobm_statut == "non" || $ligne->userobm_archive == "1" ){
	}else{
		$userid = $ligne->userobm_id;
		$usernom = $ligne->userobm_lastname.' '.$ligne->userobm_firstname;
		$userlogin = $ligne->userobm_login;
		$hpj = $ligne->temps_hpj/5;
		// Mise en Forme française de la date //
		$datebrute= $ligne->event_date;
		list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	
		$h = $h + 2;
		if($h >= 24){
			if ($jour < 31){$jour = $jour + 1;}
			if ($jour == 31 && $mois < 12){$jour = '01'; $mois = $mois +1;}else{$jour = '01'; $mois = '01'; $annee = $annee +1;}
		}
	
		$date = $jour." ".$moisactuelmot[$moisactuel]." ".$annee;

		$datecreation = $ligne->event_timecreate;
						list($annee,$mois,$jour,$h,$m,$s)=sscanf($datecreation,"%d-%d-%d %d:%d:%d");
						$h = $h + 2;
						switch ($mois){
							case '01': $moismot = 'Janvier'; break;
							case '02': $moismot = 'Février'; break;
							case '03': $moismot = 'Mars'; break;
							case '04': $moismot = 'Avril'; break;
							case '05': $moismot = 'Mai'; break;
							case '06': $moismot = 'Juin'; break;
							case '07': $moismot = 'Juillet'; break;
							case '08': $moismot = 'Aout'; break;
							case '09': $moismot = 'Septembre'; break;
							case '10': $moismot = 'Octobre'; break;
							case '11': $moismot = 'Novembre'; break;
							case '12': $moismot = 'Décembre'; break;
						}
		$datecreation = $jour." ".$moismot." ".$annee;
		$titledatecrea = $datecreation;
		
		switch( $ligne->eventcategory1_code ){
			case '901':
				if($ligne->event_description == "ok"){
					$test = 'CP'; 
					$hpj = 1;
					$restant = $ligne->rh_congepaye;
					$style='background-color:yellow;font-weight:bold;font-size:14px';
				}else{
					$test = 'CP'; 
					$style='background-color:orange;font-weight:bold;font-size:14px';
					$hpj = 1;
					$restant = $ligne->rh_congepaye;
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '902':
				if($ligne->event_description == "ok"){
					$test = 'CEx';
					$hpj = 1;
					$restant = $ligne->rh_congesexcep;
					$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
				}else{
					$test = 'CEx';
					$hpj = 1;
					$restant = $ligne->rh_congesexcep;
					$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '903':
				if($ligne->event_description == "ok"){
					$test = 'CEm';
					$hpj = ($ligne->event_duration/3600);
					$restant = $ligne->rh_enfantmalade;
					$style='background-color:yellow;font-weight:bold;font-size:13px;';
				}else{
					$test = 'CEm';
					$hpj = ($ligne->event_duration/3600);
					$restant = $ligne->rh_enfantmalade;
					$style='background-color:orange;font-weight:bold;font-size:13px;';
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '904':
				if($ligne->event_description == "ok"){
					$test = 'RTT';
					$hpj = ($ligne->event_duration/3600);
					$restant = $ligne->rh_rtt;
					$style='background-color:yellow;font-weight:bold;font-size:13px;';
					$hpj = ($ligne->event_duration/3600);
				}else{
					$test = 'RTT';
					$style='background-color:orange;font-weight:bold;font-size:13px;';
					$hpj = ($ligne->event_duration/3600);
					$restant = $ligne->rh_rtt;
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '906':
				if($ligne->event_description == "ok"){
					$test = 'Mal';
					$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
				}else{
					$test = 'Mal';
					$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '911':
				if($ligne->event_description == "ok"){
					$test = 'A.T';
					$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
				}else{
					$test = 'A.T';
					$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
			case '999':
				$test = 'F';
				$style='background-color:#333;color:white;font-weight:bold;text-align:center;';	
			break;
			default:
				if($ligne->event_description == "ok"){
					$hpj = ($ligne->event_duration/3600);
					$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
				}else{
					$hpj = ($ligne->event_duration/3600);
					$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
					$actions = '
			<a href="congesuser.php?valider=1&userid='.$ligne->userobm_id.'&idconge='.$ligne->event_id.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du  '.$date.'?\'));"><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>

			<a href="congesuser.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du  '.$date.'?\'));"><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
		';
				}
			break;
		}
		if (($ligne->userobm_delegation_target == $_SESSION['login'] || $_POST['g'] == 'od') && $ligne->event_description != "ok"){
			echo '<tr><td style="width:130px;">'.$usernom.'</td><td style="'.$style.'">'.$date.'</td><td>'.$ligne->eventcategory1_label.'</td><td>'.$ligne->event_title.'</td><td>'.$titledatecrea.'</td><td>'.$hpj.' ('.$restant.' restants)</td><td>'.$actions.'</td></tr>';
		}else{
			echo '<tr><td style="width:130px;">'.$usernom.'</td><td style="'.$style.'">'.$date.'</td><td>'.$ligne->eventcategory1_label.'</td><td>'.$ligne->event_title.'</td><td>'.$titledatecrea.'</td><td>'.$hpj.' ('.$restant.' restants)</td><td>----</td></tr>';
		}
	}
}
echo '</table>

</div></body></html>';
$resultats->closeCursor();
?>
