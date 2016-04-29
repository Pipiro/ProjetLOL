<?php

require_once 'AbstractPdoManager.class.php';

class PdoApiKeyManager extends AbstractPdoManager 
{
	const APIURL = "https://euw.api.pvp.net/api/lol";
	const APIURLSTATIC = "https://global.api.pvp.net/api/lol/static-data";

	
	public function getKeys()
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, name, value, timestamp10s, number10s, timestamp10m, number10m, actif FROM apikey ORDER BY id ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$keyAPI = new apikey($result->id, $result->name, $result->value, $result->timestamp10s, $result->number10s, $result->timestamp10m, $result->number10m, $result->actif);
			$results[] = $keyAPI;
		}
		
		$query->closeCursor();

		return $results;
	}

	public function getKeyByUse()
	{
		//on récupére la liste des clées
		$keys = $this->getKeys();
		//pour chaque clé on va analyser si elle est disponible
		foreach ($keys as $keyAPI){
			if ($this->verifQuotaAndUpdate($keyAPI) && $keyAPI->getActif()==1)
			{
				return $keyAPI;
			}
		}
		return null;
	}

	public function updateKey($id, $timestamp10s, $number10s, $timestamp10m, $number10m)
	{
		$statement = "UPDATE apikey SET ";
		if ($timestamp10s!=null)
		{
			$statement = $statement . "timestamp10s='$timestamp10s', ";
		} 
		if ($number10s!=null) 
		{
			$statement = $statement . "number10s='$number10s', ";
		}
		if ($timestamp10m!=null)
		{
			$statement = $statement . "timestamp10m='$timestamp10m', ";
		} 
		if ($number10m!=null) 
		{
			$statement = $statement . "number10m='$number10m '";
		}
		$statement = $statement . "WHERE id='$id'";
		if (stripos($statement, ", WHERE"))
		{
			str_replace(", WHERE", " WHERE", $statement);
		}
		$query = $this->pdo->prepare($statement);

		$resultat=$query->execute();
	}

	public function getNumberKeysAvailable()
	{
		//on récupére la liste des clées
		$keys = $this->getKeys();
		$keysAvailable = array();
		//pour chaque clé on va analyser si elle est disponible
		foreach ($keys as $keyAPI){
			if ($this->verifQuota($keyAPI) && $keyAPI->getActif()==1)
			{
				array_push($keysAvailable, $keyAPI);
			}
		}
		//on va retourner le nombre de clées
		return count($keysAvailable);
	}

		//limite 500 requetes toutes les 10 minutes et 10 requetes toutes les 10 secondes
	public function verifQuotaAndUpdate($keyAPI)
	{
		$currenttimestamp = time();
		//cela fait plus de 10 minutes que la clee n a pas ete utilise
		if (($keyAPI->getTimestamp10m()+600) < $currenttimestamp)
		{
			$this->updateKey($keyAPI->getId(), $currenttimestamp, 1, $currenttimestamp, 1);
			return true;
		}
		//cela fait moins de 10 minutes mais plus de 10 secondes la clee n a pas ete utilise
		else if (($keyAPI->getTimestamp10s()+10) < $currenttimestamp)
		{
			//est ce que la limite sur les minutes a ete atteinte
			if (($keyAPI->getNumber10m() < 500))
			{
				$compteur = $keyAPI->getNumber10m();
				$compteur++;
				$this->updateKey($keyAPI->getId(), $currenttimestamp, 1, $currenttimestamp, $compteur);
				return true;
			}
			//la limite en minute a ete atteinte
			else
			{
				return false;
			}
		}
		//cela fait moins de 10 minutes et moins de 10 secondes la clee n a pas ete utilise
		else if (($keyAPI->getNumber10s() < 10) && ($keyAPI->getNumber10m() < 500))
		{
			$compteurS = $keyAPI->getNumber10s();
			$compteurS++;
			$compteurM = $keyAPI->getNumber10m();
			$compteurM++;
			$this->updateKey($keyAPI->getId(), $currenttimestamp, $compteurS, $currenttimestamp, $compteurM);
			return true;
		}
		//la limite en minute ou en seconde a ete atteinte
		else
		{
			return false;
		}
	}

	public function verifQuota($keyAPI)
	{
		$currenttimestamp = time();
		//cela fait plus de 10 minutes que la clee n a pas ete utilise
		if (($keyAPI->getTimestamp10m()+600) < $currenttimestamp)
		{
			return true;
		}
		//cela fait moins de 10 minutes mais plus de 10 secondes la clee n a pas ete utilise
		else if (($keyAPI->getTimestamp10s()+10) < $currenttimestamp)
		{
			//est ce que la limite sur les minutes a ete atteinte
			if (($keyAPI->getNumber10m() < 500))
			{
				return true;
			}
			//la limite en minute a ete atteinte
			else
			{
				return false;
			}
		}
		//cela fait moins de 10 minutes et moins de 10 secondes la clee n a pas ete utilise
		else if (($keyAPI->getNumber10s() < 10) && ($keyAPI->getNumber10m() < 500))
		{
			return true;
		}
		//la limite en minute ou en seconde a ete atteinte
		else
		{
			return false;
		}
	}

	public function getResultsApi($query)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		//fix du problème ssl
		curl_setopt($curl, CURLOPT_CAINFO, 'C:\wamp\ssl\cacert.pem');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10000); // l'API a 5s pour répondre sinon on renvoie une erreur
		$result = curl_exec($curl);
		if($result == false)
		{
			return curl_error($curl);
		}
		$result = json_decode($result);
		return $result;
	}


	public function getInfoLeagueByIdPlayer($id)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchLeague = self::APIURL . "/euw/v2.5/league/by-summoner/" . $id . "?api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchLeague);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
        
	}

	public function getStatsRankedByIdPlayer($id, $season)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchStats = self::APIURL . "/euw/v1.3/stats/by-summoner/" . $id . "/ranked?season=SEASON" . $season . "&api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchStats);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
        
	}

	public function getChamps()
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchChamps = self::APIURLSTATIC . "/euw/v1.2/champion?api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchChamps);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
        
	}

	public function getStatsSummaryByIdPlayer($id, $season)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchStats = self::APIURL . "/euw/v1.3/stats/by-summoner/" . $id . "/summary?season=SEASON" . $season . "&api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchStats);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
        
	}

	public function getMatchListByIdPlayerAndSeasonAndMode($id, $season, $mode)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchMatchs = self::APIURL . "/euw/v2.2/matchlist/by-summoner/" . $id . "?rankedQueues=" . $mode . "&seasons=SEASON" . $season . "&api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchMatchs);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
        
	}

	function getPlayerByName($name)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			try
			{
				$searchPlayer = self::APIURL . "/euw/v1.4/summoner/by-name/" . $name . "?api_key=" . $keyAPI->getValue();
		        return $this->getResultsApi($searchPlayer);
		    }catch(Exception $e)
		    {

	    	}
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
	}

	function getPlayerById($id)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$searchPlayer = self::APIURL . "/euw/v1.4/summoner/" . $id . "?api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($searchPlayer);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
	}

	function getPlayerInGame($id)
	{
		$keyAPI = $this->getKeyByUse();
		if ($keyAPI != null)
		{
			$currentGamePLayer = "https://euw.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/" . $id . "?api_key=" . $keyAPI->getValue();
	        return $this->getResultsApi($currentGamePLayer);
	    }
	    else
	    {
	    	return "Acune clé de disponible pour accéder à l'API, veuillez réessayer ultérieurement";
	    }
	}


	/*public function getAllSaves ($date)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE date = '".$date."' ORDER BY nom ASC");
		$query->bindValue(':date', $date);
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$saves = new save($result->id, $result->nom, $result->type, $result->destination, $result->date, $result->pourcentage, $result->taille, $result->statut, $result->fkClient);
			$results[] = $saves;
		}
		
		$query->closeCursor();
		
		return $results;
	}

	public function getSaveWithFiltersWithDate($type, $client, $statut, $date)
	{
		$results = array();

		if ($type == 0 && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE statut='".$statut."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':statut', $statut);
			$query->bindValue(':date', $date);
		}
		else if (($type == 0 || $type == 0) && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':date', $date);
		}
		else if ($type == 0 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':date', $date);
		}
		else if ($type == 2 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':date', $date);
		}
		else if ($type != 0 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
			$query->bindValue(':date', $date);
		}
		else if ($type == 2 && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND fkClient ='".$client."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':date', $date);
		}
		else if ($type != 0 && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND fkClient ='".$client."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':date', $date);
		}
		else if ($type == 2 && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND statut ='".$statut."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':statut', $statut);
			$query->bindValue(':date', $date);
		}
		else if ($type != null && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND statut ='".$statut."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
			$query->bindValue(':statut', $statut);
			$query->bindValue(':date', $date);
		}
		else if ( $type == 0 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' AND statut ='".$statut."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
			$query->bindValue(':date', $date);
		}
		else if ($type == 2 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND fkClient='".$client."' AND statut ='".$statut."'  AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
			$query->bindValue(':date', $date);
		}
		else if ($type != 0 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' AND statut ='".$statut."' AND type ='".$type."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
			$query->bindValue(':type', $type);
			$query->bindValue(':date', $date);
		}
		
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$saves = new save($result->id, $result->nom, $result->type, $result->destination, $result->date, $result->pourcentage, $result->taille, $result->statut, $result->fkClient);
			$results[] = $saves;
		}
		
		$query->closeCursor();
		
		return $results;
	}

	public function getSaveWithFiltersWithoutDate($type, $client, $statut)
	{
		$results = array();

		if ($type == 0 && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE statut='".$statut."' ORDER BY nom ASC");
			$query->bindValue(':statut', $statut);
		}
		else if (($type == 0 || $type == 0) && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
		}
		else if ($type == 0 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save ORDER BY nom ASC");
		}
		else if ($type == 2 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 ORDER BY nom ASC");
		}
		else if ($type != 0 && $client == 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND date = '".$date."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
		}
		else if ($type == 2 && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND fkClient ='".$client."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
		}
		else if ($type != 0 && $client != 0 && $statut == 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND fkClient ='".$client."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
			$query->bindValue(':fkClient', $client);
		}
		else if ($type == 2 && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND statut ='".$statut."' ORDER BY nom ASC");
			$query->bindValue(':statut', $statut);
		}
		else if ($type != null && $client == 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type='".$type."' AND statut ='".$statut."' ORDER BY nom ASC");
			$query->bindValue(':type', $type);
			$query->bindValue(':statut', $statut);
		}
		else if ( $type == 0 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' AND statut ='".$statut."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
		}
		else if ($type == 2 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE type=2 OR type =6 AND fkClient='".$client."' AND statut ='".$statut."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
		}
		else if ($type != 0 && $client != 0 && $statut != 0)
		{
			$query = $this->pdo->prepare("SELECT id, nom, type, destination, date, pourcentage, taille, statut, fkClient FROM save WHERE fkClient='".$client."' AND statut ='".$statut."' AND type ='".$type."' ORDER BY nom ASC");
			$query->bindValue(':fkClient', $client);
			$query->bindValue(':statut', $statut);
			$query->bindValue(':type', $type);
		}
		
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$saves = new save($result->id, $result->nom, $result->type, $result->destination, $result->date, $result->pourcentage, $result->taille, $result->statut, $result->fkClient);
			$results[] = $saves;
		}
		
		$query->closeCursor();
		
		return $results;
	}

	public function addSave($name, $type, $pourcentage, $client, $statut)
	{
		$query = $this->pdo->prepare("
		INSERT INTO save (nom, type, destination, date, pourcentage, taille, statut, fkClient)
		VALUES ('".$name."', '".$type."', '".NULL."', '".date("Y-m-d")."', '".$pourcentage."', '".NULL."', '".$statut."', '".$client."')");
			
		$resultat=$query->execute();
	}

	/*public function create_event($date, $description, $nombre_places, $places_restantes)
	{
		$query = $this->pdo->prepare("
		INSERT INTO event (date, description, nombre_places, places_restantes)
		VALUES ('".$date."', '".$description."', '".$nombre_places."', '".$places_restantes."')");
			
		$resultat=$query->execute();
	}
	
	public function remove_event($id)
	{
		$query = $this->pdo->prepare('DELETE FROM event WHERE id = :id');
		$query->bindValue(':id', $id);
		
		return $query->execute();
	}
	
	public function verifDate($timestamp)
	{
		if ($timestamp < (time()+7200)) //time() a 2h de retard...
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function show_event()
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, description, nombre_places, date, places_restantes FROM event ORDER BY date ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$events = new event($result->id, $result->description, $result->nombre_places, $result->date, $result->places_restantes);
			$results[] = $events;
		}
		
		$query->closeCursor();
		
		return $results;
	}
	
	public function event_exist($date)
	{
		$query = $this->pdo->prepare("SELECT id, description, nombre_places, date, places_restantes FROM event WHERE date='".$date."'");
			
		$query->bindValue(':date', $date);
		$query->execute();
			
		$result = $query->fetch(PDO::FETCH_OBJ) ;

		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function return_eventById($id)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, description, nombre_places, date, places_restantes FROM event WHERE id='".$id."'");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$events = new event($result->id, $result->description, $result->nombre_places, $result->date, $result->places_restantes);
			$results[] = $events;
		}
		
		$query->closeCursor();
		
		return $results;
	}
	
	public function update_event($id, $places)
	{
		$um = new PdoEventManager();
		$list = $um->return_eventById($id);
		foreach($list as $events): 
		$new_places = (($events->getPlaces_restantes())+$places);
		endforeach;
		$query = $this->pdo->prepare("UPDATE event SET places_restantes='$new_places' WHERE id='$id'");

		$resultat=$query->execute();
	}
	
	public function return_eventByGuest($id)
	{
		$results = array();
		
		$query = $this->pdo->prepare("SELECT id, description, nombre_places, date, places_restantes FROM event WHERE id='".$id."' ORDER BY date ASC");
		$query->execute();
		
		while($result = $query->fetch(PDO::FETCH_OBJ)) 
		{
			$events = new Event($result->id, $result->description, $result->nombre_places, $result->date, $result->places_restantes);
			$results[] = $events;
		}
		
		$query->closeCursor();
		
		return $results;
	}*/	
	
}
?>