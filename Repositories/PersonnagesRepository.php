<?php

//  //SQL Table code
// CREATE TABLE `personnages` (
//     `id` INT NOT NULL AUTO_INCREMENT ,
//     `nom` VARCHAR(32) NOT NULL ,
//     `forcePerso` INT NULL ,
//     `degats` INT NULL ,
//     `niveau` INT NULL ,
//     `experience` INT NULL ,
// PRIMARY KEY (`id`)) ENGINE = InnoDB;

class PersonnagesRepository
{
    private $_db; // Instance de PDO

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

    public function __construct($db)
    {
        $this->setDb($db);
    }

    //CREATE
    public function add(Personnage $perso)
    {
        $q = $this->_db->prepare('INSERT INTO personnages(nom, forcePerso, degats, niveau, experience) VALUES(:nom, :forcePerso, :degats, :niveau, :experience)');

        $q->bindValue(':nom', $perso->nom());
        $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
        $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
        $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
        $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);

        $result = $q->execute();
        
        if(!$result) {
          echo "<br>PDOStatement::errorInfo() dans la fonction add(Personnage) :<br><pre>";
          $arr = $q->errorInfo();
          print_r($arr);
          echo "</pre>";
        }

        return $result;
    }

    //READs
    public function get($id)
    {
        $id = (int) $id;

        $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages WHERE id = ' . $id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Personnage($donnees);
    }

    public function getList()
    {
        $persos = [];

        $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages ORDER BY nom');

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) {
            $persos[] = new Personnage($donnees);
        }

        return $persos;
    }

    //UPDATE
    public function update(Personnage $perso)
    {
        $q = $this->_db->prepare('UPDATE personnages SET forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id');

        $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
        $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
        $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
        $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
        $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

        $result = $q->execute();

        if(!$result) {
          echo "<br>PDOStatement::errorInfo() dans la fonction update(Personnage) :<br><pre>";
          $arr = $q->errorInfo();
          print_r($arr);
          echo "</pre>";
        }

        return $result;
    }

    //DELETE
    public function delete(Personnage $perso)
    {
        $this->_db->exec('DELETE FROM personnages WHERE id = ' . $perso->id());
    }
}
