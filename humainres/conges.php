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
			AND (`eventcategory1_code` >= "901")
			AND `event_date` >= "'.$anneeactuel.'-'.$_GET['chmois'].'-'.$_GET['imem'].'"
			AND `event_date` <= "'.$anneeactuel.'-'.$_GET['chmois'].'-'.($_GET['i']+1).'"
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
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congepaye` =  `rh_congepaye`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 902:
					/// Conges Exceptionnels ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congesexcep` =  `rh_congesexcep`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 903:
					/// Conges Enfant Malade ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_enfantmalade` =  `rh_enfantmalade`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 904:
					/// Conges RTT ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_rtt` =  `rh_rtt`+1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 905:
					/// Conges RC ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_rc` =  `rh_rc`+1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 906:
					/// Conges Maladie ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_maladie` =  `rh_maladie`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 909:
					/// Conges Ancienneté ///
					// $req2 = mysql_query(' UPDATE `UserObmRH` SET `rh_congesanciennete` =  `rh_congesanciennete`-1 WHERE `userobmrh_id` ="'.$_GET['userid'].'" LIMIT 1 ;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
				break;
				case 999:
					/// Férié ///
				break;
				}
			mysql_close();
			echo '<h3>Accepter: '.$ligne->event_id.' / '.$ligne->eventcategory1_code.' - '.$ligne->event_date.' - '.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.' - Validé</h3>';
		}
	}
	
}
/////////////////////////// Demande Refusé ///////////////////////
if($_GET['refus'] == "1"){
	$validation = $connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE `userobm_id` = "'.$_GET['userid'].'"
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `eventcategory1_code` = "901"
			AND `event_date` >= "'.$anneeactuel.'-'.$_GET['chmois'].'-'.$_GET['imem'].'"
			AND `event_date` <= "'.$anneeactuel.'-'.$_GET['chmois'].'-'.($_GET['i']+1).'"
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
}
/////////////////////////////////////////////////////////////////////////////////
////												 FORCAGE DES DROITS ADMIN 									   	 ////
/////////////////////////////////////////////////////////////////////////////////
if($_SESSION['login'] == 'KORNOBIS Jérémie'){
	$formgod = '
		<form method="post" action="conges.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>
			<input type="hidden" name="sauvmois" id="sauvmois" value="'.$moisactuel.'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
if($_SESSION['login'] == 'PECOURT Antoine'){
	$formgod = '
		<form method="post" action="conges.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>
			<input type="hidden" name="sauvmois" id="sauvmois" value="'.$moisactuel.'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
if($_SESSION['login'] == 'FIERRARD Virginie'){
	$formgod = '
		<form method="post" action="conges.php" style="display:inline;">
			<input type="hidden" name="g" id="g" value="od"/>
			<input type="hidden" name="sauvmois" id="sauvmois" value="'.$moisactuel.'"/>
			<button type="submit" style="height:25px;"
			onclick="return(confirm(\'Êtes-vous sûr de vouloir prendre la main ?\'));">Forcer mes Droits</button>
		</form>
	';
}
/////////////////////////////////////////////////////////////////////////////////
////														 AFFICHAGE DE LA PAGE										   	 ////
/////////////////////////////////////////////////////////////////////////////////
if($_GET['chmois'] == "12" || $_POST['chmois'] == "12"){
	$titre ='<h2>Congés de <a href="conges.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="conges.php?chmois=1&channee='.($anneeactuel+1).'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H:i:s').' : '.$formgod.'</h2>';
}else{
	if($_GET['chmois'] == "1" || $_POST['chmois'] == "1"){
		$titre = '<h2>Congés de <a href="conges.php?chmois=12&channee='.($anneeactuel-1).'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="conges.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H:i:s').' : '.$formgod.'</h2>';
	}else{
		$titre = '<h2>Congés de <a href="conges.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="conges.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H:i:s').' : '.$formgod.'</h2>';
	}
}

echo '<div id="content">
'.$titre.'
<table>';

$compteurentete = 0;
$valeurstyle = 0;
$resultats=$connexion->query("
SELECT *
FROM `UserObmGroup`, `UserObm`, `UGroup`
WHERE  `userobmgroup_group_id` = `group_id`
AND `userobmgroup_userobm_id` =  `userobm_id`
AND (`group_name` LIKE 'Secteur%' OR `group_name` LIKE 'Siege%')
AND `group_id` != '1'
AND `group_id` != '13'
ORDER BY  `UGroup`.`group_name` ASC, `userobm_lastname` ASC
;
");
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
		$valeurstyle = $valeurstyle+1;

		$groupid = $ligne->group_id;
		if ($ligne->userobm_delegation_target == $_SESSION['login'] || $_POST['g'] == 'od'){
			$boutonok = "ok";
		}else{
			$boutonok = NULL;
		}
		if($groupid != $memoire_groupid){
			echo '<tr><th><b>'.$ligne->group_name.'</b></th>
			<th>01</th><th>02</th><th>03</th><th>04</th>
			<th>05</th><th>06</th><th>07</th><th>08</th><th>09</th><th>10</th>
			<th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
			<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th>
			<th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th>
			<th>29</th><th>30</th><th>31</th><th>Actions</th>
			</tr>';
		}
		$memoire_groupid = $ligne->group_id;

		////////////  FORMATAGE CSS DES LIGNES POUR LA LISIBILITÉ  ////////////
		if ($valeurstyle%2 == 1){
			echo '<tr style="background-color:#FFF;" ><td><a href="congesuser.php?chuti='.$userid.'&channee='.$anneeactuel.'&chmois='.$moisactuel.'">'.$usernom.'</a></td>';
		}else{
			echo '<tr style="background-color:#CCC;" ><td><a href="congesuser.php?chuti='.$userid.'&channee='.$anneeactuel.'&chmois='.$moisactuel.'">'.$usernom.'</a></td>';
		}
		
//onClick="window.location=\'realiseuser.php?chuti='.$userlogin.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#FFF\';"
// onClick="window.location=\'realiseuser.php?chuti='.$userlogin.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#CCC\';"

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////		 BOUCLE POUR LA SÉLECTION DES JOURS PAR UTILISATEURS			 ////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		for( $i = 1; $i <= 32; $i ++){
			$realise=$connexion->query('
			SELECT *
			FROM `Event`, `EventCategory1`, `UserObm`
			WHERE (`userobm_id` = "'.$userid.'" OR `userobm_login` = "admin")
			AND `event_usercreate` = `userobm_id`
			AND `event_category1_id` = `eventcategory1_id`
			AND `event_date` >= "'.$anneeactuel.'-'.$moisactuel.'-'.$i.'"
			AND `event_date` < "'.$anneeactuel.'-'.$moisactuel.'-'.($i+1).'";
			');
			$realise->setFetchMode(PDO::FETCH_OBJ);
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////			 BOUCLE DE COMPTEUR D'ÉVÉNEMENT + LES CONGÉS	 			 ////
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$timestamp[$i] = mktime (0, 0, 0, $moisactuel, $i, $anneeactuel);
			if ($i == "32"){
				$boutonval = 1;
			}
			if ($i == "1"){
				$imem = $i;
			}
			if(date("l" , $timestamp[$i]) == 'Monday'){
				$imem = $i;			
			}
			if(date("l" , $timestamp[$i]) == 'Saturday' ){
					$boutonval = 1;
					$i++;	
			}else{			
				while( $ligne = $realise->fetch()){
					$actions = '
						<a href="conges.php?valider=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
						onclick="return(confirm(\'Êtes-vous sûr de vouloir Valider les congés du '.$imem.' au '.($i+1).' '.$moisactuelmot[$moisactuel].'?\'));"
						><img src="../img/valider.png" style="width:20px;" title="Accepter"/></a>
						<a href="conges.php?refus=1&imem='.$imem.'&i='.$i.'&userid='.$ligne->userobm_id.'&chmois='.$moisactuel.'"
						onclick="return(confirm(\'Êtes-vous sûr de vouloir Refuser les congés du '.$imem.' au '.($i+1).' '.$moisactuelmot[$moisactuel].'?\'));"
						><img src="../img/supprimer.png" style="width:20px;" title="Refuser"/></a>
					'; 
					if($ligne->eventcategory1_code > 900){
						$datecreation = $ligne->event_timecreate;
						list($anneedatecreation,$moisdatecreation,$jourdatecreation,$h,$m,$s)=sscanf($datecreation,"%d-%d-%d %d:%d:%d");
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
						$datecreation = $jourdatecreation." ".$moismot." ".$anneedatecreation;
						$titledatecrea = 'Demander le: '.$datecreation;

						$eventdate = $ligne->event_date;
						list($anneeeventdate,$moiseventdate,$joureventdate,$h,$m,$s)=sscanf($eventdate,"%d-%d-%d %d:%d:%d");
						$h = $h + 2;
						switch ($moiseventdate){
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
						$eventdate = $joureventdate." ".$moismot." ".$anneeeventdate;
						$titledatecrea = 'Demander le: '.$datecreation;
						switch( $ligne->eventcategory1_code ){
							case '901':
								if($ligne->event_description == "ok"){
									$test = 'CP'; 
									$style='background-color:yellow;font-weight:bold;font-size:14px';
								}else{
									$test = 'CP'; 
									$style='background-color:orange;font-weight:bold;font-size:14px';
								}
							break;
							case '902':
								if($ligne->event_description == "ok"){
									$test = 'CEx';
									$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
								}else{
									$test = 'CEx';
									$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
								}
							break;
							case '903':
								if($ligne->event_description == "ok"){
									$test = 'CEm';
									$style='background-color:yellow;font-weight:bold;font-size:13px;';
								}else{
									$test = 'CEm';
									$style='background-color:orange;font-weight:bold;font-size:13px;';
								}
							break;
							case '904':
								if($ligne->event_description == "ok"){
									$test = 'RTT';
									$style='background-color:yellow;font-weight:bold;font-size:13px;';
								}else{
									$test = 'RTT';
									$style='background-color:orange;font-weight:bold;font-size:13px;';
								}
							break;
							case '906':
								if($ligne->event_description == "ok"){
									$test = 'Mal';
									$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
								}else{
									$test = 'Mal';
									$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
								}
							break;
							case '911':
								if($ligne->event_description == "ok"){
									$test = 'A.T';
									$style='background-color:yellow;font-weight:bold;font-size:13px;color:#000;';
								}else{
									$test = 'A.T';
									$style='background-color:orange;font-weight:bold;font-size:13px;color:#000;';
								}
							break;
							case '999':
								$test = 'F';
								$style='background-color:#333;color:white;font-weight:bold;text-align:center;';	
							break;
						}
						if($ligne->event_timecreate > $ligne->event_date && $jourdatecreation > ($joureventdate+15)){
							$style='background-color:#000;color:white;font-weight:bold;text-align:center;';
							$titledatecrea = 'La date de création de la demande: '.$datecreation.' est supérieur à la date de la demande: '.$eventdate; 
						}
					}				
				}
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////					 TEST POUR L'AFFICHAGE DES VALEURS	 			 ////
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			if(isset($boutonval)){
					if ($boutonok == "ok"){
						echo '<td colspan="2">'.$actions.'</td>';
					}else{
						echo '<td colspan="2" style="background:#999;">.</td>';
					}
			}else{
				if (isset($test)){
					echo '<td style="'.$style.'" title="'.$titledatecrea.'">'.$test.'</td>';
				}else{
					echo '<td></td>';
				}					
			}			
			////////////  RESET DES COMPTEURS  ////////////
			$totalglobal = $test = $boutonval = NULL;
			//////////////////////////////////////////////////////////////////
		}
		echo '</tr>';
		$imem = $boutonok = NULL;
	}	
}
echo '</table>

</div></body></html>';
$resultats->closeCursor();
?>
