<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 11/02/2017
 * Time: 13:32
 */

class PersonnagesManager
{
    private $_db; // Instance de PDO

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function add(Personnage $perso)
    {
        $q = $this->_db->prepare('INSERT INTO personnages(nom,hp) VALUES(:nom,:hp)');
        $q->bindValue(':nom', $perso->getNom());
        $q->bindValue(':hp', $perso->setHp(100));
        $q->execute();

        $perso->hydrate([
            'id' => $this->_db->lastInsertId(),
        ]);
    }

    public function count()
    {
        return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }

    public function delete(Personnage $perso)
    {
        $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->getId());
    }

    public function exists($info)
    {
        if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
        }

        // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.

        $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
        $q->execute([':nom' => $info]);

        return (bool) $q->fetchColumn();
    }

    public function get($info)
    {
        if (is_int($info))
        {
            $q = $this->_db->query('SELECT id, nom, hp FROM personnages WHERE id = '.$info);
            $donnees = $q->fetch(PDO::FETCH_ASSOC);

            return new Personnage($donnees);
        }
        else
        {
            $q = $this->_db->prepare('SELECT id, nom, hp FROM personnages WHERE nom = :nom');
            $q->execute([':nom' => $info]);

            return new Personnage($q->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function getList($nom)
    {
        $persos = [];

        $q = $this->_db->prepare('SELECT id, nom, hp FROM personnages WHERE nom <> :nom ORDER BY nom');
        $q->execute([':nom' => $nom]);

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $persos[] = new Personnage($donnees);
        }

        return $persos;
    }

    public function update(Personnage $perso)
    {
        $q = $this->_db->prepare('UPDATE personnages SET hp = :hp WHERE id = :id');

        $q->bindValue(':hp', $perso->getHp(), PDO::PARAM_INT);
        $q->bindValue(':id', $perso->getId(), PDO::PARAM_INT);

        $q->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}