<?php

interface PlayersToTeamManager 
{
	function getPlayersByTeamId($idTeam);
	function addPlayerToTeam($idPlayer, $idTeam);
}

?>