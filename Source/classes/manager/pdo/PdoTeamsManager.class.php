<?php

require_once 'AbstractPdoManager.class.php';

class PdoTeamsManager extends AbstractPdoManager 
{

	public function getTeams()
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, name FROM teams ORDER BY name ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$teams = new teams($result->id, $result->name);
			$results[] = $teams;
		}
		
		$query->closeCursor();

		return $results;
        
	}

	function addTeam($name)
	{

	}

	
}
?>