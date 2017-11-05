<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/11/2017
 * Time: 15:55
 */

namespace Controller;

use Controller\Controller;

class LoginController extends  Controller
{
    public function indexAction()
    {
        return $this->render('login.twig');
    }

    public function createAction()
    {

        $manager = $this->getManager();

        $_SESSION['nickname'] = $_POST['nickname'];

        $char = new Character(['nickname'=>$_POST['nickname']]);
        $manager->addChar($char);

        $chars = $manager->getCharacters();
        $userChar = $_SESSION['nickname'];

        return $this->render('index.twig', ['chars'=>$chars,'userChar'=>$userChar]);
    }

    public function disconect(){

        session_destroy();

        return $this->render('login.twig');
    }
}