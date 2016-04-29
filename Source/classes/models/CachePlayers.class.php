<?php

class CachePlayers
{
	//500 request(s) every 10 minute(s)
	//10 request(s) every 10 second(s)

	private $id;
	private $idPlayer;
	private $isRanked;
	private $updateDate;
	private $idPlayerLol;
	private $nickname;
	private $leagueName;
	private $leaguePoint;
	private $leagueTier;
	private $leagueDivision;
	private $miniSerieProgress;

	public function __construct($id = null, $idPlayer = null, $isRanked = null, $updateDate = null, $idPlayerLol = null, $nickname = null, $leagueName = null, $leaguePoint = null, $leagueTier = null, $leagueDivision = null, $miniSerieProgress = null) 
	{
		$this->id = $id;
		$this->idPlayer = $idPlayer;
		$this->isRanked = $isRanked;
		$this->updateDate = $updateDate;
		$this->idPlayerLol = $idPlayerLol;
		$this->nickname = $nickname;
		$this->leagueName = $leagueName;
		$this->leaguePoint = $leaguePoint;
		$this->leagueTier = $leagueTier;
		$this->leagueDivision = $leagueDivision;
		$this->miniSerieProgress = $miniSerieProgress;
	}
	
	public function getId() 
	{ 
		return $this->id; 
	}

	public function getIdPlayer()
	{
		return $this->idPlayer;
	}

	public function getIsRanked() 
	{ 
		return $this->isRanked; 
	}

	public function getUpdateDate() 
	{ 
		return $this->updateDate; 
	}

	public function getIdPlayerLol()
	{
		return $this->idPlayerLol;
	}

	public function getNickname()
	{
		return $this->nickname;
	}

	public function getLeagueName() 
	{ 
		return $this->leagueName; 
	} 

	public function getLeaguePoint() 
	{ 
		return $this->leaguePoint; 
	} 

	public function getLeagueTier() 
	{ 
		return $this->leagueTier; 
	} 

	public function getLeagueDivision() 
	{ 
		return $this->leagueDivision; 
	} 

	public function getMiniSerieProgress() 
	{ 
		return $this->miniSerieProgress; 
	} 

	public function setId($id) 
	{ 
		$this->id = $id; 
	}

	public function setIdPlayer($idPlayer) 
	{ 
		$this->idPlayer = $idPlayer; 
	}

	public function setIsRanked($isRanked) 
	{ 
		$this->isRanked = $isRanked; 
	}

	public function setUpdateDate($updateDate) 
	{ 
		$this->updateDate = $updateDate; 
	}

	public function setIdPlayerLol($idPlayerLol) 
	{ 
		$this->idPlayerLol = $idPlayerLol; 
	}
	
	public function setNickname($nickname) 
	{ 
		$this->nickname = $nickname; 
	}

	public function setLeagueName($leagueName) 
	{ 
		$this->leagueName = $leagueName; 
	}

	public function setLeaguePoint($leaguePoint) 
	{ 
		$this->leaguePoint = $leaguePoint; 
	}

	public function setLeagueTier($leagueTier) 
	{ 
		$this->leagueTier = $leagueTier; 
	}

	public function setLeagueDivision($leagueDivision) 
	{ 
		$this->leagueDivision = $leagueDivision; 
	}

	public function setMiniSerieProgress($miniSerieProgress) 
	{ 
		$this->miniSerieProgress = $miniSerieProgress; 
	}
	
}

?>