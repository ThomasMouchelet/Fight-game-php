<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/11/2017
 * Time: 18:05
 */

namespace Controller;

use Entity\Character;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Extension_Debug;
use PDO;
use Modele\Manager;

class Controller
{
    private $db;

    public function __construct()
    {
        $db= new PDO('mysql:host=localhost;dbname=fightphp', 'root', 'root');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

        $this->setDb($db);
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function render($view, $data = [])
    {
        $loader = new Twig_Loader_Filesystem('app/views');
        $twig = new Twig_Environment($loader, array(
            'debug' => true
        ));
        $twig->addExtension(new Twig_Extension_Debug());

        return $twig->render($view, $data);
    }

    public function getManager(){
        $manager = new Manager($this->getDb());

        return $manager;
    }

}