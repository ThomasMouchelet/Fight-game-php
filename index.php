<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/11/2017
 * Time: 11:57
 */

require __DIR__.'/vendor/autoload.php';
require 'app/config.php';

use Controller\CombatController;
use Controller\LoginController;

session_start();

// si pas de page ou pas de session ou page = login
if( !isset($_GET['page']) OR $_GET['page'] == 'login') {
    $controller = new LoginController();
    $page =  $controller->indexAction();
}
// Création char
if( isset($_GET['page']) && $_GET['page'] =='create'){
    if(isset($_POST) && !isset($_SESSION['nickname'])){
        $controller = new LoginController();
        $page =  $controller->createAction();
    }else{
        $controller = new CombatController();
        $page =  $controller->indexAction();
    }
}
// si page = combat et session true
if( (isset($_GET['page']) && $_GET['page']=='combat') && isset($_SESSION['nickname']) ){
    $controller = new CombatController();
    $page =  $controller->indexAction();
    if (isset($_GET['frapper'])){
        $page =  $controller->fightAction();
    }
}
//si page = deco
else if( isset($_GET['page']) && $_GET['page']=='deconnexion'){
    $controller = new LoginController();
    $page =  $controller->disconect();
}

if(isset($page)){
    echo $page;
}else{
    echo 'Erreur lors du chargement de la page';
}
