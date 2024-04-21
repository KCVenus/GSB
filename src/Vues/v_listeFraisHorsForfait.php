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
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; 
            ?>   
                
            <form method="post" action="index.php?uc=validerFrais&action=majFraisHorsForfait&id=<?php echo $id ?>" role="form">
            <tr>

                <?php 
                    if ($_SESSION['role']=="Comptable"){
                        echo 
                            '<td> ' .
                            ' <input type="date" name="lesDates[' . $id. ']" value="'. Utilitaires::dateFrancaisVersAnglais($date).'"></input></td>'. 
                            '<td><input name="lesLibelles[' . $id. ']" value="'. $libelle .'"></input></td>' .
                            '<td><input name="lesMontants[' . $id. ']" value="'. $montant .'"></input></td>';
                    }
                    else{
                        echo '<td>'.$date .'</td>'.
                            '<td>'.$libelle.'</td>' .
                            '<td>'.$montant.'</td>';
                    }
                ?>

            <td>
                <?php 
                    if ($_SESSION['role']=="Comptable" ){
                        echo '<button class="btn btn-success" type="submit" value="corriger" name="action">Corriger</button>' 
                        . '<button class="btn btn-warning" type="reset">Réinitialiser</button>  '
                        .'</form>'
                                
                        . '<a href="index.php?uc=validerFrais&action=refuserOuReporter&idFrais='. $id .'" > <button class="btn btn-danger" >Refuser ou reporter </button> </a>';

                    }
                    else {
                        echo '<a href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=' . $id 
                        .'"  onclick="return confirm(' . "'" . 'Voulez-vous vraiment supprimer ce frais?' . "')" 
                        .' ;" >supprimer</a>';
                    }
                    
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

<?php 
    if($_SESSION['role']=='Comptable'){
        echo '<div class="row">Nombre de justificatifs : <input class="nb-justificatifs" value='
        .$nbJustificatifs.'></input></div>';
    }
?>

<form method="post" action="index.php?uc=validerFrais&action=validerFicheFrais" role="form">
    <button type="submit" name="validerFicheFrais" value="valider" class="btn btn-danger">Valider</button>
</form>


<?php if($_SESSION['role']=='Visiteur'){
    ?>
  
    <div class="row">
    <h3>Nouvel élément hors forfait</h3>
    <div class="col-md-4">
        <form action="index.php?uc=gererFrais&action=validerCreationFrais" 
              method="post" role="form">
            <div class="form-group">
                <label for="txtDateHF">Date (jj/mm/aaaa): </label>
                <input type="date" id="txtDateHF" name="dateFrais" 
                       class="form-control" id="text">
            </div>
            <div class="form-group">
                <label for="txtLibelleHF">Libellé</label>             
                <input type="text" id="txtLibelleHF" name="libelle" class="form-control" id="text">
            </div> 
            <div class="form-group">
                <label for="txtMontantHF">Montant : </label>
                <div class="input-group">
                    <span class="input-group-addon">€</span>
                    <input type="text" id="txtMontantHF" name="montant" class="form-control" value="">
                </div>
            </div>
            <button class="btn btn-success" type="submit">Ajouter</button>
            <button class="btn btn-danger" type="reset">Effacer</button>
        </form>
    </div>
</div>
    <?php
    ;} ?>

