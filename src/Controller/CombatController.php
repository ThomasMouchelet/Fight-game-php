<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/11/2017
 * Time: 12:40
 */

namespace Controller;

use Controller\Controller;

class CombatController extends Controller
{
    public function indexAction(){

        $manager = $this->getManager();
        $chars = $manager->getCharacters();
        $userChar = $_SESSION['nickname'];

        return $this->render('index.twig', ['chars'=>$chars,'userChar'=>$userChar]);
    }

    public function fightAction(){

        $manager = $this->getManager();

        $id = (int) $_GET['frapper'];

        $frappeur = $manager->getCharacter($_SESSION['nickname']);
        $frappe = $manager->getCharacter($id);

        $frappeur->fight($frappe);
        $manager->update($frappe);
        $chars = $manager->getCharacters();
        $userChar = $_SESSION['nickname'];

        return $this->render('index.twig', ['chars'=>$chars,'userChar'=>$userChar]);
    }

}