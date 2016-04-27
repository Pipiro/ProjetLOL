<?php

class PlayersToTeam
{
	private $id;
	private $idPlayer;
	private $idTeam;
	
	public function __construct($id = null, $idPlayer = null, $idTeam = null) 
	{
		$this->id = $id;
		$this->idPlayer = $idPlayer;
		$this->idTeam = $idTeam;
	}
	
	public function getId() 
	{ 
		return $this->id; 
	} 

	public function getIdPlayer()
	{
		return $this->idPlayer;
	}

	public function getIdTeam()
	{
		return $this->idTeam;
	}
	
	public function setId($id) 
	{ 
		$this->id = $id; 
	}
	
	public function setIdPlayer($idPlayer) 
	{ 
		$this->idPlayer = $idPlayer; 
	}

	public function setIdTeam($idTeam) 
	{ 
		$this->idTeam = $idTeam; 
	}
	
}

?>