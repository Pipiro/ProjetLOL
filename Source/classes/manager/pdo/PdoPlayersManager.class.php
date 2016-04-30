<?php

require_once 'AbstractPdoManager.class.php';

class PdoPlayersManager extends AbstractPdoManager 
{

	public function getActivesPlayers()
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, name, role, idLol, actif FROM players WHERE actif=1 ORDER BY id ASC LIMIT 5");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$players = new players($result->id, $result->name, $result->role, $result->idLol, $result->actif);
			$results[] = $players;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	public function getIdLolByNamePlayer($name)
	{
		$query = $this->pdo->prepare("SELECT idLol FROM players WHERE name='$name'");
		$query->execute();
		
		$result = $query->fetch(PDO::FETCH_OBJ);
		
		$query->closeCursor();

		return $result->idLol;
	}

	public function getPlayerbyId($id)
	{
		$query = $this->pdo->prepare("SELECT id, name, role, idLol, actif FROM players WHERE id='$id'");
		$query->execute();
		
		$result = $query->fetch(PDO::FETCH_OBJ);
		$player = new players($result->id, $result->name, $result->role, $result->idLol, $result->actif);
		
		$query->closeCursor();

		return $player;
	}

	public function addPlayer($name, $role, $idLol)
	{
		$query = $this->pdo->prepare("INSERT INTO players (name, role, idLol, actif) VALUES ('".$name."', '".$role."', '".$idLol."', 1)");
			
		$resultat=$query->execute();
	}

	
}
?>