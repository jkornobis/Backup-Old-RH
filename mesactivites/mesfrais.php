<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Mes Frais - Module Personnel";
require_once('menus.php');

$nompage='mesfrais.php';


if(isset($_POST['note1uti'])){
	if($_POST['note1lieu3'] == NULL){
		$lieubrute = $_POST['note1lieu1'];
		$alieu = explode("/", $lieubrute);
		$lieu = $alieu['0'];
		$km = $alieu['1'];
	}else{
		$lieu = $_POST['note1lieu2'];
		$km = $_POST['note1lieu3'];
	}
	if($_POST['note1jour'] < 10){$note1jour = '0'.$_POST['note1jour'];}else{$note1jour = $_POST['note1jour'];}
		$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		
		// on envoie la requête 
		$req = mysql_query('
		INSERT INTO  `mla`.`FraisEvent` (
			`fraisevent_id` ,
			`fraisevent_userobmid` ,
			`fraisevent_date` ,
			`fraisevent_note` ,
			`fraisevent_catcode` ,
			`fraisevent_lieu` ,
			`fraisevent_km` ,
			`fraisevent_cv` ,
			`fraisevent_raison` ,
			`fraisevent_prix` ,
			`fraisevent_statuts`
		)
		VALUES (
			NULL ,
			"'.$_POST['note1uti'].'",
			"'.$_POST['note1annee'].'-'.$_POST['note1mois'].'/'.$note1jour.'", 
			"1",
			"'.$_POST['note1type'].'",
			"'.$lieu.'",
			"'.$km.'",
			"'.$_POST['note1cv'].'",
			"'.$_POST['note1raison'].'",
			"'.($km*$_POST['note1cv']).'",
			"nontraite"
		);'
		) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
////////////////////////////////////////////////////////////////////
//											Update du Frais
////////////////////////////////////////////////////////////////////
if(isset($_POST['updateuti'])){
	if($_POST['note1lieu3'] == NULL){
		$lieubrute = $_POST['note1lieu1'];
		$alieu = explode("/", $lieubrute);
		$lieu = $alieu['0'];
		$km = $alieu['1'];
	}else{
		$lieu = $_POST['note1lieu2'];
		$km = $_POST['note1lieu3'];
	}
	if($_POST['note1jour'] < 10){$note1jour = '0'.$_POST['note1jour'];}else{$note1jour = $_POST['note1jour'];}
		$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
		mysql_select_db("$PARAM_nom_bd",$db);  
		mysql_set_charset("utf8", $db);
		
		// on envoie la requête 
		if (isset($_POST['uti2prix'])){
			$req = mysql_query('
				UPDATE  `mla`.`FraisEvent` 
				SET
					`fraisevent_id` = "'.$_POST['fraisid'].'",
					`fraisevent_date` = "'.$_POST['note1annee'].'-'.$_POST['note1mois'].'-'.$note1jour.'",
					`fraisevent_catcode` = "'.$_POST['note1type'].'",
					`fraisevent_lieu` = "'.$lieu.'",
					`fraisevent_km` = "'.$km.'",
					`fraisevent_cv` = "'.$_POST['note1cv'].'",
					`fraisevent_raison` = "'.$_POST['note1raison'].'",
					`fraisevent_prix` = "'.$_POST['uti2prix'].'"
				WHERE `fraisevent_id` = "'.$_POST['fraisid'].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
		}else{
			$req = mysql_query('
				UPDATE  `mla`.`FraisEvent` 
				SET
					`fraisevent_id` = "'.$_POST['fraisid'].'",
					`fraisevent_date` = "'.$_POST['note1annee'].'-'.$_POST['note1mois'].'-'.$note1jour.'",
					`fraisevent_catcode` = "'.$_POST['note1type'].'",
					`fraisevent_lieu` = "'.$lieu.'",
					`fraisevent_km` = "'.$km.'",
					`fraisevent_cv` = "'.$_POST['note1cv'].'",
					`fraisevent_raison` = "'.$_POST['note1raison'].'",
					`fraisevent_prix` = "'.($km*$_POST['note1cv']).'"
				WHERE `fraisevent_id` = "'.$_POST['fraisid'].'"
			;') or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
		}
		
}
////////////////////////////////////////////////////////////////////
//												Fin Update															//
////////////////////////////////////////////////////////////////////
if(isset($_POST['note2uti'])){
	if($_POST['note2jour'] < 10){$note2jour = '0'.$_POST['note2jour'];}else{$note2jour = $_POST['note2jour'];}
	$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	$_POST['note2prix'] = preg_replace("#,#",".",$_POST['note2prix']);
	// on envoie la requête 
	$req2 = mysql_query('
		INSERT INTO  `mla`.`FraisEvent` (
			`fraisevent_id` ,
			`fraisevent_userobmid` ,
			`fraisevent_date` ,
			`fraisevent_note` ,
			`fraisevent_catcode` ,
			`fraisevent_lieu` ,
			`fraisevent_km` ,
			`fraisevent_cv` ,
			`fraisevent_raison` ,
			`fraisevent_prix` ,
			`fraisevent_statuts`
		)
		VALUES (
			NULL ,
			"'.$_POST['note2uti'].'",
			"'.$_POST['note2annee'].'-'.$_POST['note2mois'].'/'.$note2jour.'", 
			"2",
			"'.$_POST['note2type'].'",
			"'.$_POST['note2lieu'].'",
			0 ,
			"'.$_POST['note2cv'].'",
			"'.$_POST['note2raison'].'",
			"'.$_POST['note2prix'].'",
			"nontraite"
		);'
		) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
if(isset($_GET['supprid'])){

$db = mysql_connect("$PARAM_hote", "$PARAM_utilisateur", "$PARAM_mot_passe");  
	mysql_select_db("$PARAM_nom_bd",$db);  
	mysql_set_charset("utf8", $db);
	// on envoie la requête 
	$req3 = mysql_query('
	 DELETE FROM `FraisEvent` WHERE `FraisEvent`.`fraisevent_id` = " '.$_GET['supprid'].' " '
	) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
}
echo $doctype.$menuprincipale.$menufrais;

//////////////////////// Fonction consultation générale ///////////////////////////////////
$resultatsdep = $connexion->query('
	SELECT *
	FROM `EventCategory1`, `FraisEvent`, `FraisDepVoiture`
	WHERE `fraisevent_catcode` = `eventcategory1_code`
	AND `fraisevent_note` = "1"
	AND `fraisevent_cv` = `fraisdepvoiture_tarif`
	AND `fraisevent_userobmid` = "'.$utilisateur.'"
	AND `fraisevent_date` >= "'.$anneeactuel.'-'.$moisactuel.'-00"
	AND `fraisevent_date` <= "'.$anneeactuel.'-'.$moisactuel.'-33"
	ORDER BY `fraisevent_date`
	;');
$resultatsannexe = $connexion->query('
	SELECT *
	FROM `EventCategory1`, `FraisEvent`
	WHERE `fraisevent_catcode` = `eventcategory1_code`
	AND `fraisevent_note` = "2"
	AND `fraisevent_userobmid` = "'.$utilisateur.'"
	AND `fraisevent_date` >= "'.$anneeactuel.'-'.$moisactuel.'-00"
	AND `fraisevent_date` <= "'.$anneeactuel.'-'.$moisactuel.'-33"
	ORDER BY `fraisevent_date`
	;');
$titre1 = '<h2>Tableau des demandes de Frais de Déplacements: '.date("d/m/Y H:i").'</h2>';
$titre2 = '<h2>Tableau des demandes de Frais Annexes:</h2>';
///////////////////////////////////////////// On Prépare le code html //////////////////////////////////////////////////////
if($formation == true){/*
	$texteformation = '<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'">
<fieldset class="formation"><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Mes Frais"</i></legend>
<p style="font-size:15px;font-weight:normal;">
Bienvenue sur la page <i>"Mes Frais"</i> de votre module Personnel.<br/>
Cette page vous permet de consulter vos Frais et les poser.<br/>
Pour cela il suffit de cliquer sur le lien ci-dessous: <i>"Demander une note de frais"</i> et de remplir les champs correspondants à votre demande.<br/> Valider la demande vous amènera sur cette page pour consulter votre nouvelle demande.
</p>
</fieldset></div>';*/
}

$tableau1='<div id="contentfrais">
<h2>Page des Frais - '.$nomsession.' :<a href="mesfrais.php?chmois='.($moisactuel-1).'&channee='.$anneeactuel.'" >'.$flprecedent.'</a><span>'.$moisactuelmot.' '.$anneeactuel.'</span><a href="mesfrais.php?chmois='.($moisactuel+1).'&channee='.$anneeactuel.'">'.$flsuivant.'</a></h2>'.$texteformation.'<a href="demande.php?uti='.$utilisateur.'"><img src="../img/demande.png"/ title="Faire une note de Frais" class="btndemande">
<h2> </a><h2>
'.$titre1.'
<table style="height:auto;border:none;">
</th><th></th><th width="60px">Date</th><th>Categorie</th><th>Lieu</th><th>Km</th><th>Raison</th><th width="130px">Cv</th><th width="80px">Montant</th></tr>';

$tableau2='<br/>
'.$titre2.'
<table style="height:auto;border:none;">
</th><th></th><th width="60px">Date</th><th>Categorie</th><th>Lieu</th><th>Raison</th><th width="80px">Montant</th></tr>';
/*////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							Tableau Frais Déplacements
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
echo $tableau1;
/////////////////////////////////////////////////// Emplacement Boucle  ///////////////////////////////////////////////////
$resultatsdep->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultatsdep->fetch() ) {
	if ($ligne->fraisevent_note == "1"){$note= 'Déplacement';}else{$note= 'Annexe';}
	$datebrute = $ligne->fraisevent_date;
	$date = explode("-", $datebrute);
	$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
	switch ($ligne->fraisevent_statuts){
		case 'nontraite':
			$statut1= 'nontraite'; 
			$supprimer = '<a href="mesfrais.php?supprid='.$ligne->fraisevent_id.'&uti='.$utilisateur.'"  onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette Ligne?\'));"><img src="../img/supprimer.png" style="width:25px;" title="Supprimer"/></a>';
			$editer = '<a href="modifierfrais.php?fraisid='.$ligne->fraisevent_id.'"><img src="../img/editer.png" style="width:25px;" title="Editer"/></a>'; 
			$totalprix1 = $totalprix1 + $ligne->fraisevent_prix;
			$totalkm1 = $totalkm1 + $ligne->fraisevent_km;
			$stylestatut = 'color:red;background-color:#CCC;';
		break;
		case 'ok': 
			$statut1= 'ok';
			$totalprix1 = $totalprix1 + $ligne->fraisevent_prix;
			$totalkm1 = $totalkm1 + $ligne->fraisevent_km;
			$stylestatut = 'color:white;background-color:green;';
			$supprimer = '<img src="../img/space.png" style="width:25px;" title="Non Modifiable"/>';
			$editer = '<img src="../img/space.png" style="width:25px;" title="Non Modifiable"/>'; 
		break;
		case 'non':
			$statut1= 'non';
			$supprimer = '<a href="mesfrais.php?supprid='.$ligne->fraisevent_id.'&uti='.$utilisateur.'" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette Ligne?\'));"><img src="../img/supprimer.png" style="width:30px;" title="Supprimer"/></a>';
			$editer = '<a href="modifierfrais.php?fraisid='.$ligne->fraisevent_id.'"><img src="../img/editer.png" style="width:25px;" title="Editer"/></a>'; 
			$totalprix1 = $totalprix1 + $ligne->fraisevent_prix;
			$totalkm1 = $totalkm1 + $ligne->fraisevent_km;
			$stylestatut = 'color:white;background-color:red;';
		break;
	}
	echo '
		<tr>
			<th style="background-color:#FFF;border:none;">'.$editer.$supprimer.'</th>
			<td style="background-color:lightblue;text-align:left;width:75px;padding-left:5px;">'.$fraisevent_date.'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label.'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
			<td style="text-align:center;width:40px;">'.$ligne->fraisevent_km.'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
			<td style="text-align:center;width:140px;">'.$ligne->fraisdepvoiture_puissance.'cv  ('.$ligne->fraisevent_cv.' € / Km)</td>
			<td style="text-align:center;width:80px;">'.round($ligne->fraisevent_prix,'2').' €</td>
		</tr>
	';
}
echo'
<tr>
	<th style="background-color:#FFF;border:none;"></th><th  style="'.$stylestatut.'">Total:</th><th style="'.$stylestatut.'"></th><th  style="'.$stylestatut.'"></th><th  style="'.$stylestatut.'">'.$totalkm1.'</th><th  style="'.$stylestatut.'"></th><th  style="'.$stylestatut.'"></th>
	<th  style="'.$stylestatut.'">'.round($totalprix1,'2').' €</th>
</tr>
';
echo ('</table>');

/*////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							Tableau Frais Annexes
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
echo $tableau2;
$resultatsannexe->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $resultatsannexe->fetch() ) {
	if ($ligne->fraisevent_note == "1"){$note= 'Déplacement';}else{$note= 'Annexe';}
	$datebrute = $ligne->fraisevent_date;
	$date = explode("-", $datebrute);
	$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
	switch ($ligne->fraisevent_statuts){
		case 'nontraite':
			$statut2= 'nontraite';
			$supprimer = '<a href="mesfrais.php?supprid='.$ligne->fraisevent_id.'&uti='.$utilisateur.'$chmois='.$date['1'].'"><img src="../img/supprimer.png" style="width:25px;" title="Supprimer" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette Ligne?\'));"/></a>';
			$editer = '<a href="modifierfrais.php?fraisid='.$ligne->fraisevent_id.'"><img src="../img/editer.png" style="width:25px;" title="Editer"/></a>'; 
			$totalprix2 = $totalprix2 + $ligne->fraisevent_prix;
			$totalkm2 = $totalkm2 + $ligne->fraisevent_km;
			$stylestatut = 'color:red;background-color:#CCC;';
		break;
		case 'ok': 
			$statut2= 'ok';
			$totalprix2 = $totalprix2 + $ligne->fraisevent_prix;
			$stylestatut = 'color:white;background-color:green;';
			$supprimer = '<img src="../img/space.png" style="width:25px;" title="Non Modifiable"/>';
			$editer = '<img src="../img/space.png" style="width:25px;" title="Non Modifiable"/>'; 
		break;
		case 'non':
			$statut2= 'non';
			$supprimer = '<a href=""><img src="../img/supprimer.png" style="width:30px;" title="Supprimer" onclick="return(confirm(\'Etes-vous sûr de vouloir Supprimer cette Ligne?\'));"/></a>';
			$editer = '<a href="modifierfrais.php?fraisid='.$ligne->fraisevent_id.'"><img src="../img/editer.png" style="width:25px;" title="Editer"/></a>'; 
			$totalprix2 = $totalprix2 + $ligne->fraisevent_prix;
			$stylestatut = 'color:white;background-color:red;';
		break;
	}
	
	$datebrute = $ligne->fraisevent_date;
	$date = explode("-", $datebrute);
	$fraisevent_date = $date['2'].'/'.$date['1'].'/'.$date['0'];
	
	echo '
		<tr>
			<th style="background-color:#FFF;border:none;">'.$editer.$supprimer.'</th>
			<td style="background-color:lightblue;text-align:left;width:75px;padding-left:5px;">'.$fraisevent_date.'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->eventcategory1_label .'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_lieu.'</td>
			<td style="text-align:left;padding-left:5px;">'.$ligne->fraisevent_raison.'</td>
			<td style="background-color:white;text-align:center;width:80px;">'.round($ligne->fraisevent_prix,'2').' €</td>
		</tr>
	';
}
echo'
<tr>
	<th style="background-color:#FFF;border:none;"></th><th  style="'.$stylestatut.'">Total:</th><th  style="'.$stylestatut.'"></th><th  style="'.$stylestatut.'"></th><th  style="'.$stylestatut.'"></th>
	<th  style="'.$stylestatut.'">'.round($totalprix2,'2').' €</th>
</tr>
';
echo ('</table>');
$statutotal = $totalprix1 + $totalprix2;
$totalkm = $totalkm1;
if( $statut1 == "nontraite" ||  $statut2 == "nontraite"){
echo'
<br/><fieldset><legend><img src="../img/attente.gif" width="60px"/></legend><b>Vos Notes de Frais n\'ont pas encore été traitées.</b></fieldset>
';
}
if( $statut1 == "ok" ||  $statut2 == "ok"){
echo'
<br/><fieldset><legend><img src="../img/valider.png" width="60px"/></legend><b>Vos Notes de Frais sont Acceptées.</b></fieldset>
';
}
if( $statut1 == "non" ||  $statut2 == "non"){
echo'
<br/><fieldset><legend><img src="../img/supprimer.png" width="60px"/></legend><b>Vos Notes de Frais sont Refusées. contacter l\'administrateur</b></fieldset>';
}
echo '<br/>
<fieldset><legend>Resumé des frais:</legend>
Kilomètres parcourus: '.$totalkm.' Kms<br/>
Montant total: '.$statutotal.' €<br/>
</fieldset>
';
echo '<br/>
<fieldset style="width:600px;"><legend>Signatures:</legend>
<h2 style="display:inline;"> Demandeur: </h2><h2 style="display:inline;margin-left:205px;">Responsable:</h2>
<fieldset style="width:235px;height:100px;display:inline;"></fieldset>
<fieldset style="width:245px;height:100px;display:inline;margin-left:30px;"></fieldset>

</fieldset>
';
echo ('</div></body></html>');
?>
