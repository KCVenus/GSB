<?php

/**
 * Suivi des frais
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

    case 'voirEtatFrais':
        //menu déroulant visiteurs:
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        include PATH_VIEWS . 'v_listeVisiteurs.php';

        $idVisiteur = $_SESSION['idVisiteur'];

        $prixKm = $pdo->getFraisKmByVisiteur($idVisiteur);

        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['leMois'] = $leMois;
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        if (!$lesInfosFicheFrais) {
            Utilitaires::ajouterErreur("Aucune fiche de frais n'est à valider pour ce visiteur. Veuillez-en choisir un autre");
            include PATH_VIEWS . 'v_erreurs.php';
        } else {

            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $etat = $lesInfosFicheFrais['idEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            require PATH_VIEWS . 'v_etatFrais.php';
        }
        break;

    case 'voirFichesFrais':
        //menu déroulant visiteurs:
        $lesVisiteurs = $pdo->getNomsVisiteurs();
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $_SESSION['idVisiteur'] = $idVisiteur;
        $visiteur = $pdo->getInfosUtilisateurFromId($idVisiteur);
        include PATH_VIEWS . 'v_listeVisiteurs.php';

        $nomVisiteur = $visiteur['prenom'] . ' ' . $visiteur['nom'];
        $fichesFrais = array_merge($pdo->getLesInfosFicheFraisByEtat($idVisiteur, 'VA'),
        $pdo->getLesInfosFicheFraisByEtat($idVisiteur, 'MP'));

        if (empty($fichesFrais)) {
            Utilitaires::ajouterErreur("Aucune fiche de frais n'est à valider pour ce visiteur. Veuillez-en choisir un autre");
            include PATH_VIEWS . 'v_erreurs.php';
        } else {


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

    case 'RBouMPFicheFrais':
        //recup FicheFrais
        $idVisiteur = $_SESSION['idVisiteur'];
        $leMois = $_SESSION['leMois'];
        $idEtat = filter_input(INPUT_POST, 'RBouMPFicheFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($idEtat == 'MP') {
            $etat = 'mise en paiement';
        } else if ($idEtat == 'RB') {
            $etat = 'remboursée';
        }
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        //mettre à jour l'état à 'RB' ou 'MP' et enregistrer la dateModif:
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, $idEtat);

        echo '<div class="alert alert-warning" role="alert">
      <p> La fiche de frais à été' . $etat . ' . <a href = "index.php?uc=suivreFrais&action=selectionnerVisiteur">Cliquez ici</a>
        pour revenir à la selection.</p>
    </div> ';

        break;
}