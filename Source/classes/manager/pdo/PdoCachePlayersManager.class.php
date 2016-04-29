<?php

require_once 'AbstractPdoManager.class.php';

class PdoCachePlayersManager extends AbstractPdoManager 
{

	public function getCachePlayerByIdPlayer($id)
	{
		$query = $this->pdo->prepare("SELECT id, idPlayer, isRanked, updateDate, idPlayerLol, nickname, leagueName, leaguePoint, leagueTier, leagueDivision, miniSerieProgress FROM cacheplayers WHERE idPlayer='$id'");
		$query->execute();
		
		$result = $query->fetch(PDO::FETCH_OBJ);
		if($result)
		{
			$cachePlayersToTeam = new cachePlayers($result->id, $result->idPlayer, $result->isRanked, $result->updateDate, $result->idPlayerLol, $result->nickname, $result->leagueName, $result->leaguePoint, $result->leagueTier, $result->leagueDivision, $result->miniSerieProgress);
			$query->closeCursor();
			return $cachePlayersToTeam;
		}
		else
		{
			return null;
		}
		
		

		
	}

	public function addCachePlayer($idPlayer, $isRanked, $updateDate, $idPlayerLol, $nickname, $leagueName, $leaguePoint, $leagueTier, $leagueDivision, $miniSerieProgress)
	{
		//on supprime l'ancien cache pour le joueur
		if ($this->getCachePlayerByIdPlayer($idPlayer) != null)
		{
			$query = $this->pdo->prepare("DELETE FROM cacheplayers WHERE idPlayer='$idPlayer'");
			
			$resultat=$query->execute();
		}

		$query = $this->pdo->prepare("INSERT INTO cacheplayers(idPlayer, isRanked, updateDate, idPlayerLol, nickname, leagueName, leaguePoint, leagueTier, leagueDivision, miniSerieProgress)
			VALUES ('".$idPlayer."', '".$isRanked."', '".$updateDate."', '".$idPlayerLol."', '".$nickname."','".$leagueName."','".$leaguePoint."','".$leagueTier."','".$leagueDivision."','".$miniSerieProgress."')");
		

		$resultat=$query->execute();
	}
	
	
}
?>