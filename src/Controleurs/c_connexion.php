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
        $role = $pdo->getRoleUtilisateur($login);
        $utilisateur = $pdo->getInfosUtilisateur($login,$role);
       
        if(!($login && $mdp)){
            Utilitaires::ajouterErreur('Veuillez saisir un login et un mot de passe');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_connexion.php';
            }
        else if (!password_verify($mdp,$pdo->getMdpUtilisateur($login))) {
            Utilitaires::ajouterErreur('Login ou mot de passe incorrect');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_connexion.php';
            
        } else {
            $id = $utilisateur['id'];
            $nom = $utilisateur['nom'];
            $prenom = $utilisateur['prenom'];
            Utilitaires::connecter($id, $nom, $prenom, $role);     
            header('Location: index.php');
//            $email = $utilisateur['email'];
//            $code = rand(1000, 9999);
//            $pdo->setCodeA2f($id,$code);
//            mail($email, '[GSB-AppliFrais] Code de vérification', "Code : $code");
//            include PATH_VIEWS . 'v_accueil.php';
        }
        
        break;
    
//    case 'valideA2fConnexion':
//
//        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_NUMBER_INT);
//        if ($pdo->getCodeUtilisateur($_SESSION['idUtilisateur']) !== $code) {
//            Utilitaires::ajouterErreur('Code de vérification incorrect');
//            include PATH_VIEWS . 'v_erreurs.php';
//            include PATH_VIEWS . 'v_code2facteurs.php';
//        } else {
//            Utilitaires::connecterA2f($code);
//            header('Location: index.php');
//        }
//        break;
    default:
        include PATH_VIEWS . 'v_connexion.php';
        break;
}
