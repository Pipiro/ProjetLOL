<?php

require_once 'AbstractPdoManager.class.php';

class PdoPlayersToTeamManager extends AbstractPdoManager 
{

	public function getPlayersByTeamId($idTeam)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, idPlayer, idTeam FROM playersToTeam WHERE idTeam='$idTeam' ORDER BY id ASC LIMIT 5");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$playerstoTeam = new playerstoTeam($result->id, $result->idPlayer, $result->idTeam);
			$results[] = $playerstoTeam;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	public function addPlayerToTeam($idPlayer, $idTeam)
	{

	}



	
}
?>