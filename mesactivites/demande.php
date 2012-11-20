<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Demande de Frais - Module Personnel";
require_once('menus.php');

echo $doctype.$menuprincipale.$menudemande;

/*////////////////////////////////////////////////////////////////////////////////////////////////////
				Requètes et  infos
///////////////////////////////////////////////////////////////////////////////////////////////////*/

/*///		Tableaux des lieus		///*/
$fraisdeplieu=$connexion->query('
	SELECT * 
	FROM  `FraisDepLieu` 
	
;');
$fraisdeplieu->setFetchMode(PDO::FETCH_OBJ);
$tablelieu = '<table style="width:300px;font-size:13px;"><tr><th>Lieu</th><th>Km</th></tr>';
while( $ligne = $fraisdeplieu->fetch() ) {
	$tablelieu .= '<tr><td>'.$ligne->fraisdeplieu_nom.'/'.$ligne->fraisdeplieu_km.'</td><td style="width:70px;">'.$ligne->fraisdeplieu_km.' Km</td></tr>';
}

$tablelieu .= '</table>';

/*///		Tableaux des voitures		///*/

$fraisdepvoiture=$connexion->query('
	SELECT * 
	FROM `FraisDepVoiture`
;');
$fraisdepvoiture->setFetchMode(PDO::FETCH_OBJ);
$tablevoiture = '<table style="width:160px;font-size:13px;"><tr><th>Puissance</th><th>Taux</th></tr>';
while( $ligne = $fraisdepvoiture->fetch() ) {
		if($ligne->fraisdepvoiture_puissance == $cvsession){
			if($ligne->fraisdepvoiture_puissance == 7){
							$tablevoiture .= '<tr><td>'.$ligne->fraisdepvoiture_puissance.' CV et +</td><td>'.$ligne->fraisdepvoiture_tarif.' €</td></tr>';
							$optioncv .='<option value="'.$ligne->fraisdepvoiture_tarif.'" selected="selected">'.$ligne->fraisdepvoiture_puissance.' Cv et + : Soit '.$ligne->fraisdepvoiture_tarif.'€ par Km</option>';
						}else{
							$tablevoiture .= '<tr><td>'.$ligne->fraisdepvoiture_puissance.' CV</td><td>'.$ligne->fraisdepvoiture_tarif.' €</td></tr>';
							$optioncv .='<option value="'.$ligne->fraisdepvoiture_tarif.'" selected="selected">'.$ligne->fraisdepvoiture_puissance.' Cv : Soit '.$ligne->fraisdepvoiture_tarif.'€ par Km</option>';
					}
		}else{
			if($ligne->fraisdepvoiture_puissance == 7){
				$tablevoiture .= '<tr><td>'.$ligne->fraisdepvoiture_puissance.' CV et +</td><td>'.$ligne->fraisdepvoiture_tarif.' €</td></tr>';
				$optioncv .='<option value="'.$ligne->fraisdepvoiture_tarif.'">'.$ligne->fraisdepvoiture_puissance.' Cv et + : Soit '.$ligne->fraisdepvoiture_tarif.'€ par Km</option>';
			}else{
				$tablevoiture .= '<tr><td>'.$ligne->fraisdepvoiture_puissance.' CV</td><td>'.$ligne->fraisdepvoiture_tarif.' €</td></tr>';
				$optioncv .='<option value="'.$ligne->fraisdepvoiture_tarif.'">'.$ligne->fraisdepvoiture_puissance.' Cv : Soit '.$ligne->fraisdepvoiture_tarif.'€ par Km</option>';
		}
	}
}
$tablevoiture .= '</table>';
/*///		Titre		///*/
$titre = '<h2>Informations pratiques:</h2>';

/*///			Form Requètes et Formulaire			///*/

for($optionjours = 01; $optionjours <= 31; $optionjours ++ ){
	if($optionjours == date('d')){
		$optionjoursaffichage .='<option selected value="'.($optionjours).'">'.($optionjours).'</option>';
	}else{
		$optionjoursaffichage .='<option value="'.($optionjours).'">'.($optionjours).'</option>';
	}
}

$fraistype =$connexion->query('
	SELECT  `eventcategory1_label` ,  `eventcategory1_code` 
	FROM  `EventCategory1` 
	WHERE  `eventcategory1_code` <  "699"
	OR `eventcategory1_code` = "907"
	OR `eventcategory1_code` = "908"
	ORDER BY  `eventcategory1_code` 
;');
$fraistype->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $fraistype->fetch() ) {
	$optiontype .='<option value="'.$ligne->eventcategory1_code.'">'.$ligne->eventcategory1_label.'</option>';
}

$fraislieu=$connexion->query('
	SELECT * 
	FROM  `FraisDepLieu` 
	ORDER BY `fraisdeplieu_nom` ASC
;');
$fraislieu->setFetchMode(PDO::FETCH_OBJ);
/*//////////////////////////////////////////////////////////////////////////////////////////////*/

while( $ligne = $fraislieu->fetch() ) {
	$labelsauv = $label;
	$labelbrute= explode("-", $ligne->fraisdeplieu_nom);
	$label = $labelbrute['0'];

	if ($labelsauv == null){
		$optionlieu .='<optgroup label="'.$label.'">';	
	}else{
		if ($labelsauv != $label){
			$optionlieu .='</optgroup>';
			$optionlieu .='<optgroup label="'.$label.'">';
		}else{
			$optionlieu .='<option value="'.$ligne->fraisdeplieu_nom.'/'.$ligne->fraisdeplieu_km.' label="'.$ligne->fraisdeplieu_nom.'">'.$ligne->fraisdeplieu_nom.' ('.$ligne->fraisdeplieu_km.' Km)</option>';
		}
	}
}
$optionlieu .='</optgroup>';

$formulaire='<br/>
<fieldset><legend>Note de Frais de Déplacement: </legend>
	<p>
	<form method="POST" action="mesfrais.php">
		<input type="hidden" name="note1uti" id="note1uti" value="'.$utilisateur.'">
		Date: 
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
		 Type de Frais: 
		<select id="note1type" name="note1type" style="height:22px;">
			'.$optiontype.'
		</select>
		<fieldset style="height:auto;"><legend>Lieu:</legend>Choisir entre le menu déroulant: 
		 <select id="note1lieu1" name="note1lieu1" style="height:22px;">
			'.$optionlieu.'
		</select><br/><br/>
		<u>Ou</u> remplir les 2 champs :<input type="text" name="note1lieu2" id="note1lieu2" value="Ville départ - Ville arrivé"/> Kms: 
		<input type="text" name="note1lieu3" id="note1lieu3" size="3"/>
		</fieldset><br/>
		Puissance de la Voiture (Fiscale): 
		<select id="note1cv" name="note1cv" style="height:22px;">
			'.$optioncv.'
		</select>
		<br/><br/>
		Raison:<br/><textarea cols="55" rows="2" name="note1raison" id="note1raison"></textarea> 
		<br/><br/>
		<button type="submit" style="height:22px;">Valider la Demande de Frais</button>
	</form>
	</p>
</fieldset>
';

$formulaire2='<br/>
<fieldset><legend>Note de Frais Annexes: </legend>
	<form method="POST" action="mesfrais.php?uti='.$utilisateur.'">
		<span style="font-size:16px;color:red;font-weight:bold;">Tous les champs sont obligatoires ! </span><br/><p>
		<input type="hidden" name="note2uti" id="note2uti" value="'.$utilisateur.'">
		Date: 
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
		 Type de Frais: 
		<select id="note2type" name="note2type" style="height:22px;">
			'.$optiontype.'
		</select><br/><br/>
		Lieu: <input type="text" name="note2lieu" id="note2lieu"/>
		<br/><br/>
		Raison:<br/><textarea cols="55" rows="2" name="note2raison"></textarea> 
		<br/><br/>
		Prix: <input type="text" name="note2prix" id="note2prix" size="5"/>€
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
		<div id="contentadmin">
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
