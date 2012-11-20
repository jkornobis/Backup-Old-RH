<?php
$doctype='
	<!DOCTYPE html>
	<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title>'.$titlepage.'</title>
	<meta http-equiv="Refresh" content="3600;url=moniteur.php">
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="print" type="text/css" href="print.css"/>
	<link rel="shortcut icon" href="../admin.png">
	</head>
	<body>
';
$menuprincipale='
	<div id="menuprincipale" >
		<img src="../img/logo.png" width="190px" alt="logo" onclick="window.location.href=\'etat.php\'" title="États"/>
		<b>Administration:</b>
		<ul>
			<li><a href="acceuiladmin.php" style="margin-left:10px;">Acceuil Admin</a></li>
			<li><a href="totalrealise.php" style="margin-left:10px;">Réalisés</a></li>
			<li><a href="conges.php" style="margin-left:10px;">Congés</a></li>
			<li><a href="frais.php" style="margin-left:10px;">Frais</a></li>
		</ul>
		<b>Informations:</b>
		<ul>
			<li><a href="acceuilstats.php" style="margin-left:10px;">Accueil Stats</a></li>
			<li><a href="stats.php" style="margin-left:10px;">Statistiques</a></li>
			<li><a href="bugtrackeradmin.php" style="margin-left:10px;">Avis sur OBM</a></li>
		</ul>
		<b>Options:</b>
		<ul>
			<li><a href="javascript:window.print()" style="margin-left:10px;">Imprimer la Page</a></li>
			<li><a href="fspadpage.php" style="margin-left:10px;">Options Avancés</a></li>
		</ul>
		<b>Retour:</b>
		<ul>
			<li><a href="../mesactivites/etat.php" style="margin-left:10px;">Retour M.Personel</a></li>
		</ul>
	</div>
';
$nbusers= 1;
While( $donnees = $form1data->fetch() ){
	if( $donnees['userobm_login'] == "admin0" || $donnees['userobm_login'] == "admin" || $donnees['userobm_login'] == "mla" || $donnees['userobm_login'] == "acceuilsiege" || $donnees['userobm_statut'] == "non"){}else{
		$listeusers = $listeusers.'<option value="'.$donnees['userobm_login'].'">'.$donnees['userobm_lastname'].' '.$donnees['userobm_firstname'].'</option>';
		$listeusersconges = $listeusersconges.'<option value="'.$donnees['userobm_id'].'">'.$donnees['userobm_lastname'].' '.$donnees['userobm_firstname'].'</option>';
		$listeadmin = $listeadmin.'<option value="'.$donnees['userobm_lastname'].' '.$donnees['userobm_firstname'].'">'.$donnees['userobm_lastname'].' '.$donnees['userobm_firstname'].'</option>';
	}$nbusers++;
}
While( $donnees = $form2data->fetch() ){
		$listeprojets = $listeprojets.'<option value="'.$donnees['eventcategory1_code'].'">'.$donnees['eventcategory1_label'].'</option>';

		if ($donnees['eventcategory1_code'] < 900){
			$listeprojetsbouton = $listeprojetsbouton.'<input type="checkbox" name="'.$donnees['eventcategory1_code'].'" value="'.$donnees['eventcategory1_code'].'">'.$donnees['eventcategory1_label'].'<br/>';
			$totalprojetsbouton = $totalprojetsbouton++; 
		}
}

for($i = 1; $i <= 31; $i++){
		$optionjours .= '<option value="'.$i.'">'.$i.'</option>';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Statistiques
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menustats='
	<div id="sousmenu">
	<form method="post" action="stats.php" style="display:inline;">
	<p>
	<select id="chmois" name="chmois" style="font-size:13px;">
		<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
		<option value="'.$moisactuel.'">---------</option>
		<option value="1">Janvier</option>
		<option value="2">Fevrier</option>
		<option value="3">Mars</option>
		<option value="4">Avril</option>
		<option value="5">Mai</option>
		<option value="6">Juin</option>
		<option value="7">Juillet</option>
		<option value="8">Aout</option>
		<option value="9">Septembre</option>
		<option value="10">Octobre</option>
		<option value="11">Novembre</option>
		<option value="12">Decembre</option>
		<option value="13">Année entière</option>
	</select>

	<select id="channee" name="channee" style="font-size:13px;">
		<option value="'.(date("Y")+0).'">'.(date("Y")).'</option>
		<option value="'.(date("Y")+0).'">---------</option>
		<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
		<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
		<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
		<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
		<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>

	<select id="chuti" name="chuti" style="font-size:13px;">
		<option value="globale">Globale | ou:</option>
		'.$listeusers.'
	</select>
	<button type="submit" style="font-size:13px;">Valider</button>

	</form><b>Ou:</b>
	<form method="post" action="statsprojet.php" style="display:inline;font-size:13px;">
	
	<select id="projet" name="projet" style="font-size:13px;">'.$listeprojets.'</select>
	<button type="submit" style="font-size:13px;">Valider</button>
	</form>	<b>Ou:</b>
	<a href="statscustom.php">Stats Personnalisées</a></p>
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Raport User
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menurapport='
	<div id="sousmenu">
	<form method="post" action="rapportuser.php" style="display:inline;">
	<p><b>Menu du rapport utilisateur:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>

	<button type="submit" style="height:24px;">Valider</button>
	(*Obligatoires)
	</p>
	</form>
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Réalisé
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menurealise='
	<div id="sousmenu"><p>
	<form method="post" action="totalrealise.php" style="display:inline;">
	<b>Réalisé:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</form> |
	<form method="post" action="totalrealiseuser.php" style="display:inline;">
	<b>Réalisé Annuelle:</b>
	<select id="chuti" name="chuti" style="height:25px;">
	'.$listeusers.'
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</form>
	</p>
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Congés
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menuconges='
	<div id="sousmenu">
	<form method="post" action="conges.php" style="display:inline;">
	<p><b>Menu Congés:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</p>
	</form>
 	|
	<form method="post" action="congesuser.php" style="display:inline;">
	<p>Menu utilisateur:
		<select id="chmois" name="chmois" style="height:24px;">
			<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
			<option value="'.$moisactuel.'">---------</option>
			<option value="1">Janvier</option>
			<option value="2">Fevrier</option>
			<option value="3">Mars</option>
			<option value="4">Avril</option>
			<option value="5">Mai</option>
			<option value="6">Juin</option>
			<option value="7">Juillet</option>
			<option value="8">Aout</option>
			<option value="9">Septembre</option>
			<option value="10">Octobre</option>
			<option value="11">Novembre</option>
			<option value="12">Decembre</option>
		</select>
		<select id="channee" name="channee" style="height:24px;">
			<option value="'.(date("Y")).'">'.(date("Y")).'</option>
			<option value="'.(date("Y")).'">---------</option>
			<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
			<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
			<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
			<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
			<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
		</select>
		<select id="chuti" name="chuti" style="height:25px;">
			'.$listeusersconges.'
		</select>
		<button type="submit" style="height:24px;">Valider</button>
	</p>
	</form> 
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Contrôle
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menucontrole='
	<div id="sousmenu">
	<form method="post" action="controle.php" style="display:inline;">
	<p><b>Menu Contrôle:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>* 
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>*
	<select id="chuti" name="chuti" style="height:25px;">
	'.$listeusers.'
	</select>*
	<button type="submit" style="height:24px;">Valider</button>
	(*Obligatoires)
	</p>
	</form>
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Compteurs
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menucompteurs='
	<div id="sousmenu">
		<p>Cette page vous permet de visualiser les différents compteurs  de congés de l\'ensemble des salariés.</p>
	</div>
';
$menuuserinfos='
	<div id="sousmenu">
		<b>Menu Information des utilisateurs:</b>
		<form method="post" action="infosuser.php" style="display:inline;">
			<p>
				<select id="chuti" name="chuti" style="height:25px;">
				'.$listeusers.'
				</select>
				<button type="submit" style="height:24px;">Valider</button>
			</p>
		</form>
	</div>
';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Administration
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menuadmin='
	<div id="sousmenu">
		<p>Cette page vous permet d\'acceder aux différentes options d\'administrations.</p>
	</div>
';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Administration
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menufrais = '
	<div id="sousmenu"><p>
	<form method="post" action="frais.php" style="display:inline;">
	<b>Menu des Frais:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</form>
	| 
	<p><a href="statsfrais.php">Tableau Frais</a></p> |
	<p><a href="statsfraisdetail.php">Tableau Frais (Détails)</a></p> |
	<p><a href="statsfraisperso.php">Tableau Personnalisé (Heures et Frais)</a></p>
	</div>
';
$menuheuresupp ='
<div id="sousmenu">
	<form method="post" action="heuresupp.php" style="display:inline;">
	<p><b>Menu Heures Supplémentaires ou à Rattraper:</b>
	<select id="chmois" name="chmois" style="height:24px;">
	<option value="'.$moisactuel.'">'.$moisactuelmot.'</option>
	<option value="'.$moisactuel.'">---------</option>
	<option value="1">Janvier</option>
	<option value="2">Fevrier</option>
	<option value="3">Mars</option>
	<option value="4">Avril</option>
	<option value="5">Mai</option>
	<option value="6">Juin</option>
	<option value="7">Juillet</option>
	<option value="8">Aout</option>
	<option value="9">Septembre</option>
	<option value="10">Octobre</option>
	<option value="11">Novembre</option>
	<option value="12">Decembre</option>
	</select>
	<select id="channee" name="channee" style="height:24px;">
	<option value="'.(date("Y")).'">'.(date("Y")).'</option>
	<option value="'.(date("Y")).'">---------</option>
	<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
	<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
	<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
	<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
	<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</p>
	</form>
	</div>
';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//								Menu Administration
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$menurecap='
	<div id="sousmenu">
		<p>Cette page vous permet de visualiser les différents compteurs  de Frais et congés exceptionnels de l\'ensemble des salariés.</p>
	</div>
';
$menuetat='
	<div id="sousmenu">
		<p>Cette page vous permet de visualiser les dernières entrées nécessitant une administration.</p>
	</div>
';
$menubugtracker='
	<div id="sousmenu">
		<p>Cette page vous permet de visualiser les Avis et Problèmes sur OBM.</p>
	</div>
';

$flprecedent = '<img src="../img/fleche1.png" title="Précedent" />';
$flsuivant = '<img src="../img/fleche2.png" title="Suivant"/>';
?>
