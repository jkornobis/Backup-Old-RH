<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Modification de Frais - Module Personnel";
require_once('menus.php');

echo $doctype.$menuprincipale.$menudemande;

/*////////////////////////////////////////////////////////////////////////////////////////////////////
				Requètes et  infos
///////////////////////////////////////////////////////////////////////////////////////////////////*/
$modif = $connexion->query('
	SELECT *
	FROM `EventCategory1`, `FraisEvent`, `UserObm`
	WHERE `fraisevent_catcode` = `eventcategory1_code`
	AND `userobm_id` = `fraisevent_userobmid`
	AND `fraisevent_id` = "'.$_GET['fraisid'].'"
	;');
$modif->setFetchMode(PDO::FETCH_OBJ);
while( $ligne = $modif->fetch() ) {

	$datebrute = $ligne->fraisevent_date;
	
	list($annee,$mois,$jour)=sscanf($datebrute,"%d-%d-%d");
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
	$typenote = $ligne->fraisevent_note;
	$labelfrais= $ligne->eventcategory1_label;

for($optionjours = 01; $optionjours <= 31; $optionjours ++ ){
			$optionjoursaffichage .='<option value="'.($optionjours).'">'.($optionjours).'</option>';
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
	while( $type = $fraistype->fetch() ) {
		$optiontype .='<option value="'.$type->eventcategory1_code.'">'.$type->eventcategory1_label.'</option>';
	}

	if($typenote == "1"){

	///////////////// Puissance voiture ///////////////////////////////

		$fraisdepvoiture=$connexion->query('
			SELECT * 
			FROM `FraisDepVoiture`
		;');
		$fraisdepvoiture->setFetchMode(PDO::FETCH_OBJ);
		$tablevoiture = '<table style="width:160px;font-size:13px;"><tr><th>Puissance</th><th>Taux</th></tr>';
		while( $champs = $fraisdepvoiture->fetch() ) {
			if( $champs->fraisdepvoiture_tarif == $ligne->fraisevent_cv){
				$optioncv .='<option value="'.$champs->fraisdepvoiture_tarif.'" selected="selected">'.$champs->fraisdepvoiture_puissance.' Cv : Soit '.$champs->fraisdepvoiture_tarif.'€ par Km</option>';
			}else{
				$optioncv .='<option value="'.$champs->fraisdepvoiture_tarif.'" >'.$champs->fraisdepvoiture_puissance.' Cv : Soit '.$champs->fraisdepvoiture_tarif.'€ par Km</option>';
			}
		}
		$tablevoiture .= '</table>';
		///////////////// Lieux ///////////////////////////////
		$fraislieu=$connexion->query('
			SELECT * 
			FROM  `FraisDepLieu` 
			ORDER BY `fraisdeplieu_nom` ASC
		;');
		$fraislieu->setFetchMode(PDO::FETCH_OBJ);
		/*//////////////////////////////////////////////////////////////////////////////////////////////*/

		while( $lieu = $fraislieu->fetch() ) {
			$labelsauv = $label;
			$labelbrute= explode("-", $lieu->fraisdeplieu_nom);
			$label = $labelbrute['0'];
	
			if ($lieu->fraisdeplieu_nom == $ligne->fraisevent_lieu){
				$optionlieu .='<option value="'.$lieu->fraisdeplieu_nom.'/'.$lieu->fraisdeplieu_km.' label="'.$lieu->fraisdeplieu_nom.'" selected="selected">'.$lieu->fraisdeplieu_nom.' ('.$lieu->fraisdeplieu_km.' Km)</option>';
			}else{
				if ($labelsauv == null){
					$optionlieu .='<optgroup label="'.$label.'">';	
				}else{
					if ($labelsauv != $label){
						$optionlieu .='</optgroup>';
							$optionlieu .='<optgroup label="'.$label.'">';
					}else{
						$optionlieu .='<option value="'.$lieu->fraisdeplieu_nom.'/'.$lieu->fraisdeplieu_km.' label="'.$lieu->fraisdeplieu_nom.'">'.$lieu->fraisdeplieu_nom.' ('.$lieu->fraisdeplieu_km.' Km)</option>';
					}
				}
			}
		}
		$optionlieu .='</optgroup>';

		$formulairelieu = '
				<fieldset style="height:auto;"><legend>Lieu:</legend>Choisir entre le menu déroulant: 
				<select id="note1lieu1" name="note1lieu1" style="height:25px;">
					'.$optionlieu.'
				</select><br/><br/>
				<u>Ou</u> remplir les 2 champs :<input type="text" name="note1lieu2" id="note1lieu2" value="Ville départ - Ville arrivé"/> Kms: 
				<input type="text" name="note1lieu3" id="note1lieu3" size="3"/>
				</fieldset>
				Puissance de la Voiture (Fiscale): 
				<select id="note1cv" name="note1cv" style="height:25px;">
					'.$optioncv.'
				</select>
				Raison:<br/><textarea cols="55" rows="2" name="note1raison" id="note1raison">'.$ligne->fraisevent_raison.'</textarea> <br/>
			';
	}else{
		$formulairelieu = '
			<br/><br/>
			Lieu:<input type="text" name="note1lieu1" id="note1lieu1" value="'.$ligne->fraisevent_lieu.'"/>
			<br/><br/>
			Raison:<br/><textarea cols="55" rows="2" name="note1raison">'.$ligne->fraisevent_raison.'</textarea> 
			<br/><br/>
			Prix: <input type="text" name="uti2prix" id="uti2prix" size="5" value="'.$ligne->fraisevent_prix.'"/>€
			<br/>
		';
	}

	$formulaire='<h2>Ici vous pouvez modifier vos frais:</h2>
	<fieldset style="height:auto; width:60%;"><legend>Modifier une note de frais:</legend>
		<p>
		<form method="POST" action="mesfrais.php">
			<input type="hidden" name="updateuti" id="updateuti" value="'.$utilisateur.'">
			<input type="hidden" name="fraisid" id="fraisid" value="'.$_GET['fraisid'].'">
			Date: 
			<select id="note1jour" name="note1jour" style="height:25px;">
				<option value="'.$jour.'">'.$jour.'</option>
				<option value="'.$jour.'">----</option>
				'.$optionjoursaffichage.'
			</select>
			<select id="note1mois" name="note1mois" style="height:25px;">
				<option value="'.$mois.'">'.$moismot.'</option>
				<option value="01">---------</option>
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
			<select id="note1type" name="note1type" style="height:25px;">
				<option value="'.$ligne->eventcategory1_code.'">'.$labelfrais.'</option>
				<option value="'.$ligne->eventcategory1_code.'">-----------------------</option>
				'.$optiontype.'
			</select>
			'.$formulairelieu.'
			<br/>
			<button type="submit" style="height:25px;">Valider la Modification de la Demande de Frais</button>
		</form>
		</p>
	</fieldset>
	';
}
/*////////////////////////////////////////////////////////////////////////////////////////////////////
				Traitement et Affichages
///////////////////////////////////////////////////////////////////////////////////////////////////*/
echo '
		<div id="contentadmin">
			'.$formulaire.'
		</div><br/>
';
echo'</html>';
?>
