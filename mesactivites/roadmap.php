<?php
require_once('config.php');
require_once('tests.php');
$titlepage = "Évolution d'OBM - Module Personnel";
require_once('menus.php');
echo $doctype.$menuprincipale.$menuroadmap;

$nompage = 'roadmap.php';
if($formation == true){
	$texteformation = '<div id="overlay" class="overlay" onclick="window.location.href=\''.$nompage.'\'"><fieldset class="formation" style="height:auto;"><legend style="padding-left:20px;padding-right:20px;"><b>Formation:</b> <i>"Évolution d\'OBM"</i></legend>
<p style="font-size:15px;font-weight:normal;">
Bienvenue sur la page <i>"Évolution d\'OBM"</i> de votre module Personnel.<br/>
Cette Page vous présente toutes les évolutions d\'OBM depuis son lancement. Elle vous informe sur les 2 modules: <i>"Personnel"</i> et <i>"Administrateur"</i> afin de vous donner une vision globale.
</p>
</fieldset></div>';
}

echo '<div id="contentadmin">'.$texteformation;
echo '<h2>Principe:</h2> <h3 style="margin-bottom:-10px;margin-top:-10px;">0.1 -> 0.2: correction de bugs, nouvelles pages ou fonctionnalités.<br/> 0.3 -> 1.0: version majeure des modules.</h3>';

function Date_ConvertSqlTab($date_sql) {
    $jour = substr($date_sql, 8, 2);
    $mois = substr($date_sql, 5, 2);
    $annee = substr($date_sql, 0, 4);
    $heure = substr($date_sql, 11, 2);
    $minute = substr($date_sql, 14, 2);
    $seconde = substr($date_sql, 17, 2);
    
    $key = array('annee', 'mois', 'jour', 'heure', 'minute', 'seconde');
    $value = array($annee, $mois, $jour, $heure, $minute, $seconde);
    
    $tab_retour = array_combine($key, $value);
    
    return $tab_retour;
}

function AuPluriel($chiffre) {
    if($chiffre>1) {
        return 's';
    };
}

function TimeToJourJ($date_sql) {
    $tab_date = Date_ConvertSqlTab($date_sql);
    $mkt_jourj = mktime($tab_date['heure'],
                    $tab_date['minute'],
                    $tab_date['seconde'],
                    $tab_date['mois'],
                    $tab_date['jour'],
                    $tab_date['annee']);

    $mkt_now = time();
    
    $diff = $mkt_jourj - $mkt_now;
    
    $unjour = 3600 * 24;
    
    if($diff>=$unjour) {
        // EN JOUR
        $calcul = $diff / $unjour;
        return '<strong>'.ceil($calcul).' jour'.AuPluriel($calcul).'</strong>.';

    } elseif($diff<$unjour && $diff>=0 && $diff>=3600) {
        // EN HEURE
        $calcul = $diff / 3600;
        return '<strong>'.ceil($calcul).' heure'.AuPluriel($calcul).'</strong>.';

    } elseif($diff<$unjour && $diff>=0 && $diff<3600) {
        // EN MINUTES
        $calcul = $diff / 60;
        return '<strong>'.ceil($calcul).' minute'.AuPluriel($calcul).'</strong>.';

    } elseif($diff<0 && abs($diff)<3600) {
        // DEPUIS EN MINUTES
        $calcul = abs($diff) / 60;
        return 'Depuis <strong>'.ceil($calcul).' minute'.AuPluriel($calcul).'</strong>.';

    } elseif($diff<0 && abs($diff)<=3600) {
        // DEPUIS EN HEURES
        $calcul = abs($diff) / 3600;
        return 'Depuis <strong>'.ceil($calcul).' heure'.AuPluriel($calcul).'</strong>.';        

    } else {
        // DEPUIS EN JOUR
        $calcul = abs($diff) / $unjour;
        return 'Depuis <strong>'.ceil($calcul).' jour'.AuPluriel($calcul).'</strong>.';

    };
}
// EXEMPLE //
// Affiche le temps restant jusqu'au 01 janvier prochain //
$next_jourdelan = date('Y') + 1;
/*//////////////////////////////////////////////////////////////////////
							LOG MODULE PERNONNEL
//////////////////////////////////////////////////////////////////////*/
echo '<div id="deplacements" style="width:500px;"><h2>Module Personnel</h2>';
//    Rapport 10/10/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 10/10/2011</legend>
<h4>Version Module Personnel: 3.0 </h4>
<p>
- Intégration d\'une structure de formation dans le module personnel.<br/>
- J-'.TimeToJourJ($next_jourdelan.'-01-01 00:00:00').'
<br/>
</p>
</fieldset>';
//    Rapport 07/10/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 07/10/2011</legend>
<h4>Version Module Personnel: 2.4 </h4>
<p>
- Structuration du menu du module pour gagner en lisibilité.<br/>
- Modification en profondeur de la gestion des RH prit en compte dans le module.<br/>
- Ajout de la Modification des frais. (avec le bouton: <img src="../img/editer.png" width="30px" alt="logo" title="bouton édition"/>) <br/>
<br/>
</p>
</fieldset>';
//    Rapport 07/09/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 07/09/2011</legend>
<h4>Version Module Personnel: 2.1 </h4>
<p>
- Nouvelle page Avis sur OBM: "retour" de ma messagerie technique plus fonctionnel comprenant un aspect demande d\'évolution.
<br/>
- Ajout de la modification du titre de la page visitée.
</p>
</fieldset>';
//    Rapport 12/07/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 12/07/2011</legend>
<h4>Version Module Personnel: 2.0 </h4>
<p>
- Destruction de session du module personnel lors du retour sur OBM.(suite à plusieurs problèmes-bizarries signalés).<br/>
- Nouvelle page Mon Résumé: pour visualiser toutes les dernières informations de votre compte (congés, frais, érreurs)
</p>
</fieldset>';
//    Rapport 04/07/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 04/07/2011</legend>
<h4>Version Module Personnel: 1.9 </h4>
<p>
- Retrait de la page: Messagerie Technique d\'OBM <br/>(Préférez le Mail).<br/>
- Passage du XHTML au HTML5 (nouveaux boutons, ombres, etc...).<br/>
- Amélioration de la fonction Imprimer la Page.
</p>
</fieldset>';
//    Rapport 12/05/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 12/05/2011</legend>
<h4>Version Module Personnel: 1.8 </h4>
<p>
- Nouvelle page: Messagerie Technique d\'OBM.<br/>
- Sécurisation du système avec des sessions PHP.<br/>
- Rajout du nom et prénom et de la date permettant <br/> l\'impression de la page.<br/>
</p>
</fieldset>';
//    Rapport 12/04/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 12/04/2011</legend>
<h4>Version Module Personnel: 1.5 </h4>
<p>
- Amélioration du Réalisé (3ème tableau).<br/>
</p>
</fieldset>';
//    Rapport 31/03/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 31/03/2011</legend>
<h4>Version Module Personnel: 1.4 </h4>
<p>
- Déploiement de la nouvelle page Réalisé Annuelle.<br/>
- Déploiement des nouvelles pages Frais et Heures.<br/>
- Déploiement de la nouvelle page Évolution d\'OBM.<br/>
</p>
</fieldset>';
//    Rapport 14/03/2011
echo '<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 14/03/2011</legend>
<h4>Version Module Administrateur: 0.5 -> 1.0</h4>
<p>
- Déploiement de la nouvelle interface.<br/>
- Re-structuration du code sous forme modulaire. <br/>
- Réorganisation de l\'arborescence du module.<br/>
- Création de fichiers thèmes (CSS + dossier IMG centralisant les themes de chacun des modules).

</p>
</fieldset>';
//    Rapport 03/03/2011
echo '<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 01/01/2011</legend>
<h4>Version Module Personnel: 0.1</h4>
<p>
- Déploiement des nouveaux projets colororisés.<br/>
- Création et réglages du compte secrétaire pour gérer les maladie et congés exceptionnel.
</p>
</fieldset>';
//    Rapport 01/01/2011
echo '<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 01/01/2011</legend>
<h4>Version Module Personnel: 0.1</h4>
<p>
- Lancement d\'OBM.<br/>
- Déploiement du module Personnel: 0.1
</p>
</fieldset>';
/*//////////////////////////////////////////////////////////////////////
							LOG MODULE ADMIN
//////////////////////////////////////////////////////////////////////*/
echo '</div><div id="annexes"><h2>Module Administrateur</h2>';
//    Rapport 29/09/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 29/09/2011</legend>
<h4>Version Module Administrateur: 2.7 </h4>
<p>
- Nouvelle page statistique d\'heures d\'abscences et de frais.<br/>
- Page congés globale et individuelle.<br/>
- Mise en place des nouvelles contraintes de gestion des utilisateurs.<br/>
</p>
</fieldset>';
//    Rapport 29/09/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 29/09/2011</legend>
<h4>Version Module Administrateur: 2.4 </h4>
<p>
- Nouvelle page statistique de frais.<br/>
- Nouvelle page statistique détaillée de frais.<br/>
- Nouvelle page statistique personnalisée.
<br/>
</p>
</fieldset>';
//    Rapport 29/09/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 29/09/2011</legend>
<h4>Version Module Administrateur: 2.4 </h4>
<p>
- Nouvelle page statistique de frais.<br/>
- Nouvelle page statistique détaillée de frais.<br/>
- Nouvelle page statistique personnalisée.
<br/>
</p>
</fieldset>';
//    Rapport 07/09/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 07/09/2011</legend>
<h4>Version Module Administrateur: 2.1 </h4>
<p>
- Nouvelle page Avis sur OBM: "retour" de ma messagerie technique plus fonctionnel comprenant un aspect demande d\'évolution.
<br/>
- Ajout de la modification du titre de la page visitée.
</p>
</fieldset>';
//    Rapport 07/06/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 07/06/2011</legend>
<h4>Version Module Administrateur: 2.0 </h4>
<p>
- La session Admin PHP est interconnectée au module personnel afin de mettre en place l\'attribution de l\'administration des comptes<br/>
</p>
</fieldset>';
//    Rapport 12/05/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 12/04/2011</legend>
<h4>Version Module Administrateur: 1.7 </h4>
<p>
- Page informations sur le serveur.<br/>
- Sécurisation du système avec une session Admin PHP.<br/>
</p>
</fieldset>';
//    Rapport 12/04/2011
echo'
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 12/04/2011</legend>
<h4>Version Module Administrateur: 1.6 </h4>
<p>
- Page informations sur les utilisateurs.<br/>
</p>
</fieldset>';
//    Rapport 31/03/2011
echo '
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 31/03/2011</legend>
<h4>Version Module Administrateur: 1.5</h4>
<p>
- Déploiement de la nouvelle page Réalisé Annuelle.<br/>
- Déploiement des nouvelles pages Frais et Heures.<br/>
</p>
</fieldset>';
//    Rapport 14/03/2011
echo '
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 14/03/2011</legend>
<h4>Version Module Administrateur: 0.7 -> 1.0</h4>
<p>
- Déploiement de la nouvelle interface.<br/>
- Re-structuration du code sous forme modulaire. <br/>
- Réorganisation de l\'arborescence du module.<br/>
- Création de fichiers thèmes (CSS + dossier IMG centralisant les themes de chacun des modules).
</p>
</fieldset>';
//    Rapport 01/01/2011
echo '
<fieldset style="height:auto;width:480px;"><legend style="font-size:18px;">Rapport 01/01/2011</legend>
<h4>Version Module Administrateur: 0.1</h4>
<p>
- Lancement d\'OBM.<br/>
- Déploiement du module Administrateur: 0.1
</p>
</fieldset>';
echo '</div></div></body></html>';
?>
