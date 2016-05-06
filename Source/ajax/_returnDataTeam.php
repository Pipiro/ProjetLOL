<?php
    require '../required.php';

    $am = new PdoApiKeyManager();
    // Saison actuelle pour la recherche de données
    $actualSeason = "2016";

    // Récupération des joueurs actifs de la team
    $pttm = new PdoPlayersToTeamManager();
    $players = $pttm->getActivesPlayersByTeamId($_GET['idTeam']);

    // Construction du tableau des clés de l'éqipe
    foreach ($players as $player) 
    {
      if ($player->getIdLol() != reset($players)->getIdLol())
      {
        $arrayKeysTeam[$player->getName()] = $player->getIdLol();
      }
    }

    $recentGames = $am->getRecentGamesByIdPlayer(reset($players)->getIdLol());

    //var_dump($recentGames);
    //die;

    echo "<br />Recherche de partie avec le joueur ".reset($players)->getName()."<br /><br />";

    foreach ($recentGames->games as $recentGame) {
      if ($recentGame->gameMode == "CLASSIC" && $recentGame->subType == "NORMAL")
      {
        $arrayKeysGame = null;
        echo "MATCH ".$recentGame->gameId." ";
        //var_dump(substr($recentGame->createDate, 0, -3)); die;
        echo 'Le '.date('d/m/Y', substr($recentGame->createDate, 0, -3)).' &agrave; '.date('H:i:s', substr($recentGame->createDate, 0, -3))."<br />";
        //var_dump($recentGame->fellowPlayers); die;
        foreach ($recentGame->fellowPlayers as $fellowPlayer) 
        {
          if ($fellowPlayer->teamId == $recentGame->teamId)
          {
            $arrayKeysGame[] = $fellowPlayer->summonerId;
          }
        }
        // Récuparation des joueurs de la team manquant
        $missingPlayers = array_diff($arrayKeysTeam, $arrayKeysGame);
        if ($missingPlayers == null)
        {
          echo "Tout le monde présent";
        }
        else
        {
          echo "Il manque : ";
          foreach ($missingPlayers as $name => $idLol) 
          {
            echo $name." ";
          }
        }
        echo "<br /><br />";
        //var_dump($arrayKeysTeam);
        //var_dump($arrayKeysGame);
        //var_dump(array_diff($arrayKeysTeam, $arrayKeysGame));
      }
    }

?>