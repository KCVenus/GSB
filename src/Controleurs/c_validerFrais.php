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
        $_SESSION['montantFicheFrais'] = 0;
        $prixKm=$pdo->getFraisKmByVisiteur($idVisiteur);

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
    
    case 'majFraisHorsForfait':
        $idFrais = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $lesLibelles = filter_input(INPUT_POST, 'lesLibelles', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $lesDates = filter_input(INPUT_POST, 'lesDates', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $lesMontants = filter_input(INPUT_POST, 'lesMontants', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $libelle = $lesLibelles[$idFrais];
        $date = $lesDates[$idFrais];
        $montant = $lesMontants[$idFrais];
        $idVisiteur = $_SESSION['idVisiteur'];
        $leMois = $_SESSION['leMois'];
        $prixKm=$pdo->getFraisKmByVisiteur($idVisiteur);

        $pdo->majFraisHorsForfait($idVisiteur, $leMois, $libelle, $date, $montant, $idFrais);
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesVisiteurs = $pdo->getNomsVisiteurs();
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
            ?>
            <script>alert("<?php echo htmlspecialchars('Votre fiche de frais a bien été corrigée ! ', ENT_QUOTES); ?>")</script>
            <?php
        }
       
        
    break;
    
    case 'refuserOuReporter':
        
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idVisiteur = $_SESSION['idVisiteur'];
        $leMois = $_SESSION['leMois'];
        $montant=$_SESSION['montantFicheFrais'];
        
         echo ' 
        <div class="alert alert-warning" role="alert">
         <p><h4>Voulez vous refuser ou reporter le frais?<br></h4>
         <a href="index.php?uc=validerFrais&action=refuser&idFrais=' .$idFrais .'">refuser</a>  ou '
        . '<a href="index.php?uc=validerFrais&action=reporter&idFrais=' . $idFrais 
        .'&montant=' .$montant .'&mois=' .$leMois .
        '">reporter</a>
       </div> ';
        
        
    break;
    
    
    
    case 'refuser':
   
        $idFrais = intval(filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_NUMBER_INT));

        $pdo->refuserFraisHorsForfait($idFrais);
        
        echo '<div class="alert alert-warning" role="alert">
              <p> Le frais hors forfait sélectionné à été refusé. <a href = "index.php?uc=validerFrais&action=voirEtatFrais">Cliquez ici</a>
                pour revenir à la selection.</p>
            </div> ';


    break;
        
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        //récupérer le mois à afficher et l'id du visiteur
        $idVisiteur = $_SESSION['idVisiteur'] ;
        $leMois = $_SESSION['leMois'];
        $prixKm=$pdo->getFraisKmByVisiteur($idVisiteur);
        if (Utilitaires::lesQteFraisValidesNombre($lesFrais)) {
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
    

    case 'reporter':

        $idVisiteur = $_SESSION['idVisiteur'];
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_NUMBER_INT);
        $leMois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //    $montant = filter_input(INPUT_GET, 'montant', FILTER_SANITIZE_NUMBER_INT);
        //    $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $moisSuivant = Utilitaires::getMoisSuivant($leMois);

        if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)) {
          $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
        }
        $moisReporter = $pdo->reporterFraisHF($idFrais, $leMois);

            echo '<div class="alert alert-warning" role="alert">
              <p> Le frais hors forfait sélectionné à été reporté au mois suivant. <a href = "index.php?uc=validerFrais&action=voirEtatFrais">Cliquez ici</a>
                pour revenir à la selection.</p>
            </div> ';

	
    break;

    case 'validerFicheFrais':
        //recup FicheFrais
        $idVisiteur = $_SESSION['idVisiteur'];
        $leMois = $_SESSION['leMois'];
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);       
        // mettre à jour le montantValide de la fiche de frais (somme des montants forfait et hors forfait)
        $montantValide = $_SESSION['montantFicheFrais'];
        $pdo->majMontantValideFicheFrais($idVisiteur,$leMois,$montantValide);
        //mettre à jour l'état à 'VA':
        $idEtat='VA';
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, $idEtat);  
         echo '<div class="alert alert-warning" role="alert">
      <p> La fiche de frais à été validée. <a href = "index.php?uc=validerFrais&action=voirEtatFrais">Cliquez ici</a>
        pour revenir à la selection.</p>
    </div> ';
    
    break;
           
}