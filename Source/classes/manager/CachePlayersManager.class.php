<?php

interface CachePlayersManager 
{
	function getCachePlayerByIdPlayer($id);
	function addCachePlayer($idPlayer, $isRanked, $updateDate, $idPlayerLol, $nickname, $leagueName, $leaguePoint, $leagueTier, $leagueDivision, $miniSerieProgress);
}

?>