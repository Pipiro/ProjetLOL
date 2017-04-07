<?php
    require 'required.php';

    //onglet actif
    $activeTab = "team";

    //on récupére les stats ranked et summary du joueur
    $am = new PdoApiKeyManager();
    if (isset($_POST['season']))
    {
      $numberSeason = $_POST['season'];    
    }
    else if (isset($_GET['season']))
    {
      $numberSeason = $_GET['season'];
    }
    else
    {
      $numberSeason = 2017;
    }

    $player = $am->getPlayerById($_GET['id']);
    
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
    <!-- <script type="text/javascript" src="jquery/jquery-ui-1.11.4.js"></script> -->

    <!-- incluion highcharts -->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/solid-gauge.js"></script>

   <script type="text/javascript">

      // Au premier chargement on appel ajax
      $( document ).ready(function() {

          getStatsPlayer(<?= $numberSeason; ?>, "<?= $modeName; ?>");

      });

      function getStatsPlayer(season, mode)
      {
       //on va mettre a jour les bouton actif
       $('#2013').removeClass('active');
       $('#2014').removeClass('active');
       $('#2015').removeClass('active');
       $('#2016').removeClass('active');
       $('#2017').removeClass('active');
       $('#TOUS').removeClass('active');
       $('#RANKED_SOLO').removeClass('active');
       $('#RANKED_TEAM_5V5').removeClass('active');

       $('#'+season).toggleClass('active');
       $('#'+mode).toggleClass('active');

       //il faut gerer la particularite de la saison 2013
       if (season == 2013) { season = 3};
       
       //affichage du chargement
       $('#contenu_stats').html("<br /><br /><div style='text-align: center;'><p>Récupération et traitement des données</p><img src='images/loader.gif' alt='chargement...'/></div>")

       //on remet le compteur de victoire a 0 si il a déjà été remplis
       if ($('#container_victoire').highcharts() != null)
       { 
         var chart = $('#container_victoire').highcharts(),point;
         point = chart.series[0].points[0];
         point.update(0);
       }
       
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
       // gestion de la particularité des team ranked lors de la saison 2016
       if (season != 2016 || season != 2017)
       {
           $("#RANKED_TEAM_5V5").removeClass('disabled');
       }
        else
       {
          $("#RANKED_TEAM_5V5").toggleClass('disabled');
       }
      }

      function updateSeason(mode)
      {
       $("#input_2013").attr("onChange", "updateMode(2013);getStatsPlayer(2013,'"+mode+"')");
       $("#input_2014").attr("onChange", "updateMode(2014);getStatsPlayer(2014,'"+mode+"')");
       $("#input_2015").attr("onChange", "updateMode(2015);getStatsPlayer(2015,'"+mode+"')");
       $("#input_2016").attr("onChange", "updateMode(2016);getStatsPlayer(2016,'"+mode+"')");
       $("#input_2017").attr("onChange", "updateMode(2017);getStatsPlayer(2017,'"+mode+"')");
      }

    </script>

  </head>

  <body>

    <div class="container">

      <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;overflow : hidden;">
        <div id="affichage" style="margin-top: -20px;">
          <div id="filtre" style="float:right; width: 400px;">
           <form action='statsPlayer.php' method='post'>
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
              <?php if ($numberSeason == 2017) { ?>
                <label id="2017" class="btn btn-info active">
                  <input name="season" id="input_2017" value="2017" type="radio" onChange="updateMode(2017);getStatsPlayer(2017, '<?php echo $modeName; ?>')">2017
                </label>
              <?php } else { ?>
                <label id="2017" class="btn btn-info">
                  <input name="season" id="input_2017" value="2017" type="radio" onChange="updateMode(2017);getStatsPlayer(2017, '<?php echo $modeName; ?>')">2017
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
                <?php if ($modeName == "RANKED_TEAM_5V5" && $numberSeason != 2016) { ?>
                  <label id="RANKED_TEAM_5V5" class="btn btn-info active">
                    <input name="mode" id="input_RANKED_TEAM_5V5" value="RANKED_TEAM_5V5" type="radio" onChange="updateSeason('RANKED_TEAM_5V5');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_TEAM_5V5')">Ranked Team 5v5
                  </label>
                <?php } else if ($numberSeason != 2016 && $numberSeason != 2017) { ?>
                  <label id="RANKED_TEAM_5V5" class="btn btn-info">
                    <input name="mode" id="input_RANKED_TEAM_5V5" value="RANKED_TEAM_5V5" type="radio" onChange="updateSeason('RANKED_TEAM_5V5');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_TEAM_5V5')">Ranked Team 5v5
                  </label>
                <?php } else if ($numberSeason == 2017) { ?>
                  <label id="RANKED_FLEX_5V5" class="btn btn-info">
                    <input name="mode" id="input_RANKED_FLEX_5V5" value="RANKED_FLEX_5V5" type="radio" onChange="updateSeason('RANKED_FLEX_5V5');getStatsPlayer(<?php echo $numberSeason; ?>, 'RANKED_FLEX_5V5')">Ranked Flex 5v5
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

        <div id="statutChargement" style='clear:both;'>


        </div>

        <div id="contenu_stats" style='clear:both;'>


        </div>
       
    </div>
    <?php include("includes/footer.php"); ?>
    </div> 

  </div>

  

  </body>
</html>
