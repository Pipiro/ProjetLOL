<?php

interface PlayersToTeamManager 
{
	function getPlayersByTeamId($idTeam);
	function getActivesPlayersByTeamId($idTeam);
	function addPlayerToTeam($idPlayer, $idTeam);
}

?>