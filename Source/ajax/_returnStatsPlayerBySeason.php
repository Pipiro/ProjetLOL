<?php
    require '../required.php';

    $am = new PdoApiKeyManager();

    $statsJoueurRanked = $am->getStatsRankedByIdPlayer($_GET['idJoueur'],$_GET['season']);
    $statsJoueurSummary = $am->getStatsSummaryByIdPlayer($_GET['idJoueur'],$_GET['season']);
    $player = $am->getNamePlayerById($_GET['idJoueur']);
    $champs = $am->getChamps()->data;

    //var_dump($statsJoueurRanked);
    //var_dump($statsJoueurSummary);

    if (isset($statsJoueurSummary->playerStatSummaries))
    {
        //recuperation des victoires et des defaites selon le mode de jeu
        $totalWin = 0;
        $totalLosses = 0;
        $totalGame = 0;
        $statsRankedSolo = false;
        $statsRankedTeam = false;

        foreach ($statsJoueurSummary->playerStatSummaries as $statsMode) 
        {
          if (($_GET['mode'] == "RANKED_SOLO" || $_GET['mode'] == "TOUS") && $statsMode->playerStatSummaryType == "RankedSolo5x5")
          {
            $totalLosses = $totalLosses + $statsMode->losses;
            $totalWin = $totalWin + $statsMode->wins;
            if ($totalLosses != 0 || $totalWin != 0)
            {
                $statsRankedSolo = true; 
            }          
          }
          else if (($_GET['mode'] == "RANKED_TEAM_5V5" || $_GET['mode'] == "TOUS") && $statsMode->playerStatSummaryType == "RankedTeam5x5")
          {
            $totalLosses = $totalLosses + $statsMode->losses;
            $totalWin = $totalWin + $statsMode->wins;
            if ($totalLosses != 0 || $totalWin != 0)
            {
                $statsRankedTeam = true;
            }
          }
        }

        $totalGame = $totalWin + $totalLosses;
        $showStats = true;  ?>

        <h2 style="text-align: center;"><?php echo $totalGame; ?> parties</h1>
               
        <?php
        //on ne doit pas remplir la gauge s il ny a pas de donnees
        if (($_GET['mode'] == "RANKED_SOLO" && $statsRankedSolo == false) || ($_GET['mode'] == "RANKED_TEAM_5V5" && $statsRankedTeam == false) || ($_GET['mode'] == "TOUS" && $statsRankedSolo == false && $statsRankedTeam == false))
        {
            $showStats = false;
        }

        //affichage d un message d erreur s il manque des donnees
        if (($_GET['mode'] == "RANKED_SOLO" || $_GET['mode'] == "TOUS") && $statsRankedSolo == false)
        { ?>
            <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $_GET['season']; ?> en mode Ranked Solo.
            </div>
        <?php }
        if (($_GET['mode'] == "RANKED_TEAM_5V5" || $_GET['mode'] == "TOUS") && $statsRankedTeam == false)
        { ?>
            <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $_GET['season']; ?> en mode Ranked Team 5v5.
            </div>
        <?php }

        if ($showStats == true)
        { ?>

            <script type="text/javascript">

               var chart = $('#container_victoire').highcharts(),point;
               point = chart.series[0].points[0];
               point.update(<?php echo number_format(($totalWin / ($totalGame)) * 100,0); ?>);

           </script>

       <?php } ?>



        <br /><br />

        <?php if ($statsJoueurRanked != null)
        {

            //traitement des donnees des matches classes si la saison est differente de 2013
            if ($_GET['season'] != "3")
            {
                //modification du mode pour api
                if ($_GET['mode']=="TOUS")$modeModif="RANKED_SOLO_5x5,RANKED_TEAM_5x5";
                if ($_GET['mode']=="RANKED_SOLO")$modeModif="RANKED_SOLO_5x5";
                if ($_GET['mode']=="RANKED_TEAM_5V5")$modeModif="RANKED_TEAM_5x5";

                //requete api
                $matchList = $am->getMatchListByIdPlayerAndSeasonAndMode($_GET['idJoueur'],$_GET['season'],$modeModif);

                if (isset($matchList->matches))
                {                 
                    //on va analyser pour chaque match le poste, la lane et le champion prefere
                    $arrayLane = array();
                    $arrayRole = array();
                    $arrayChampion = array();
                    foreach($matchList->matches as $match):
                    //analyse de la lane
                    if (isset($match->lane))
                    {
                      if (array_key_exists($match->lane,$arrayLane))
                      {
                        $arrayLane["$match->lane"]++;
                      }
                      else
                      {
                        $arrayLane["$match->lane"]=1;
                      }
                    }
                    //analyse du role
                    if (isset($match->role))
                    {
                      if (array_key_exists($match->role,$arrayRole))
                      {
                        $arrayRole["$match->role"]++;
                      }
                      else
                      {
                        $arrayRole["$match->role"]=1;
                      }
                    }
                    //analyse du champion
                    if (isset($match->champion))
                    {
                      if (array_key_exists($match->champion,$arrayChampion))
                      {
                        $arrayChampion["$match->champion"]++;
                      }
                      else
                      {
                        $arrayChampion["$match->champion"]=1;
                      }
                    }
                  endforeach;
                    
                  //on trie les tableaux en ordre decroissant
                  arsort($arrayLane);
                  arsort($arrayRole);
                  arsort($arrayChampion); ?>

                   <div id="containerJoueFreq">
                  <?php foreach($champs as $champ):
                    if ($champ->id == key($arrayChampion)) {

                        //traitement des noms de champion particulier
                        $champName =  preg_replace('/\s/', '', $champ->name);
                        if ($champName=="LeBlanc")$champName="Leblanc";
                        else if ($champName=="Dr.Mundo")$champName="DrMundo";
                        else if ($champName=="Wukong")$champName="MonkeyKing";
                        else if ($champName=="Kha'Zix")$champName="Khazix";
                        else if ($champName=="Cho'Gath")$champName="Chogath";
                        else if ($champName=="Fiddlesticks")$champName="FiddleSticks";
                        else if ($champName=="Rek'Sai")$champName="RekSai";
                        else if ($champName=="Kog'Maw")$champName="KogMaw";
                        else if ($champName=="Vel'Koz")$champName="Velkoz";

                        echo "<div id='imagePref'><img style='width: 80px; height: 80px;' src='http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/" . $champName . ".png'". "title='". $champ->name . "'></div>";
                        break;
                    }
                  endforeach;
                  echo "<h2>" . key($arrayRole) . " " . key($arrayLane) . "</h2>"; ?>
                  </div>
                <?php }
             } ?>

          <br />
          <h2>Champions Saison <?php echo $_GET['season']; ?></h2>

          <?php foreach($statsJoueurRanked->champions as $champPlayer): ?>
            <?php foreach($champs as $champ): ?>

              <?php if ($champ->id == $champPlayer->id && $champPlayer->id != 0) { ?>
              
                <!-- traitement des noms de champion particulier -->
                <?php $champName =  preg_replace('/\s/', '', $champ->name); ?>
                <?php if ($champName=="LeBlanc")$champName="Leblanc"; ?>
                <?php if ($champName=="Dr.Mundo")$champName="DrMundo"; ?>
                <?php if ($champName=="Wukong")$champName="MonkeyKing"; ?>
                <?php if ($champName=="Kha'Zix")$champName="Khazix"; ?>
                <?php if ($champName=="Cho'Gath")$champName="Chogath"; ?>
                <?php if ($champName=="Fiddlesticks")$champName="FiddleSticks"; ?>
                <?php if ($champName=="Rek'Sai")$champName="RekSai"; ?>
                <?php if ($champName=="Kog'Maw")$champName="KogMaw"; ?>
                <?php if ($champName=="Vel'Koz")$champName="Velkoz"; ?>

                <div id="caseChamp">
                  <?php echo "<div id='imageChamp'><img style='opacity:0.5;' src='http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/" . $champName . ".png'". "title='". $champ->name . "'></div>"; ?>
                  <center><div id="ratioChamp"><?php echo number_format((($champPlayer->stats->totalSessionsWon / ($champPlayer->stats->totalSessionsWon + $champPlayer->stats->totalSessionsLost) * 100)),0) . "%"; ?></div></center>
                  <div id="winChamp"><?php echo $champPlayer->stats->totalSessionsWon ?></div>
                  <div id="lossChamp"><?php echo $champPlayer->stats->totalSessionsLost ?></div>
                </div>

              <?php } ?>

            <?php endforeach; ?>
          <?php endforeach; ?>

         <?php }else if ($showStats !=false) { ?>
            <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $_GET['season']; ?>.
            </div>
        <?php } ?>
<?php }else{ ?>
    <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
        <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $_GET['season']; ?>.
    </div>
<?php } ?>
