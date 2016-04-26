<?php

interface PlayersManager 
{
	function getActivesPlayers();
	function getIdLolByNamePlayer($name);
	function addPlayer($name,$idLol);
}

?>