<?php

/**
 * Vue Accueil
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

if($_SESSION["statut"] == "Comptable"){$icon = 'euro';} elseif ($_SESSION["statut"] == "Visiteur"){$icon = 'pencil';}
if($_SESSION["statut"] == "Comptable"){$icon1 = 'ok';} elseif ($_SESSION["statut"] == "Visiteur"){$icon1 = 'list-alt';}
?>
<div class="alert alert-warning" role="alert"><strong>Rappel : </strong>Vos frais sont à déclarer au plus tard le dernier jour du mois 
	et vos factures acquittées doivent être arrivées aux services comptables au plus tard le 10 du mois suivant la saisie.
	Les éléments reçus après le 10 seront reportés sur le mois suivant.
</div>
<div id="accueil">
    <h2>
        Gestion des frais<small> - <?= $_SESSION['statut'] ?> : 
            <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <a href="index.php?uc=gererFrais&action=saisirFrais"
                           class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-<?php echo $icon ?>"></span>
                            <br><?php if($_SESSION["statut"] == "Visiteur"){echo "Renseigner la fiche de frais";}
                                            elseif($_SESSION["statut"] == "Comptable"){echo "Suivre le paiement des fiches de frais";} 
                                    ?> </a>
                        <a href="index.php?uc=etatFrais&action=selectionnerMois"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-<?php echo $icon1 ?>"></span>
                            <br><?php if($_SESSION["statut"] == "Visiteur"){echo "Afficher mes fiches de frais";}
                                            elseif($_SESSION["statut"] == "Comptable"){echo "Valider les fiches de frais";}
                                    ?> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>