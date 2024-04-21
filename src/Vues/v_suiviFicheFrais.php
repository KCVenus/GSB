<?php
use Outils\Utilitaires;
/**
 * Vue Suivi des frais
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
        <div class="panel-heading">Fiches de frais validées ou mises en paiement</div>
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
                $idEtat = $ficheFrais['idEtat'];
            ?>   
                
           
            <tr>
                 <form method="post" action="index.php?uc=suivreFrais&action=voirEtatFrais" role="form">

                <?php 
                  
                        echo 
                            '<td> <input readonly name="nom-visiteur" class="table-input" type="text" value="' . $nomVisiteur . '"</input></td>'. 
                            '<td>  <input readonly name="leMois" class="table-input" type="text" value="' . $mois . '"</input></td>' .
                            '<td>' . $nbJustificatifs . '</td>'.
                              '<td>' . $montantValide . '</td>' .
                                '<td>' . $dateModif . '</td>'.
                                '<td> <input hidden name="etat" type="text" value="' . $idEtat . '"</input>' . $libEtat . '</td>';
                    
                ?>

            <td>
                <?php 
                echo '<a href="index.php?uc=suivreFrais&action=voirEtatFrais"><button class="btn btn-success" type="submit">Afficher</button> </a>  </form>';
               ?> 

            </td>
            

            </tr>
            
           
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>




