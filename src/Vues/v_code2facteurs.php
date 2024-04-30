<?php
/**
 * Vue d'identification à deux facteurs
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Annabelle Hantrais
 * @author    Arthur Demoisson
 * @copyright 2024 Réseau CERTA
 * @license   Réseau CERTA
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */
?>

<div class="alert alert-info" role="alert">Un email contenant un code à 4 chiffres vous a été envoyé, merci de le saisir ici...</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Identification utilisateur - Authentification à deux facteurs (A2F)</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="post" 
                      action="index.php?uc=connexion&action=valideA2fConnexion">
                    <fieldset>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-qrcode"></i>
                                </span>
                                <input class="form-control" placeholder="Code"
                                       name="code" type="text" maxlength="4">
                            </div>
                        </div>
                        <input class="btn btn-lg btn-success btn-block"
                               type="submit" value="Se connecter">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>