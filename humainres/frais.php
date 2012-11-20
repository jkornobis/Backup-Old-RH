<?php
require_once('config.php');
$titlepage = "Frais - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menufrais;
require_once('tests.php');

if($_GET['chmois'] == "12" || $_POST['chmois'] == "12"){
	$titre ='<h2>Page des Frais: <a href="frais.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="frais.php?chmois=1&channee='.($anneeactuel+1).'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
	}else{
		if($_GET['chmois'] == "1" || $_POST['chmois'] == "1"){
			$titre = '<h2>Page des Frais: <a href="frais.php?chmois=12&channee='.($anneeactuel-1).'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="frais.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
		}else{
			$titre = '<h2>Page des Frais: <a href="frais.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'">'.$flprecedent.'</a> <span>'.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</span><a href="frais.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'" >'.$flsuivant.'</a> Le '.date('d/m/Y à H\Hi').'</h2>';
	}
}

echo'<div id="contentfrais">'.$titre.'</h2>';

/*//////////////////////////////////////////////////////////////////////////////////////////////
						Requetes de traitement
//////////////////////////////////////////////////////////////////////////////////////////////*/
if (isset($_GET['accept'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('UPDATE  `FraisEvent` SET  `fraisevent_statuts` =  "ok" WHERE  `fraisevent_userobmid` ='.$_GET['accept'].';') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if (isset($_GET['refus'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('UPDATE  `FraisEvent` SET  `fraisevent_statuts` =  "non" WHERE  `fraisevent_userobmid` ='.$_GET['refus'].';') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if (isset($_GET['editer'])){
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		// on envoie la requête 
		$req = mysql_query('UPDATE  `FraisEvent` SET  `fraisevent_statuts` =  "nontraite" WHERE  `fraisevent_userobmid` ='.$_GET['editer'].';') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
/*//////////////////////////////////////////////////////////////////////////////////////////////
								Page des Frais
//////////////////////////////////////////////////////////////////////////////////////////////*/
for($utilisateurtab = 0; $utilisateurtab <= 1000 ; $utilisateurtab ++) {
	$resultatsuser = $connexion->query('
		SELECT *
		FROM `UserObm`, `FraisEvent`, `EventCategory1`
		WHERE `fraisevent_userobmid` = `userobm_id`
		AND `fraisevent_catcode` = `eventcategory1_code`
		AND `fraisevent_userobmid` ="'.$utilisateurtab.'"
		AND `fraisevent_date` > "'.$anneeactuel.'-'.$moisactuel.'-00"
		AND `fraisevent_date` < "'.$anneeactuel.'-'.$moisactuel.'-33"
		ORDER BY `fraisevent_date`
	;');
	$resultatsuser->setFetchMode(PDO::FETCH_OBJ);
	
	$ligne = $resultatsuser->fetch();
	if (isset($ligne->fraisevent_id)){
		echo '<table><caption><a href="fraisuser.php?chuti='.$ligne->userobm_id.'&chmois='.$moisactuel.'" class="userlien">'.$ligne->userobm_lastname.' '.$ligne->userobm_firstname.'</a>: '.$moisactuelmot[$moisactuel].' '.$anneeactuel.'</caption>';
		echo '<tr><th>Type</th><th>Catégories</th><th>Date</th><th>Lieu</th><th>Raison</th><th>KM</th><th>Taux</th><th>Montant</th></tr>';
switch ($ligne->fraisevent_statuts){
			case 'nontraite': 
				$statut= '<tr><th colspan="8" style="background:lightblue;color:red;font-size:20px;text-align:center;">Non traité</th></tr>';
				$stylestatut= 'background-color:#CCC;color:red;';
				$accepter = '<a href="frais.php?accept='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Accepter cette Note ?\'));"><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>'; 
				$refuser = '<a href="frais.php?refus='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Refuser cette entrée?\'));"><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			case 'ok':
				$statut= '<tr><th colspan="8" style="background:green;color:white;">Accepté</th></tr>';
				$stylestatut= 'background-color:green;color:white;';
				$accepter = '<a href=""><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>'; 
				$refuser = '<a href="frais.php?refus='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Annuler la précédente validation?\'));"><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'"  onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			case 'non':
				$statut= '<tr><th colspan="8" style="background:red;color:white;">Refusé</th></tr>';
				$stylestatut= 'background-color:red;color:white;';
				$accepter = '<a href="frais.php?accept='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Accepter cette Note ?\'));"><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>';
				$refuser = '<a href=""><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'"  onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			}
			$datebrute = $ligne->fraisevent_date;
			$date = explode("-", $datebrute);
			$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
			$totalprix = $totalprix + $ligne->fraisevent_prix;
			$totalkm = $totalkm + $ligne->fraisevent_km;
			switch($ligne->fraisevent_note){
				case'1':
				$note = 'Déplacement';
				echo '
					<tr>
						<td>'.$note.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label.'</td>
						<td>'.$fraisevent_date.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
						<td>'.$ligne->fraisevent_km.'</td>
						<td style="text-align:left;">'.$ligne->fraisevent_cv.' / Km</td>
						<td>'.$ligne->fraisevent_prix.' €</td>
				
					</tr>
				';
				break;
				case '2':
				$note = 'Annexe';
				echo '
					<tr>
						<td>'.$note.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label.'</td>
						<td>'.$fraisevent_date.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
						<td style="background-color:lightblue;"> ---- </td>
						<td style="text-align:left;background-color:lightblue;"> ---- </td>
						<td>'.$ligne->fraisevent_prix.' €</td>
					</tr>
				';
				break;
			}
		while( $ligne = $resultatsuser->fetch() ) {
			switch ($ligne->fraisevent_statuts){
			case 'nontraite': 
				$statut= '<tr><th colspan="8" style="background:#CCC;color:red;font-size:20px;text-align:center;">Non traité</th></tr>';
				$stylestatut= 'background-color:#CCC;color:red;';
				$accepter = '<a href="frais.php?accept='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Accepter cette Note ?\'));"><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>'; 
				$refuser = '<a href="frais.php?refus='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Refuser cette entrée?\'));"><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			case 'ok':
				$statut= '<tr><th colspan="8" style="background:green;color:white;">Accepté</th></tr>';
				$stylestatut= 'background-color:green;color:white;';
				$accepter = '<a href=""><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>'; 
				$refuser = '<a href="frais.php?refus='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Annuler la précédente validation?\'));"><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'"  onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			case 'non':
				$statut= '<tr><th colspan="8" style="background:red;color:white;">Refusé</th></tr>';
				$stylestatut= 'background-color:red;color:white;';
				$accepter = '<a href="frais.php?accept='.$ligne->fraisevent_userobmid.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Accepter cette Note ?\'));"><img src="../img/valider.png" style="width:50px;" title="Accepter"/></a>';
				$refuser = '<a href=""><img src="../img/supprimer.png" style="width:50px;" title="Refuser"/></a>';
				$editer = '<a href="frais.php?editer='.$ligne->fraisevent_userobmid.'"  onclick="return(confirm(\'Etes-vous sûr de vouloir Remettre à zéro le traitement de cette note?\'));"><img src="../img/editer.png" style="width:50px;" title="Remettre en Non Traité"/></a>';
			break;
			}
			$datebrute = $ligne->fraisevent_date;
			$date = explode("-", $datebrute);
			$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
			$totalprix = $totalprix + $ligne->fraisevent_prix;
			$totalkm = $totalkm + $ligne->fraisevent_km;
			switch($ligne->fraisevent_note){
				case'1':
				$note = 'Déplacement';
				echo '
					<tr>
						<td>'.$note.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label.'</td>
						<td>'.$fraisevent_date.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
						<td>'.$ligne->fraisevent_km.'</td>
						<td style="text-align:left;">'.$ligne->fraisevent_cv.' / Km</td>
						<td>'.$ligne->fraisevent_prix.' €</td>
				
					</tr>
				';
				break;
				case '2':
				$note = 'Annexe';
				echo '
					<tr>
						<td>'.$note.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label.'</td>
						<td>'.$fraisevent_date.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
						<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
						<td style="background-color:lightblue;"> ---- </td>
						<td style="text-align:left;background-color:lightblue;"> ---- </td>
						<td>'.$ligne->fraisevent_prix.' €</td>
					</tr>
				';
				break;
			}
		}
		echo '<tr><th colspan="5" style="'.$stylestatut.'"><th style="'.$stylestatut.'">'.$totalkm.' Km</th><th style="'.$stylestatut.'"></th><th style="'.$stylestatut.'">'.$totalprix.' €</th></tr>'.$statut.'<tr style="background-color:#FFF;"><td style="background-color:#FFF;border:none;"></td><td colspan="7" style="text-align:right;background-color:#FFF;border:none;">'.$accepter.$refuser.$editer.'</td><tr></table><br/>';
	}
	$totalprix = $totalkm = NULL;
}
echo '</table>';
echo '</div></div></body></html>';
?>
