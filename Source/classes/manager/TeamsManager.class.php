<?php

interface TeamsManager 
{
	function getTeams();
	function getTeamById($id);
	function addTeam($name);
}

?>