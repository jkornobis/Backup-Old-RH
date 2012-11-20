<?php
$doctype ='
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>'.$titlepage.'</title>
<link rel="stylesheet" media="screen" type="text/css" href="style.css"/>
<link rel="stylesheet" media="print" type="text/css" href="print.css"/>
<link rel="shortcut icon" href="../favicon.ico">
</head>
<body>
';

$menuprincipale = '
<div id="menuprincipale">
<img src="../img/logo.png" width="180px" alt="logo" onclick="window.location.href=\'etat.php\'" title="Mon résumé"/>
<b>Gestion:</b>
<ul>
<li><a href="monrealiseannuel.php">Mon Réalisé</a></li>
<li><a href="mesconges.php">Mes Congés</a></li>
<li><a href="mesfrais.php">Mes Frais</a></li>
</ul>
<b>Informations:</b>
<ul>
<li><a href="messtats.php">Mes Statistiques</a></li>
<li><a href="controle.php">Contrôle des Saisies</a></li>
<li><a href="bugtrackerindex.php">Avis sur OBM</a></li>
<li><a href="roadmap.php">Évolution d\'OBM</a></li>
</ul>
<b>Options:</b>
<ul>
<li><a href="javascript:window.print()">Imprimer la Page</a></li>
<li><a href="?deminfos=1">Besoin d\'aide ?</a>
</li>
<li><a href="logout.php">Retour à OBM</a></li>
</ul>
'.$accesadmin.'
</ul>
</div>
';
//<li><a href="monresumer.php?uti='.$utilisateur.'">Mon Rapport</a></li>

$menustats= '<div id="sousmenu">
<form method="get" action="messtats.php?uti='.$utilisateur.'">
<p><b>Choix du Mois et de l\'Année :</b>
<select id="chmois" name="chmois" style="height:24px;">
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
<option value="13">Année entière</option>
</select>
<select id="channee" name="channee" style="height:24px;">
<option value="'.(date(Y)+0).'">'.(date(Y)).'</option>
<option value="'.(date(Y)+0).'">---------</option>
<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
</select>
<input type="hidden" name="uti" value="'.$utilisateur.'"/>
<button type="submit" style="height:24px;">Valider</button>
</p>
</form>
</div>
';

$menurealise= '<div id="sousmenu">
<form method="get" action="monrealise.php?uti='.$utilisateur.'">
<p>
<select id="chmois" name="chmois" style="height:24px;">
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
<option value="13">Année entière</option>
</select>
<select id="channee" name="channee" style="height:24px;">
<option value="'.(date(Y)+0).'">'.(date(Y)).'</option>
<option value="'.(date(Y)+0).'">---------</option>
<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
</select>
<input type="hidden" name="uti" value="'.$utilisateur.'"/>
<button type="submit" style="height:24px;">Valider</button>
</p>
</form>
</div>
';

$menucontrole= '<div id="sousmenu">
<form method="get" action="controle.php?uti='.$utilisateur.'">
<p><b>Choix du Mois et de l\'Année :</b>
<select id="chmois" name="chmois" style="height:24px;">
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
<option value="13">Année entière</option>
</select>
<select id="channee" name="channee" style="height:24px;">
<option value="'.(date(Y)+0).'">'.(date(Y)).'</option>
<option value="'.(date(Y)+0).'">---------</option>
<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
</select>
<input type="hidden" name="uti" value="'.$utilisateur.'"/>
<button type="submit" style="height:24px;">Valider</button>
</p>
</form>
</div>
';

$menufrais= '<div id="sousmenu"><p>
<form method="get" action="mesfrais.php?uti='.$utilisateur.'" style="display:inline;">
<b>Mes Frais:</b>
<select id="chmois" name="chmois" style="height:24px;">
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
<option value="13">Année entière</option>
</select>
<select id="channee" name="channee" style="height:24px;">
<option value="'.(date(Y)+0).'">'.(date(Y)).'</option>
<option value="'.(date(Y)+0).'">---------</option>
<option value="'.($anneeactuel-2).'">'.($anneeactuel-2).'</option>
<option value="'.($anneeactuel-1).'">'.($anneeactuel-1).'</option>
<option value="'.($anneeactuel+0).'">'.($anneeactuel+0).'</option>
<option value="'.($anneeactuel+1).'">'.($anneeactuel+1).'</option>
<option value="'.($anneeactuel+2).'">'.($anneeactuel+2).'</option>
</select>
<input type="hidden" name="uti" value="'.$utilisateur.'"/>
<button type="submit" style="height:24px;">Valider</button>
</form>
</p>
</div>
';

$menuconges='
<div id="sousmenu">
<p><b>Les congés sur toute l\'année '.$anneeactuel.': </b></p>
</div>
';

$menuresumer='
<div id="sousmenu">
<p style="font-size:24px;line-height:24px;"><b>En travaux ! (Certaines données sont factices pour la phase de test)</b></p>
</div>
';

$menudemande='
<div id="sousmenu">
<p>Cette page va vous permettre de demander des frais de déplacements ainsi que les frais Annexes.</p>
</div>
';
$menuheuresdemande='
<div id="sousmenu">
<p>Cette page va vous permettre de demander les heures complémentaire ou à rattraper.</p>
</div>
';
$menuroadmap='
<div id="sousmenu">
<p style="font-size:24px;line-height:24px;"><b>Cette page vous affiche les évolutions de OBM: (OBM et les Modules MLA)</b></p>
</div>
';
$menuchat='
<div id="sousmenu">
<p style="font-size:24px;line-height:24px;"><b>Cette page vous affiche la messagerie direct OBM:</b></p>
</div>
';
$menuetat='<div id="sousmenu">
<p style="font-size:24px;line-height:24px;">Résumé de vos informations concernant OBM.</p>
</div>';
$menubugtracker='<div id="sousmenu">
<p style="font-size:24px;line-height:24px;">Ici vous pouvez voir les avis que vous avez poster sur OBM.</p>
</div>';

$flprecedent = '<img src="../img/fleche1.png" title="Précedent" alt="precedent"/>';
$flsuivant = '<img src="../img/fleche2.png" title="Suivant" alt="suivant"/>';
?>
