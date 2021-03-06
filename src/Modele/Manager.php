<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/11/2017
 * Time: 13:41
 */

namespace Modele;

use Entity\Character;
use PDO;

class Manager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDb($db);
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->_db = $db;
    }

    public function getCharacters()
    {
        $chars = [];

        $q = $this->getDb()->prepare('SELECT * FROM characters');
        $q->execute();

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $chars[] = new Character($donnees);
        }
        return $chars;
    }

    public function getCharacter($info)
    {
        if (is_int($info))
        {
            $q = $this->getDb()->query('SELECT * FROM characters WHERE id = '.$info);
            $donnees = $q->fetch(PDO::FETCH_ASSOC);

            return new Character($donnees);
        }
        else
        {
            $q = $this->getDb()->prepare('SELECT * FROM characters WHERE nickname = :nickname');
            $q->execute([':nickname' => $info]);

            return new Character($q->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function update(Character $char)
    {
        $q = $this->getDb()->prepare('UPDATE characters SET hp = :hp WHERE id = :id');

        $q->bindValue(':hp', $char->getHp(), PDO::PARAM_INT);
        $q->bindValue(':id', $char->getId(), PDO::PARAM_INT);

        $q->execute();
    }

    public function addChar(Character $char)
    {
        $q = $this->getDb()->prepare('INSERT INTO characters(nickname) VALUES(:nickname)');
        $q->bindValue(':nickname', $char->getNickname());
        $q->execute();

        $char->hydrate([
            'id' => $this->getDb()->lastInsertId(),
            'hp' => 100
        ]);

        $this->update($char);
    }

    public function deleteChar(Character $char)
    {

    }

    public function isChar($info )
    {
        if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM characters WHERE id = '.$info)->fetchColumn();
        }

        // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
        $q = $this->_db->prepare('SELECT COUNT(*) FROM characters WHERE nickname = :nickname');
        $q->execute([':nickname' => $info]);

        return (bool) $q->fetchColumn();
    }
}