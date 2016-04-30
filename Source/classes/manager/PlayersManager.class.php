<?php

interface PlayersManager 
{
	function getActivesPlayers();
	function getIdLolByNamePlayer($name);
	function getPlayerbyId($id);
	function addPlayer($name, $role, $idLol);
}

?>