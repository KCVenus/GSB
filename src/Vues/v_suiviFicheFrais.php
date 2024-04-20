<?php
use Outils\Utilitaires;
/**
 * Vue Liste des frais hors forfait
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>


<hr>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Fiches de frais validées</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="visiteur">Visiteur</th>
                    <th class="mois">Mois</th>  
                    <th class="jsutificatifs">Justificatifs</th>  
                    <th class="montantValide">Montant validé</th> 
                    <th class="dateModif">Date modification</th> 
                    <th class="etat">Etat</th> 
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($fichesFrais as $ficheFrais) {
               $mois=$ficheFrais['mois'];
               $nbJustificatifs=$ficheFrais['nbJustificatifs'];
               $montantValide=$ficheFrais['montantValide'];
               $dateModif=$ficheFrais['dateModif'];
               $libEtat = $ficheFrais['libEtat'];
            ?>   
                
            <form method="post" action="index.php?uc=suivreFrais&action=afficherSuiviFicheFrais" role="form">
            <tr>

                <?php 
                  
                        echo 
                            '<td> ' . $nomVisiteur . '</td>'. 
                            '<td>' . $mois . '</td>' .
                            '<td>' . $nbJustificatifs . '</td>'.
                              '<td>' . $montantValide . '</td>' .
                                '<td>' . $dateModif . '</td>'.
                                '<td>' . $libEtat . '</td>';
                    
                ?>

            <td>
                <?php 
                echo 'afficher cette fiche';
//                    if ($_SESSION['role']=="Comptable"){
//                        echo '<button class="btn btn-success" type="submit" value="corriger" name="action">Corriger</button>' 
//                        . '<button class="btn btn-warning" type="reset">Réinitialiser</button>  '
//                        .'</form>'
//                                
//                        . '<a href="index.php?uc=validerFrais&action=refuserOuReporter&idFrais='. $id .'" > <button class="btn btn-danger" >Refuser ou reporter </button> </a>';
//
//                    }
//                    else{
//                        echo '<a href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=' . $id 
//                        .'"  onclick="return confirm(' . "'" . 'Voulez-vous vraiment supprimer ce frais?' . "')" 
//                        .' ;" >supprimer</a>';
//                    }
//                ?> 

            </td>
            

            </tr>
            
           
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>




