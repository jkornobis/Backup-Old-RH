<?php
require_once('config.php');
require_once('tests.php');
require_once('menus.php');

echo $doctype.$menuprincipale.$menuheuresdemande;

/*////////////////////////////////////////////////////////////////////////////////////////////////////
				Requètes et Formulaires
///////////////////////////////////////////////////////////////////////////////////////////////////*/

for($optionjours = 01; $optionjours <= 31; $optionjours ++ ){
	if($optionjours == date('d')){
		$optionjoursaffichage .='<option selected value="'.($optionjours).'">'.($optionjours).'</option>';
	}else{
		$optionjoursaffichage .='<option value="'.($optionjours).'">'.($optionjours).'</option>';
	}
}

$formulaire='<br/>
<fieldset><legend>Demande d\'Heure Supplémentaire: </legend>
	<form method="post" action="meshsupp.php">
		<p>
		<input type="hidden" name="note1uti" id="note1uti" value="'.$utilisateur.'">
		Date de l\'heure supplémentaires demandé: <br/>
		<select id="note1jour" name="note1jour" style="height:22px;">
			'.$optionjoursaffichage.'
		</select>
		<select id="note1mois" name="note1mois" style="height:22px;">
			<option value="'.$moisactuel.'">'.$moisactuelmot.'</option><option value="01">---------</option>
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
		</select><input type="hidden" name="note1annee" id="note1annee" value="'.(date(Y)).'"/> <b>'.(date(Y)).'</b>
		<br/><br/>
		Temps demandé (en Heure): <input type="text" name="note1temps" id="note1temps" size="3"/>
		<br/><br/>
		Raison:<br/><textarea cols="55" rows="2" name="note1raison" id="note1raison"></textarea> 
		<br/><br/>
		<button type="submit" style="height:22px;">Valider la Demande de Frais</button>
		</p>
	</form>
</fieldset>
';

$formulaire2='<br/>
<fieldset><legend>Demande d\'Heure à Rattraper: </legend>
	<form method="post" action="meshsupp.php?uti='.$utilisateur.'">
		<p>
		<input type="hidden" name="note2uti" id="note2uti" value="'.$utilisateur.'">
		Date de l\'heure à rattraper demandé: <br/>
		<select id="note2jour" name="note2jour" style="height:22px;">
			'.$optionjoursaffichage.'
		</select>
		<select id="note2mois" name="note2mois" style="height:22px;">
			<option value="'.$moisactuel.'">'.$moisactuelmot.'</option><option value="01">---------</option>
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
		</select><input type="hidden" name="note2annee" id="note2annee" value="'.(date(Y)).'"/> <b>'.(date(Y)).'</b>
		<br/><br/>
		Temps demandé (en Heure): <input type="text" name="note2temps" id="note2temps" size="3"/>
		<br/><br/>
		Raison:<br/><textarea cols="55" rows="2" name="note2raison" id="note2raison"></textarea> 
		<br/><br/>
		<button type="submit" style="height:22px;">Valider la Demande de Frais</button>
		</p>
	</form>
</fieldset>
';
/*////////////////////////////////////////////////////////////////////////////////////////////////////
				Traitement et Affichages
///////////////////////////////////////////////////////////////////////////////////////////////////*/
echo '
		<div id="contentheures"><br/><br/>
			<div id="deplacements">
			'.$formulaire.'
			</div>
			<div id="annexes">
			'.$formulaire2.'
			</div>
		</div><br/>
';
echo'</html>';
?>
