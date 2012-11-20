<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mes Congés - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuconges;

$nompage='mesconges.php';
//////////////////////// Fonction consultation générale ///////////////////////////////////

$globalstats = $connexion->query('
	SELECT *
	FROM `Event`, `UserObm`,  `EventCategory1`
	WHERE `event_usercreate` = `userobm_id`
	AND `event_category1_id` = `eventcategory1_id`
	AND `userobm_id` = "'.$utilisateur.'"
	AND `event_date` >= "'.$anneeactuel.'-01-00"
	AND `event_date` <= "'.$anneeactuel.'-12-31"
	ORDER BY `eventcategory1_code` ASC, `event_date` DESC
;');

//////////////////////////////// On Prépare le code html //////////////////////////////////
if($formation == true){
	$texteformation = '
<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'">
<fieldset><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Mes Congés"</i></legend>
<p style="font-size:15px;">
Bienvenue sur la page <i>"Mes Congés"</i> de votre module Personnel.<br/>
Cette page vous permet de consulter vos congés et leur statut directement saisi dans OBM.
</p>
</fieldset></div>';
}


$tableau='<div id="content">
<h2>Tableau des conges - '.$nomsession.' :<span>'.$anneeactuel.'</span></h2>'.$texteformation.'
<h2 style="color:#A00">Les compteurs sont en cours de réalisation. (0 Restants est donc normal)</h2>
<table><tr><th>Categories</th><th>Date</th><th>Date de la Demande</th><th>Temps</th><th>État</th></tr>';

echo $tableau;
/////////////////////////////////////////////////// Emplacement Boucle ///////////////////////////////////////////////////

While($donnees = $globalstats->fetch())
{
	$styleth ='';
	$tempsbrute = $donnees['event_duration'] / 3600;
// Mise en Forme française de la date //
	$datebrute= $donnees['event_date'];
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
	
	$h = $h + 2;
	if($h >= 24){
		if ($jour < 31){$jour = $jour + 1;}
		if ($jour == 31 && $mois < 12){$jour = '01'; $mois = $mois +1;}else{$jour = '01'; $mois = '01'; $annee = $annee +1;}
	}
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
	
	$date = $jour." ".$moismot." ".$annee;
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$userid = $donnees['event_usercreate'];
	$usernom = $donnees['userobm_lastname']." ".$donnees['userobm_firstname'];
	$titre = $donnees['event_title'];
	$cat = $donnees['eventcategory1_label'];
	$catid = $donnees['event_category1_id'];
	$catcode = $donnees['eventcategory1_code'];
	$idevent = $donnees['event_id'];
	$oknon = $donnees['event_description'];
	$congesnormale = $donnees['userobm_congesnormale']/24;
	$congesparental = $donnees['userobm_congesparental'];
	$congesrrtnt = $donnees['userobm_congesrrtnt'];
	$congesrc = $donnees['userobm_congesrc'];
	$congesmaladie = $donnees['userobm_congesmaladie'];
	$tempshpj = $donnees['temps_hpj']/5;
	$userid = $donnees['event_usercreate'];
	$datecreation =$donnees['event_timecreate'];

	$datebrute= $donnees['event_timecreate'];
	list($annee,$mois,$jour,$h,$m,$s)=sscanf($datebrute,"%d-%d-%d %d:%d:%d");
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
///////////////////////////////// Fonction Affichage conge ////////////////////////////////////////////////////////

	if($catcode ==  901 || $catcode ==  902 || $catcode ==  903 || $catcode ==  904 || $catcode ==  905 ){
		if ($tempsbrute > 24){
			$temps = round(($tempsbrute/24));
			$mot = ' jours';
		}else{
			if ($tempsbrute == 24){
				$temps = '1';
				$mot = ' jour';			
			}else{
				if ($tempsbrute >= 8){
					$temps = '1';
					$mot = ' jour';
				}else{
					$temps = $tempsbrute;
					$mot = 'H';
				}
			}
		}
		if($oknon == 'nontraite'){
			$oknon = '<img src="../img/attente.gif" alt="Non Traité" title="Non Traité"  width="25px"/>';
			$stylestatut = 'background-color:white;width:40rpx;';
		}else{
			switch ($oknon) {
				case 'ok':
					$oknon = '<img src="../img/valider.png" alt="Accepté" title="Accepté"  width="25px"/>';$stylestatut = 'background-color:white;color:white;width:40px;';
		    		break;
				case 'non':
					$oknon = '<img src="../img/supprimer.png" width="25px" alt="Refusé" title="Refusé"/>';$stylestatut = 'background-color:red;color:white;width:40px;';
		    		break;
			}
		}
		echo '
		<tr '.$stylet.'>
			<td style="text-align:left;padding-left:5px;width:400px;background-color:white;">'.$cat.'</td>
			<td style="background-color:lightblue;font-weight:bold;font-size:16px;width:155px;text-align:justify;padding-left:5px;">'.$date.'</td>
			<td>'.$datecreation.'</td>
			<td>'.round($temps,2).$mot.' ('.$congesnormale.' restants)</td>
			<td style="'.$stylestatut.'">'.$oknon.'</td>
		</tr>';
	}
}
echo ('</table></div>');
echo ('</body></html>');
?>
