<?php
        require 'required.php';

        //onglet actif
        $activeTab = "team";

        //on récupére les joueurs a surveiller
        $pm = new PdoPlayersManager();
        $players = $pm->getActivesPlayers();

        //on récupére les infos de league pour chaque joueurs
        $am = new PdoApiKeyManager();
        $playersStats = null;

        foreach($players as $player)
        {
          $playersStats[$player->getName()] = $am->getInfoLeagueByIdPlayer($player->getIdLol());           
        }

        //var_dump($playersStats);
        /*$searchFreeChamp = "http://prod.api.pvp.net/api/lol/euw/v1.1/champion?api_key=720315b6-0816-4222-b740-291bc1ae4af9";
        $lol = curl_init();
        curl_setopt($lol, CURLOPT_URL, $searchFreeChamp);
        curl_setopt($lol, CURLOPT_RETURNTRANSFER, TRUE);
        $freeChamp = curl_exec($lol);
        $freeChamp = json_decode($freeChamp);*/

    function strReplaceAssoc(array $replace, $subject) 
    { 
        return str_replace(array_keys($replace), array_values($replace), $subject);    
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

    <script type="text/javascript" src="jquery/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="jquery/script_deplacement.js"></script>

  </head>

  <body>

    <div class="container">

     <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;height:800px;">
        <div id="affichage">

        <!-- tableau associatif pour rank -->
        <?php $replace = array( 
        ' III' => '_3',
        ' II' => '_2',
        ' IV' => '_4',
        ' I' => '_1', 
        ' V' => '_5'); 

        $indiceBoucle = 1; //indice boucle pour calculer les divs ?>

        <?php foreach($playersStats as $playerName => $playerStats): ?>
          <?php if (!isset($playerStats->status)) { ?>
            <?php $playerIdLol = key($playerStats); // récupération de la clé du joueur?>
            <?php foreach(current($playerStats) as $league): ?>
              <?php if ($league->queue == "RANKED_SOLO_5x5") { ?>
                <?php $tierLower = strtolower($league->tier); ?>
                <?php echo "<div id='champ".$indiceBoucle."'>"; ?>
                  <?php echo "<div id='imageChamp".$indiceBoucle."'></div>"; ?>

                  <?php foreach($league->entries as $entry): ?>
                    <?php if ($entry->playerOrTeamId == $playerIdLol): ?>
            
                          <?php echo "<a href='statsPlayer.php?id=" . $entry->playerOrTeamId . "&season=2016'>" . "<br /><b>" . $entry->playerOrTeamName . "</b></a>"?>

                          <!-- on parse la ligue pour récupérer l'image  -->
                          <?php $imageLeagueLien = "http://lkimg.zamimg.com/images/medals/" . $tierLower . " " . $entry->division . ".png";
                          $imageLeague = strReplaceAssoc($replace, $imageLeagueLien); ?>

                          <?php echo "<img src='". $imageLeague . "'></img>"; ?>

                          <?php echo $league->name . "<br />"?>
                          <?php echo $entry->leaguePoints . " LP" ?>
                          <?php echo strtoupper($tierLower); ?>
                          <?php echo $entry->division ?>
                          <?php if (isset($entry->miniSeries)) {
                          $series = str_split($entry->miniSeries->progress);
                          echo "<br />";
                          foreach ($series as $serie ) {
                            if ($serie == "W")
                            {
                              echo "<img src='http://lkimg.zamimg.com/assets/000/000/356.png'></img>";
                            }
                            else if ($serie == "L")
                            {
                              echo "<img src='http://lkimg.zamimg.com/assets/000/000/357.png'></img>";
                            }
                            else if ($serie == "N")
                            {
                              echo "<img src='http://lkimg.zamimg.com/assets/000/000/358.png'></img>";
                            }
                          }

                        } ?>

                  <?php endif ?>
                <?php endforeach; ?>
                </div>
              <?php } ?>
            <?php endforeach; ?>
          <?php } else { ?>
          <!-- joueur unranked -->
           <?php echo "<div id='champ".$indiceBoucle."'>"; ?>
                <?php echo "<div id='imageChamp".$indiceBoucle."'></div>"; ?>

                      <?php echo "<a href='statsPlayer.php?id=" . $pm->getIdLolByNamePlayer($playerName) . "&season=2016'>" . "<br /><b>" . $playerName . "</b></a>";


                      echo "<br /><br /><br />UNRANKED"; ?>

            </div>
          <?php } ?>

          <?php $indiceBoucle++; ?>
        <?php endforeach; ?>

        </div>

    <!-- <div id="freeHeroes">
      <p>Free Heroes : </p>

      <?php foreach($freeChamp->champions as $champ): ?>

            <?php if ($champ->freeToPlay == "FREE") { ?>
              <div id="caseChamp">
              <?php echo "<img src='http://ddragon.leagueoflegends.com/cdn/3.15.5/img/champion/" . $champ->name . ".png'". "title='". $champ->name . "'></img>"; ?>
            </div>
          <?php } ?>

        
      <?php endforeach; ?>
    </div>
    
    <div id="myProfile">

      <p>Mon profil</p>

      <?php foreach($myLeague as $league): ?>
        <?php echo "<h1>" . $league->queue . " - " . $league->name . "</h1>" ?>
        <?php foreach($league->entries as $entry): ?>
          <?php if ($entry->playerOrTeamName == "Pipiroo" || $entry->playerOrTeamName == "Incredible Geeks" || $entry->playerOrTeamName == "FEED TO FEED"): ?>
              <li>
              <?php echo $entry->playerOrTeamName ?>
              <?php echo $entry->leaguePoints . " LP" ?>
              <?php echo $entry->tier ?>
              <?php echo $entry->rank ?>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php //echo serialize($league) ?>
      <?php endforeach; ?>

      <?php //Secho serialize($myLeague) ?>

    </div> -->

  </div>

  <?php include("includes/footer.php"); ?>

  </div>

   

      

  </div> <!-- /container -->

  </body>
</html>
