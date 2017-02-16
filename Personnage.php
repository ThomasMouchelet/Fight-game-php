<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 11/02/2017
 * Time: 12:23
 */
class Personnage
{
    private $_id,
            $_Hp,
            $_nom;

    const ITS_ME = 1;
    const KILL = 2;
    const HIT = 3;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnes)
    {
        foreach ($donnes as $key => $value){
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }


    public function fight(Personnage $perso)
    {
        if ($perso->getId() == $this->_id) {
            return self::ITS_ME;
        }
        return $perso->lessHp();
    }

    public function lessHp()
    {
        if ($this->_Hp > 0  ){
            $this->_Hp -= 10;
            return self::HIT;
        }else{
            return self::KILL;
        }
    }

    public function nomValide()
    {
        return !empty($this->_nom);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getHp()
    {
        return $this->_Hp;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->_nom;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $id = (int) $id;
        if ($id>0){
            $this->_id = $id;
        }
    }

    /**
     * @param $hp
     * @internal param mixed $hp
     */
    public function setHp($hp)
    {
        /** @var int $hp */
        $hp = (int) $hp;
        $this->_Hp = $hp;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        if (is_string($nom)){
            $this->_nom = $nom;
        }
    }
}