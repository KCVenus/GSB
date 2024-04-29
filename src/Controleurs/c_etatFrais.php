<?php

/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use Outils\Utilitaires;
use Outils\tfpdf\PDF;


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idVisiteur = $_SESSION['idUtilisateur'];
$role = $_SESSION['role'];
$etat = filter_input(INPUT_GET, 'etat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


switch ($action) {
    case 'selectionnerMois':
        if($role=="Visiteur"){
             $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
             $lesCles = array_keys($lesMois);
            $moisASelectionner = $lesCles[0];
            include PATH_VIEWS . 'v_listeMois.php';
        }
    break;
    
    case 'voirEtatFrais':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        $prixKm=$pdo->getFraisKmByVisiteur($idVisiteur);
        $moisASelectionner = $leMois;
        include PATH_VIEWS . 'v_listeMois.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $etat = $lesInfosFicheFrais['idEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        $_SESSION['leMois']=$leMois;
        include PATH_VIEWS . 'v_etatFrais.php';

        break;
    
    case 'voirpdf':
        if ($etat =='VA'){
            ob_start();
            $pdf = new Modeles\PDF();
            $pdf->AddPage();
            $pdf->CreatePDFEtat();
            ob_end_flush(); // Envoi du contenu du tampon et désactivation de la mise en tampon
            ob_end_clean();
            $pdf->Output();
        }elseif ($etat == 'RB'){
            ob_start();
            $pdf = new Modeles\PDF();
            $pdf->AddPage();
            $pdf->CreatePDFRembourser();
            ob_end_flush(); // Envoi du contenu du tampon et désactivation de la mise en tampon
            ob_end_clean();
            $pdf->Output();
        } else {
            Utilitaires::ajouterErreur('Une erreur est survenue, veuillez recommencer');
            include PATH_VIEWS . 'v_erreurs.php';
        }
        break;

}
