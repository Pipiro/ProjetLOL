<?php

require_once 'AbstractPdoManager.class.php';

class PdoPlayersManager extends AbstractPdoManager 
{

	public function getActivesPlayers()
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, name, idLol, actif FROM players WHERE actif=1 ORDER BY id ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$players = new players($result->id, $result->name, $result->idLol, $result->actif);
			$results[] = $players;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	
}
?>