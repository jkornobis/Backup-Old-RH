<?php
require_once('config.php');
$titlepage = "Acceuil Administration - Module Administrateur";
require_once('menus.php');
echo $doctype.$menuprincipale.$menustats;

echo '<div id="content"><h2>Page d\'Accueil de l\'Admnistration: '.date("d/m/Y").'</h2>'.$texteformation ;

echo '
	<div style="float:left;width:600px;">
	<fieldset style="width:550px;">
	<legend style="padding-left:20px;padding-right:20px;">Administration des Réalisés</legend>
	<form method="post" action="totalrealise.php" style="display:inline;">
	<h3>Selectionner un mois sur l\'ensemble des utilisateurs:</h3>
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
	<form method="post" action="totalrealiseuser.php" style="display:inline;">
	<h3>Selectionner le réalisé annuel d\'un utilisateur:</h3>
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
	</fieldset>
	<br/>
	<fieldset style="width:550px;">
	<legend style="padding-left:20px;padding-right:20px;"> Administration des Frais
	</legend>
	<form method="post" action="frais.php" style="display:inline;">
	<h3>Menu des Frais:</h3>
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
	<h3>Tableaux des Frais:</h3>
	<p><a href="statsfrais.php">Consulter</a></p>
	<h3>Tableau des Frais détaillés: (utilisateurs identifiés):</h3>
	<p><a href="statsfraisdetail.php">Consulter</a></p>
	<h3>Tableau Personnalisé (Heures d\'absences et Frais Km / Divers):</h3>
	<p><a href="statsfraisperso.php">Consulter</a></p>
	</fieldset>
	</div>';

//////////////////////////////////////////////////////////////////////////////////
//											2eme Div pour frais
//////////////////////////////////////////////////////////////////////////////////

	echo '<div>
	<fieldset style="width:550px;">
	<legend style="padding-left:20px;padding-right:20px;"> Administration des Congés</legend>
	<form method="post" action="conges.php" style="display:inline;">
	<h3>Selectionner un mois de congés:</h3>
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
 	<h3>Selectionner Directement un utilisateur:</h3>
	<form method="post" action="congesuser.php" style="display:inline;">
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
	</fieldset>
	<br/>
	<fieldset style="width:550px;">
	<legend style="padding-left:20px;padding-right:20px;">Administration Avancé</legend>
	<h3>Contrôle des événements: </h3>
	<form method="post" action="controle.php" style="display:inline;">
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
	'.$listeusers.'
	</select>
	<button type="submit" style="height:24px;">Valider</button>
	</p>
	</form>
	<h3>États des Compteurs de Congés aujourd\'hui: </h3>
	<p><a href="resumerhr.php">Consulter</a></p>
	<h3>Informations sur les utilisateurs et Outils Avancés: </h3>
	<p><a href="fspadpage.php">Consulter</a></p>
	<h3>Gestion des Avis sur OBM: </h3>
	<p><a href="bugtrackeradmin.php">Consulter</a></p>
	</fieldset>
	';
	echo '</div></div></body></html>';
?>
