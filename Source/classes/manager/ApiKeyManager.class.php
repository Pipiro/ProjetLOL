<?php

interface ApiKeyManager 
{
	function getInfoLeagueByIdPlayer($id);
	function getStatsByIdPlayer($id, $season);
	function getChamps();
	function getIdPlayerByName($name);
	function getNamePlayerById($id);
	function getKeyByUse();
	function updateKey($id, $timestamp10s, $number10s, $timestamp10m, $number10m);
	/*function getAllSaves($date);
	function getSaveWithFiltersWithDate($type, $client, $statut, $date);
	function getSaveWithFiltersWithoutDate($type, $client, $statut);
	function addSave($name, $type, $pourcentage, $client, $statut);
	function create_event($date, $description, $nombre_places, $places_restantes);
	function remove_event($id);
	function verifDate($timestamp);
	function show_event();
	function event_exist($date);
	function return_eventById($id);
	function update_event($id, $places);
	function return_eventByGuest($id);*/
}

?>