<?php

/**
 * Vue Liste des mois
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
<h2>
    <?php if($uc=='suivreFrais'){
                echo 'Suivre le paiement'  ;
            }else if($uc=='validerFrais'){
                 echo 'Valider fiche de frais'  ;
            }
            ?>
    </h2>
<div class="row">

    <div class="col-md-4">
        <form 
            <?php if($uc=='suivreFrais'){
                echo ' action="index.php?uc=suivreFrais&action=voirFichesFrais"  '  ;
            }else if($uc=='validerFrais'){
                 echo ' action="index.php?uc=validerFrais&action=selectionnerMois"  '  ;
            }
            ?>
           
              method="post" role="form">
            <div class="form-group">
                
                <label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                   
                        foreach ($lesVisiteurs as $unVisiteur) {
                            $prenom = $unVisiteur['prenom'];
                            $nom = $unVisiteur['nom'];
                            $id = $unVisiteur['id'];

                                ?>
                                <option value="<?php echo $id ?>" 
                                    <?php if( isset($_SESSION['idVisiteur']) && $id==$_SESSION['idVisiteur']){echo 'selected';} ?> >
                                    <?php echo $nom.' '.$prenom ?> </option>
                                <?php
                        }
                    
                          ?>
                        <option value="//<?php echo $id ?>">
                                    //<?php echo $nom.' '.$prenom ?> </option>
                                //<?php

                    ?>    

                </select>

            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                   role="button">
        </form>
    </div>
</div>