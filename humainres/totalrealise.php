<?php
require_once('config.php');
$titlepage = "Réalisés - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menurealise;
require_once ('tests.php');

if($_GET['chmois'] == "12" || $_POST['chmois'] == "12"){
	$titre ='<h2>Réalisé de <a href="totalrealise.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="totalrealise.php?chmois=1&channee='.($anneeactuel+1).'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
	}else{
		if($_GET['chmois'] == "1" || $_POST['chmois'] == "1"){
			$titre = '<h2>Réalisé de <a href="totalrealise.php?chmois=12&channee='.($anneeactuel-1).'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="totalrealise.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
		}else{
			$titre = '<h2>Réalisé de <a href="totalrealise.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="totalrealise.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////			 BOUCLE POUR LA SÉLECTION DES UTILISATEURS	 				 ////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		
		if($groupid != $memoire_groupid){
		echo '<tr><th><b>'.$ligne->group_name.'</b></th>
		<th>01</th><th>02</th><th>03</th><th>04</th>
		<th>05</th><th>06</th><th>07</th><th>08</th><th>09</th><th>10</th>
		<th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th>
		<th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th>
		<th>23</th><th>24</th><th>25</th><th>26</th><th>27</th><th>28</th>
		<th>29</th><th>30</th><th>31</th>
		</tr>';
		}
		$memoire_groupid = $ligne->group_id;

		////////////  FORMATAGE CSS DES LIGNES POUR LA LISIBILITÉ  ////////////
		if ($valeurstyle%2 == 1){
			echo '<tr style="background-color:#FFF;" ><td>'.$usernom.'</td>';
		}else{
			echo '<tr style="background-color:#CCC;" ><td>'.$usernom.'</td>';
		}
		
//onClick="window.location=\'realiseuser.php?chuti='.$userlogin.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#FFF\';"
// onClick="window.location=\'realiseuser.php?chuti='.$userlogin.'&chmois='.$moisactuel.'&channee='.$anneeactuel.'\'" onMouseOver="this.style.backgroundColor=\'#B5E655\';" onMouseOut="this.style.backgroundColor=\'#CCC\';"

		////////////////////////////////////////////////////////////////////////
		////		 BOUCLE POUR LA SÉLECTION DES JOURS PAR UTILISATEURS			 ////
		///////////////////////////////////////////////////////////////////////
		for( $i = 1; $i <= 31; $i ++){
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
			// on dit qu'on veut que le résultat soit récupérable sous forme d'objet

			///////////////////////////////////////////////////////////////////
			////			 BOUCLE DE COMPTEUR D'ÉVÉNEMENT + LES CONGÉS	 			 ////
			///////////////////////////////////////////////////////////////////
			$timestamp[$i] = mktime (0, 0, 0, $moisactuel, $i, $anneeactuel);
			if(date("l" , $timestamp[$i]) == 'Sunday' || date("l", $timestamp[$i]) == 'Saturday'){
				$test = ' ';
				$style='background-color:#CCC;font-weight:normal;text-align:center;width:10px;';
			}else{			
				while( $ligne = $realise->fetch() )
				{
					list($annee,$mois,$jour,$h,$m,$s)=sscanf($ligne->event_date,"%d-%d-%d %d:%d:%d");
					if($h >= "18" || $h <= "5"){
						$warning .= '<p style="color:#A00;font-size:14px;font-weight:bold;"> ! Attention !: L\'événement intitulé <i>"'.$ligne->event_title.'"</i> du <b>'.$jour.'/'.$mois.'/'.$annee.'</b> à <b>'.($h+1).'H'.$m.'min</b> ne fais pas partie des horaires habituelles de travail. Veuillez vérifier.</p>';
					}
					if($ligne->eventcategory1_code < 900 || $ligne->eventcategory1_code == 907 || $ligne->eventcategory1_code == 908){
						$totalglobal = $totalglobal + ($ligne->event_duration/3600);
					}else{
			
						if($ligne->event_description == "ok"){
							$style='background-color:yellow;font-weight:bold;font-size:13px';
						}else{
							$style='background-color:orange;font-weight:bold;font-size:13px';
						}
						
						switch($ligne->eventcategory1_code){
							case 901:
								$test = 'CP'; 
							break;
							case 902:
								$test = 'CEx'; 
							break;
							case 903:
								$test = 'Cem'; 
							break;
							case 904:
								$test = 'RTT';
								$style='color:black;background:url(../img/rtt.png)bottom right no-repeat ;height:30px;text-align:left;margin:0;padding:0;padding-left:2px;font-weight:bold;font-size:11px;';
							break;
							case 905:
								$test = 'RC'; 
								$style='color:black;background:url(../img/rc.png)bottom right no-repeat ;height:30px;text-align:left;margin:0; padding:0;padding-left:2px;font-weight:bold;font-size:11px;';
							break;
							case 906:
								$test = 'Mal';
							break;
							case 909:
								$test = 'C.Anc'; 
							break;
							case 999:
								$test = 'F';
								$style='background-color:#333333;color:white;font-weight:bold;text-align:center;';
							break;
						}
					}				
				}
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			////					 TEST POUR L'AFFICHAGE DES VALEURS	 			 ////
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if(isset($totalglobal)){
				////////////  TEST DE VALIDITÉ DES DONNÉES  ////////////
				if($totalglobal > 12 ){
					echo '<td style="background-color:red;">!E!</td>';
				}else{
					if (isset($totalglobal) && isset($test)){
						echo '<td style="'.$style.'" valign="top">'.$totalglobal.'</td>';
					}else{
						echo '<td style="font-weight:normal;text-align:center;font-size:11px;">'.$totalglobal.'</td>';
					}
				}
			}else{
				if (isset($test)){
					echo '<td style="'.$style.'" >'.$test.'</td>';
				}else{
					echo '<td></td>';
				}
			}
			////////////  RESET DES COMPTEURS  ////////////
			$totalglobal = $test = NULL;
			//////////////////////////////////////////////////////////////////
		}
		echo '</tr>';
		
	}	
}
echo '</table>
<br/>
<fieldset><legend>Légende:</legend>
<h3>Cliquer sur les noms pour accéder à la page personnel du réalisé.</h3>
<u>Signification des cases:</u><br/><br/>
<a href="" style="background-color:yellow;color:black;padding:5px;border:1px solid grey;">CP</a> Congés payés, vérifier dans le réalisé personnel<br/><br/>
<a href="" style="background-color:#3e7dd1;color:black;padding:5px;border:1px solid grey;">CEx</a> Congés Exceptionnels, vérifier dans le réalisé personnel<br/><br/>
<a href="" style="background-color:#7BA05B;color:black;padding:5px;border:1px solid grey;">CEm</a> Congés Enfant Malade, vérifier dans le réalisé personnel<br/><br/>
<a href="" style="background-color:orange;color:black;padding:5px;border:1px solid grey;">RTT</a> RTT, vérifier dans le réalisé personnel<br/><br/>
<a href="" style="background-color:green;color:black;padding:5px;border:1px solid grey;">RC</a> RC, vérifier dans le réalisé personnel<br/><br/>
<a href="" style="background-color:white;color:black;padding:5px;border:1px solid grey;">8</a> Total des heures réalisées dans la journnée, en heure réelle.<br/><br/>
<a href="" style="background-color:red;color:black;padding:5px;border:1px solid grey;">!E!</a> Journée dont les heures dépasse 12H, à vérifier dans le réalisé personnel.
</fieldset> <div id="warning">
'.$warning.'</div>
</div></body></html>';
$resultats->closeCursor();
?>
