<?php

/**
 * Hachage des mots de passe
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Arthur Demoisson
 * @author    Annabelle Hantrais
 * 
 */

$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'userGsb', 'secret');
$pdo->query('SET CHARACTER SET utf8');

$requetePrepare = $pdo->prepare(
       'SELECT utilisateur.id AS id, utilisateur.mdp as mdp '
       . 'FROM utilisateur '
   );
$requetePrepare->execute();
$utilisateurs = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);

foreach ($utilisateurs as $utilisateur) {
    $mdp = $utilisateur['mdp'];
    $login = $utilisateur['id'];
    
    $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);
    $req = $pdo->prepare("UPDATE utilisateur SET mdp= :hashMdp  WHERE id= :unId ");
    $req->bindParam(':unId',$login, PDO::PARAM_STR);
    $req->bindParam(':hashMdp',$hashMdp, PDO::PARAM_STR);
    $req->execute();

}
            

