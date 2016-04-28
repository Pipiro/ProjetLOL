<?php

require_once 'AbstractPdoManager.class.php';

class PdoPlayersToTeamManager extends AbstractPdoManager 
{

	public function getPlayersByTeamId($idTeam)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, idPlayer, idTeam FROM playerstoteam WHERE idTeam='$idTeam' ORDER BY id ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$playersToTeam = new playersToTeam($result->id, $result->idPlayer, $result->idTeam);
			$results[] = $playersToTeam;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	public function getActivesPlayersByTeamId($idTeam)
	{
		$results = array();
		$playersToTeamArray = array();
		$pm = new PdoPlayersManager();   
		
		$query = $this->pdo->prepare("SELECT id, idPlayer, idTeam FROM playerstoteam WHERE idTeam='$idTeam'");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$playersToTeam = new playersToTeam($result->id, $result->idPlayer, $result->idTeam);
			$playersToTeamArray[] = $playersToTeam;
		}
		
		$query->closeCursor();

		foreach($playersToTeamArray as $playersToTeamSingle)
	    {
	    	$player = $pm->getPlayerbyId($playersToTeamSingle->getIdPlayer());
	    	if ($player->getActif() == 1)
	    	{
	    		$results[] = $player;
	    	}
	    }

		return $results;
        
	}

	public function addPlayerToTeam($idPlayer, $idTeam)
	{

	}



	
}
?>