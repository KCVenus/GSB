<?php

/**
 * Gestion des frais
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idUtilisateur = $_SESSION['idUtilisateur'];
$role = $_SESSION['role'];

switch ($action) {
   case 'selectionnerMoisComptable':
        $lesMois = $pdo->getLesMoisCloturesDisponibles();
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        include PATH_VIEWS . 'v_listeMoisComptable.php';
        
    break;
    case 'voirEtatFrais':
       //menu déroulant visiteurs:
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['idVisiteur'] = $idVisiteur;

        //menu déroulant mois:
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['leMois'] = $leMois;
        $lesMois = $pdo->getLesMoisCloturesDisponibles();
        include PATH_VIEWS . 'v_listeMoisComptable.php';
        
       
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        if(!$lesInfosFicheFrais){
            Utilitaires::ajouterErreur("Aucune fiche de frais n'est à valider pour ce visiteur. Veuillez-en choisir un autre");
            include PATH_VIEWS . 'v_erreurs.php';
        }
        else{
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            require PATH_VIEWS . 'v_listeFraisForfait.php';
            require PATH_VIEWS . 'v_listeFraisHorsForfait.php';
            
        }
    break;
        
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        //récupérer le mois à afficher et l'id du visiteur
        $idVisiteur = $_SESSION['idVisiteur'] ;
        $leMois = $_SESSION['leMois'];
        if (Utilitaires::lesQteFraisValides($lesFrais)) {
            //modifier les frais:
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
            
            //puis recharger la page : 
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            $lesVisiteurs = $pdo->getNomsVisiteurs();
            $lesMois = $pdo->getLesMoisCloturesDisponibles();  
            $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
            $actionBtnsHF= ($role=='Comptable') ?'<button class="btn btn-success" type="submit">Corriger</button><button class="btn btn-danger" type="reset">Réinitialiser</button>' : 'supprimer';
            include PATH_VIEWS . 'v_listeMoisComptable.php';
            require PATH_VIEWS . 'v_listeFraisForfait.php';
            require PATH_VIEWS . 'v_listeFraisHorsForfait.php';
        } else {
            Utilitaires::ajouterErreur('Les valeurs des frais doivent être numériques');
            include PATH_VIEWS . 'v_erreurs.php';
        }
    break;
        
    
}