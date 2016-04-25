<?php
    require 'required.php';

    //onglet actif
    $activeTab = "index";

    //on récupére les stats ranked et summary du joueur
    $am = new PdoApiKeyManager();
    if (isset($_POST['season']))
    {
      $statsJoueurRanked = $am->getStatsRankedByIdPlayer($_GET['id'],$_POST['season']);
      $statsJoueurSummary = $am->getStatsSummaryByIdPlayer($_GET['id'],$_POST['season']);
      $numberSeason = $_POST['season'];    
    }
    else if (isset($_GET['season']))
    {
      $statsJoueurRanked = $am->getStatsRankedByIdPlayer($_GET['id'],$_GET['season']);
      $statsJoueurSummary = $am->getStatsSummaryByIdPlayer($_GET['id'],$_GET['season']);
      $numberSeason = $_GET['season'];
    }
    else
    {
      $statsJoueurRanked = $am->getStatsRankedByIdPlayer($_GET['id'],2016);
      $statsJoueurSummary = $am->getStatsSummaryByIdPlayer($_GET['id'],2016);
      $numberSeason = 2016;
    }

    $player = $am->getNamePlayerById($_GET['id']);
    $champs = $am->getChamps()->data; 

    //on recupere le mode selectionne
    if (isset($_POST['mode']))
    {
      $modeName = $_POST['mode'];
    }
    else
    {
       $modeName = "TOUS";
    }


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Projet LOL</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" type="text/css" />

    <!-- inclusion jquery -->
    <script type="text/javascript" src="jquery/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="jquery/jquery-ui-1.11.4.js"></script>

    <!-- incluion highcharts -->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/solid-gauge.js"></script>

   <script type="text/javascript">

      function getStatsPlayer(season, mode)
      {
       //on va mettre a jour les bouton actif
       $('#2013').removeClass('active');
       $('#2014').removeClass('active');
       $('#2015').removeClass('active');
       $('#2016').removeClass('active');
       $('#TOUS').removeClass('active');
       $('#RANKED_SOLO').removeClass('active');
       $('#RANKED_TEAM_5V5').removeClass('active');

       $('#'+season).toggleClass('active');
       $('#'+mode).toggleClass('active');

       //il faut gerer la particularite de la saison 2013
       if (season == 2013) { season = 3};
       
       //affichage du chargement
       $('#contenu_stats').html("<br /><br /><div style='text-align: center;'><img src='/ProjetLOL/Source/images/loader.gif' alt='chargement...'/></div>")

       //on remet le compteur de victoire a 0
       var chart = $('#container_victoire').highcharts(),point;
       point = chart.series[0].points[0];
       point.update(0);
       
       //requête ajax, appel du fichier _returnNumberKeysAvailables.php
       $.ajax(
       {
        type: "GET",
        url: "ajax/_returnStatsPlayerBySeason.php?idJoueur="+<?php echo $_GET['id']; ?>+"&season="+season+"&mode="+mode,
        dataType : "html",
        //affichage de l'erreur en cas de problème
        error:function(msg, string)
        {
          alert( "Error !: " + string );
        },
        success:function(data)
        {
          //on met à jour la div contenu_stats avec les données reçus
          //on vide la div et on le cache
          $("#contenu_stats").empty().hide();
          //on affecte les resultats au div
          $("#contenu_stats").append(data);
          //on affiche les resultats avec la transition
          $('#contenu_stats').fadeIn(800);
        }
       });
      }

      function updateMode(season)
      {
       $("#input_TOUS").attr("onChange", "updateSeason('TOUS');getStatsPlayer("+season+",'TOUS')");
       $("#input_RANKED_SOLO").attr("onChange", "updateSeason('RANKED_SOLO');getStatsPlayer("+season+",'RANKED_SOLO')");
       $("#input_RANKED_TEAM_5V5").attr("onChange", "updateSeason('RANKED_TEAM_5V5');getStatsPlayer("+season+",'RANKED_TEAM_5V5')");
      }

      function updateSeason(mode)
      {
       $("#input_2013").attr("onChange", "updateMode(2013);getStatsPlayer(2013,'"+mode+"')");
       $("#input_2014").attr("onChange", "updateMode(2014);getStatsPlayer(2014,'"+mode+"')");
       $("#input_2015").attr("onChange", "updateMode(2015);getStatsPlayer(2015,'"+mode+"')");
       $("#input_2016").attr("onChange", "updateMode(2016);getStatsPlayer(2016,'"+mode+"')");
      }

    </script>

  </head>

  <body>

    <div class="container">

      <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;overflow : hidden;">
        <div id="affichage" style="margin-top: -20px;">
          <div id="filtre" style="float:right; width: 400px;">
          <?php echo "<form action='statsPlayer.php' method='post'>"; ?>
            <div class="btn-group" data-toggle="buttons" style="float:right;">
              <?php if ($numberSeason == 3) { ?>
                <label id="2013" class="btn btn-info active">
                  <input name="season" id="input_2013" value="3" type="radio" onChange="updateMode(2013);getStatsPlayer(2013, '<?php echo $modeName; ?>')">2013
                </label>
              <?php } else { ?>
                <label id="2013" class="btn btn-info">
                  <input name="season" id="input_2013" value="3" type="radio" onChange="updateMode(2013);getStatsPlayer(2013, '<?php echo $modeName; ?>')">2013
                </label>
              <?php } ?>
              <?php if ($numberSeason == 2014) { ?>
                <label id="2014" class="btn btn-info active">
                  <input name="season" id="input_2014" value="2014" type="radio" onChange="updateMode(2014);getStatsPlayer(2014, '<?php echo $modeName; ?>')">2014
                </label>
              <?php } else { ?>
                <label id="2014" class="btn btn-info">
                  <input name="season" id="input_2014" value="2014" type="radio" onChange="updateMode(2014);getStatsPlayer(2014, '<?php echo $modeName; ?>')">2014
                </label>
              <?php } ?>
              <?php if ($numberSeason == 2015) { ?>
                <label id="2015" class="btn btn-info active">
                  <input name="season" id="input_2015" value="2015" type="radio" onChange="updateMode(2015);getStatsPlayer(2015, '<?php echo $modeName; ?>')">2015
                </label>
              <?php } else { ?>
                <label id="2015" class="btn btn-info">
                  <input name="season" id="input_2015" value="2015" type="radio" onChange="updateMode(2015);getStatsPlayer(2015, '<?php echo $modeName; ?>')">2015
                </label>
              <?php } ?>
              <?php if ($numberSeason == 2016) { ?>
                <label id="2016" class="btn btn-info active">
                  <input name="season" id="input_2016" value="2016" type="radio" onChange="updateMode(2016);getStatsPlayer(2016, '<?php echo $modeName; ?>')">2016
                </label>
              <?php } else { ?>
                <label id="2016" class="btn btn-info">
                  <input name="season" id="input_2016" value="2016" type="radio" onChange="updateMode(2016);getStatsPlayer(2016, '<?php echo $modeName; ?>')">2016
                </label>
              <?php } ?>
            </div>

            <div class="btn-group" data-toggle="buttons" style="float:right; margin-top: 5px;">
              <?php if ($modeName == "TOUS") { ?>
                <label id="TOUS" class="btn btn-info active">
                  <input name="mode" id="input_TOUS" value="TOUS" type="radio" onChange="updateSeason('TOUS');getStatsPlayer(<?php echo $numberSeason; ?>, 'TOUS')">Tous
                </label>
               <?php } else { ?>
                <label id="TOUS" class="btn btn-info">
                  <input name="mode" id="input_TOUS" value="TOUS" type="radio" onChange="updateSeason('TOUS');getStatsPlayer(<?php echo $numberSeason; ?>, 'TOUS')">Tous
                </label>
                <?php } ?>
                <?php if ($modeName == "RANKED_SOLO") { ?>
                <label id="RANKED_SOLO" class="btn btn-info active">
                  <input name="mode" id="input_RANKED_SOLO" value="RANKED_SOLO" type="radio" onChange="updateSeason('RANKED_SOLO');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_SOLO')">Ranked Solo
                </label>
                <?php } else { ?>
                  <label id="RANKED_SOLO" class="btn btn-info">
                    <input name="mode" id="input_RANKED_SOLO" value="RANKED_SOLO" type="radio" onChange="updateSeason('RANKED_SOLO');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_SOLO')">Ranked Solo
                  </label>
                <?php } ?>
                <?php if ($modeName == "RANKED_TEAM_5V5") { ?>
                  <label id="RANKED_TEAM_5V5" class="btn btn-info active">
                    <input name="mode" id="input_RANKED_TEAM_5V5" value="RANKED_TEAM_5V5" type="radio" onChange="updateSeason('RANKED_TEAM_5V5');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_TEAM_5V5')">Ranked Team 5v5
                  </label>
                <?php } else { ?>
                  <label id="RANKED_TEAM_5V5" class="btn btn-info">
                    <input name="mode" id="input_RANKED_TEAM_5V5" value="RANKED_TEAM_5V5" type="radio" onChange="updateSeason('RANKED_TEAM_5V5');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_TEAM_5V5')">Ranked Team 5v5
                  </label>
                <?php } ?>

            </div>
        </form>
      </div>

        <div id="titleStat" style="float:left; width: 600px;">
          <div id="namePlayer" style="float:left;">
            <?php echo "<img style='opacity:0.5;' src='http://lkimg.zamimg.com/images/v2/summoner/icons/size64x64/" . $player->$_GET['id']->profileIconId . ".png'". "title='Icone'></div>"; ?>
            <?php echo "<h2>".$player->$_GET['id']->name."</h1>"; ?> 
          </div>
          <div id="container_victoire" style="width: 300px; height: 200px; float: right;"></div>     
        </div>

        <div id="contenu_stats" style='clear:both;'>

          <?php 
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
                if (($modeName == "RANKED_SOLO" || $modeName == "TOUS") && $statsMode->playerStatSummaryType == "RankedSolo5x5")
                {
                  $totalLosses = $totalLosses + $statsMode->losses;
                  $totalWin = $totalWin + $statsMode->wins; 
                  if ($totalLosses != 0 || $totalWin != 0)
                  {
                    $statsRankedSolo = true;
                  }         
                }
                else if (($modeName == "RANKED_TEAM_5V5" || $modeName == "TOUS") && $statsMode->playerStatSummaryType == "RankedTeam5x5")
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
              $showStats = true; ?>

              <h2 style="text-align: center;"><?php echo $totalGame; ?> parties</h1>

              <?php
              //on ne doit pas remplir la gauge s il ny a pas de donnees
              if (($modeName == "RANKED_SOLO" && $statsRankedSolo == false) || ($modeName == "RANKED_TEAM_5V5" && $statsRankedTeam == false) || ($modeName == "TOUS" && $statsRankedSolo == false && $statsRankedTeam == false))
              {
                  $showStats = false;
              }

              //affichage d un message d erreur s il manque des donnees
              if (($modeName == "RANKED_SOLO" || $modeName == "TOUS") && $statsRankedSolo == false)
              { ?>
                  <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                      <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $numberSeason; ?> en mode Ranked Solo.
                  </div>
              <?php }
              if (($modeName == "RANKED_TEAM_5V5" || $modeName == "TOUS") && $statsRankedTeam == false)
              { ?>
                  <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                      <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $numberSeason; ?> en mode Ranked Team 5v5.
                  </div>
              <?php }

              if ($showStats == true)
              { ?>

                <script type="text/javascript">
                  $(function () {

                      var gaugeOptions = {

                          chart: {
                              type: 'solidgauge',
                              backgroundColor: 'rgba(255, 255, 255, 0.1)'
                          },

                          title: null,

                          pane: {
                              center: ['50%', '85%'],
                              size: '130%',
                              startAngle: -90,
                              endAngle: 90,
                              background: {
                                  backgroundColor: 'rgba(0,0,0,0)',
                                  innerRadius: '60%',
                                  outerRadius: '100%',
                                  shape: 'arc'
                              }
                          },

                          tooltip: {
                              enabled: false
                          },

                          // the value axis
                          yAxis: {
                              stops: [
                                  [0, '#DF5353'], // red
                                  [1, '#55BF3B'] // green
                              ],
                              lineWidth: 0,
                              minorTickInterval: null,
                              tickPixelInterval: 400,
                              tickWidth: 0,
                              title: {
                                  y: -70
                              },
                              labels: {
                                  y: 16
                              }
                          },

                          plotOptions: {
                              solidgauge: {
                                  dataLabels: {
                                      y: 5,
                                      borderWidth: 0,
                                      useHTML: true
                                  }
                              }
                          }
                      };

                      // The speed gauge
                      $('#container_victoire').highcharts(Highcharts.merge(gaugeOptions, {
                          yAxis: {
                              min: 0,
                              max: 100,
                              title: {
                                  text: 'Victoire'
                              }
                          },

                          credits: {
                              enabled: false
                          },

                          series: [{
                              name: 'Victoire',
                              data: [<?php echo number_format(($totalWin / ($totalGame)) * 100,0); ?>],
                              dataLabels: {
                                  format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                                      ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                                         '<span style="font-size:12px;color:silver">%</span></div>'
                              },
                              tooltip: {
                                  valueSuffix: ' %'
                              }
                          }]

                      }));


                      // Bring life to the dials
                      /*setInterval(function () {
                          // Speed
                          var chart = $('#container-speed').highcharts(),
                              point,
                              newVal,
                              inc;

                          if (chart) {
                              point = chart.series[0].points[0];
                              inc = Math.round((Math.random() - 0.5) * 100);
                              newVal = point.y + inc;

                              if (newVal < 0 || newVal > 100) {
                                  newVal = point.y - inc;
                              }

                              point.update(newVal);
                          }
                      }, 2000);*/


                  });

                </script>

              <?php } ?>

          <?php if ($statsJoueurRanked != null)
          {

            //traitement des donnees des matches classes si la saison est differente de 2013
            if ($_GET['season'] != "3")
            {
              //modification du mode pour api
              if ($modeName=="TOUS")$modeModif="RANKED_SOLO_5x5,RANKED_TEAM_5x5";
              if ($modeName=="RANKED_SOLO")$modeModif="RANKED_SOLO_5x5";
              if ($modeName=="RANKED_TEAM_5V5")$modeModif="RANKED_TEAM_5x5";

              //requete api
              $matchList = $am->getMatchListByIdPlayerAndSeasonAndMode($_GET['id'],$numberSeason,$modeModif);

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
            <h2>Champions Saison <?php echo $numberSeason; ?></h2>

            <?php foreach($statsJoueurRanked->champions as $champPlayer): ?>
                  <?php foreach($champs as $champ): ?>

                    <?php if ($champ->id == $champPlayer->id && $champPlayer->id != 0) { ?>
                    <?php //var_dump(preg_replace('/\s/', '', $champ->name)); ?>

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
              <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $numberSeason; ?>.
            </div>

         <?php } ?>
    <?php }else{ ?>       
      <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
        <strong>Ho non!</strong> Pas de données disponible pour la saison <?php echo $numberSeason; ?>.
       </div>
     <?php } ?>

      </div>
       
    </div>
    <?php include("includes/footer.php"); ?>
    </div> 

  </div>

  

  </body>
</html>
