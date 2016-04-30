<?php

interface CachePlayersManager 
{
	function getCachePlayerByIdPlayerAndTimeLimit($id, $timeLimit);
	function addCachePlayer($idPlayer, $isRanked, $updateDate, $idPlayerLol, $nickname, $leagueName, $leaguePoint, $leagueTier, $leagueDivision, $miniSerieProgress);
}

?>