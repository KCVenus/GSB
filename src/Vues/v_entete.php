 <?php

/**
 * Vue Entête
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

if ( !isset($_SESSION['role']) || $_SESSION["role"] == "Visiteur" ){
    $icon = 'pencil';
    $icon1 = 'list-alt';
}
elseif($_SESSION["role"] == "Comptable"){
    $icon = 'euro';
    $icon1 = 'ok';}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="./styles/style.css" rel="stylesheet">
        <?php 
            if(isset($_SESSION['role']) && $_SESSION["role"] == "Comptable")
                {echo '<link href="./styles/style_comptable.css" rel="stylesheet">';}
        ?>
    </head>
    <body>  
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($estConnecte) {
                ?>
            <div class="header">
                <div class="row vertical-align">
                    <div class="col-md-4">
                        <h1>
                            <img src="./images/logo.jpg" class="img-responsive" 
                                 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                 title="Laboratoire Galaxy-Swiss Bourdin">
                        </h1>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-pills pull-right " role="tablist">
                            <li <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?> >
                                <a href="index.php">
                                    <span class="glyphicon glyphicon-home"></span>
                                    Accueil
                                </a>
                            </li>
                            
                            <li <?php if ($uc == 'gererFrais') { ?>class="active"<?php } ?>>
                                <a 
                                   <?php if($_SESSION["role"] == "Visiteur"){echo 'href="index.php?uc=gererFrais&action=saisirFrais"';}
                                  elseif($_SESSION["role"] == "Comptable"){echo 'href="index.php?uc=validerFrais&action=selectionnerVisiteur"';} 
                                    ?>
                                    >
                                    <span class="glyphicon glyphicon-<?php echo $icon ?>"></span>
                                    <?php if($_SESSION["role"] == "Visiteur"){echo "Renseigner la fiche de frais";}
                                            elseif($_SESSION["role"] == "Comptable"){echo " Valider les fiches de frais";} 
                                    ?> 
                                </a>
                            </li>
                            <li <?php if ($uc == 'etatFrais') { ?>class="active"<?php } ?>>
                                <a 
                                   
                                   
                                   <?php if($_SESSION["role"] == "Visiteur"){echo 'href="index.php?uc=etatFrais&action=selectionnerMois"';}
                                  elseif($_SESSION["role"] == "Comptable"){echo 'href="index.php?uc=suivreFrais&action=selectionnerVisiteur"';} 
                                    ?>>
                                    <span class="glyphicon glyphicon-<?php echo $icon1 ?>"></span>
                                     <?php if($_SESSION["role"] == "Visiteur"){echo "Afficher mes fiches de frais";}
                                            elseif($_SESSION["role"] == "Comptable"){echo "Suivre le paiement des fiches de frais";}
                                    ?> 
                                </a>
                            </li>
                            <li 
                            <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            } else {
                ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
                <?php
            }
         
            
            