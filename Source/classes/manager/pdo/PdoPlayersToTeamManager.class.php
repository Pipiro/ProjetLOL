<?php

require_once 'AbstractPdoManager.class.php';

class PdoPlayersToTeamManager extends AbstractPdoManager 
{

	public function getPlayersByTeamId($idTeam)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, idPlayer, idTeam FROM playerstoteam WHERE idTeam='$idTeam' ORDER BY id ASC LIMIT 5");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$playersToTeam = new playersToTeam($result->id, $result->idPlayer, $result->idTeam);
			$results[] = $playersToTeam;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	public function addPlayerToTeam($idPlayer, $idTeam)
	{

	}



	
}
?>