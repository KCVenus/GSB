<?php

/**
 * Gestion de la connexion
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
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeConnexion':
        include PATH_VIEWS . 'v_connexion.php';
        break;
    case 'valideConnexion':
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       
        $statut = $pdo->getStatutUtilisateur($login, $mdp);
     
        $employe = $pdo->getInfosUtilisateur($login, $mdp, $statut);
        
       

        if (!is_array($employe)) {

            Utilitaires::ajouterErreur('Login ou mot de passe incorrect');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_connexion.php';
        } else {
           
            $id = $employe['id'];
            $nom = $employe['nom'];
            $prenom = $employe['prenom'];
            Utilitaires::connecter($id, $nom, $prenom, $statut);
            header('Location: index.php'); // a commenter
        }
        
        
        
        break;
        
        //----------------Passage à l'authentification a deux facteurs :------------------------------
        
        //case 'valideConnexion': 
        
        //$login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$visiteur = $pdo->getInfosVisiteur($login, $mdp);
        
        //if (!password_verify($mdp,$pdo->getMdpVisiteur($login))) {
        //    Utilitaires::ajouterErreur('Login ou mot de passe incorrect');
        //    include PATH_VIEWS . 'v_erreurs.php';
        //    include PATH_VIEWS . 'v_connexion.php';
            
        //} else {
         //   $id = $visiteur['id'];
           // $nom = $visiteur['nom'];
           // $prenom = $visiteur['prenom'];
            
         //   Utilitaires::connecter($id, $nom, $prenom);
            //header('Location: index.php');
            
        //    $email = $visiteur['email'];
        //    $code = rand(1000, 9999);
        //    $pdo->setCodeA2f($id,$code);
        //    mail($email, '[GSB-AppliFrais] Code de vérification', "Code : $code");
        //    include PATH_VIEWS . 'v_code2facteurs.php';
        //}
        
    //break;
    //
    //
        //case 'valideA2fConnexion':
        //$login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$visiteur = $pdo->getInfosVisiteur($login, $mdp);
        
        //$code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_NUMBER_INT);
        //if ($pdo->getCodeVisiteur($_SESSION['idVisiteur']) !== $code) {
         //   Utilitaires::ajouterErreur('Code de vérification incorrect');
         //    $id = $visiteur['id'];
            //rajouter 1 au nb de tentatives
         //   $nbTentatives = $pdo->getNbTentatives($id)+1;
            //mettre à jour la valeur en bdd
        //    $pdo->updateNbTentatives($id, $nbTentatives);

         //   if($nbTentatives >3){
         //       Utilitaires::ajouterErreur('Code de vérification incorrect');
          //      header('Location: index.php');
         //       $pdo->setNbTentatives($id, 0);
         //   }
          //  include PATH_VIEWS . 'v_erreurs.php';
        //    include PATH_VIEWS . 'v_code2facteurs.php';
        //} else {
        //    Utilitaires::connecterA2f($code);
        //    header('Location: index.php');
        //}
        //break;
        //
        //------------------------------------------------------------------------------------//
    default:
        include PATH_VIEWS . 'v_connexion.php';
        break;
}
