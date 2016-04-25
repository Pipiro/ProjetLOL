<?php

class Players
{
	//500 request(s) every 10 minute(s)
	//10 request(s) every 10 second(s)

	private $id;
	private $name;
	private $idLol;
	private $actif;
	
	public function __construct($id = null, $name = null, $idLol = null, $actif = null) 
	{
		$this->id = $id;
		$this->name = $name;
		$this->idLol = $idLol;
		$this->actif = $actif;
	}
	
	public function getId() 
	{ 
		return $this->id; 
	} 

	public function getName()
	{
		return $this->name;
	}

	public function getIdLol()
	{
		return $this->idLol;
	}

	public function getActif() 
	{ 
		return $this->actif; 
	} 
	
	public function setId($id) 
	{ 
		$this->id = $id; 
	}
	
	public function setName($name) 
	{ 
		$this->name = $name; 
	}

	public function setIdLol($idLol) 
	{ 
		$this->idLol = $idLol; 
	}

	public function setActif($actif) 
	{ 
		$this->actif = $actif; 
	}
	
}

?>