<?php

$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais_b3', 'userGsbB3', 'gsbb3');
$pdo->query('SET CHARACTER SET utf8');

$requetePrepare = $pdo->prepare(
       'SELECT visiteur.id AS id, visiteur.mdp as mdp '
       . 'FROM visiteur '
   );
$requetePrepare->execute();
$visiteurs = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);

foreach ($visiteurs as $visiteur) {
    $mdp = $visiteur['mdp'];
    $login = $visiteur['id'];
    
    $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);
    $req = $pdo->prepare('UPDATE visiteur SET mdp= :hashMdp  WHERE id= :unId ');
    $req->bindParam(':unId',$login, PDO::PARAM_STR);
    $req->bindParam(':hashMdp',$hashMdp, PDO::PARAM_STR);
    $req->execute();

}
            

