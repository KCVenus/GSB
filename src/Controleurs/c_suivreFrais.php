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
    case 'selectionnerVisiteur':
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        include PATH_VIEWS . 'v_listeVisiteurs.php';
        
    break;

//    case 'selectionnerMois':
//        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $lesMois = $pdo->getLesMoisCloturesDisponibles($idVisiteur);
//        $lesVisiteurs = $pdo->getNomsVisiteurs();
//        include PATH_VIEWS . 'v_listeMoisComptable.php';
//        
//    break;

    case 'voirEtatFrais':
       //menu déroulant visiteurs:
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['idVisiteur'] = $idVisiteur;
        $visiteur =$pdo->getInfosUtilisateurFromId($idVisiteur);
        
        $nomVisiteur = $visiteur['prenom'].' '.$visiteur['nom'];

         $fichesFrais = array_merge($pdo->getLesInfosFicheFraisByEtat($idVisiteur, 'VA'),
                    $pdo->getLesInfosFicheFraisByEtat($idVisiteur, 'RB'),
                    $pdo->getLesInfosFicheFraisByEtat($idVisiteur, 'CL'));

//            var_dump($fichesFrais);
        if(empty($fichesFrais)){
            Utilitaires::ajouterErreur("Aucune fiche de frais n'est à valider pour ce visiteur. Veuillez-en choisir un autre");
            include PATH_VIEWS . 'v_erreurs.php';
        }
        else{
            
           
//            $numAnnee = substr($leMois, 0, 4);
//            $numMois = substr($leMois, 4, 2);
//            $libEtat = $lesInfosFicheFrais['libEtat'];
//            $montantValide = $lesInfosFicheFrais['montantValide'];
//            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
//            $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
//            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
//            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
//            require PATH_VIEWS . 'v_listeFraisForfait.php';
            require PATH_VIEWS . 'v_suiviFicheFrais.php';    
        }
    break;
    
    
    
    
    
  
           
}