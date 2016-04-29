<?php
    require 'required.php';

    //onglet actif
    $activeTab = "";

    //on récupére les stats du joueur
    $am = new PdoApiKeyManager();
    $errorMessage = "";

    $playerProp = $am->getPlayerByName(str_replace(" ", "%20", $_GET['pseudo']));
    if (is_string($playerProp))
    {
        $errorMessage = $playerProp;
    }

    if ($playerProp != null && !is_string($playerProp)) 
    {
      foreach ($playerProp as $player) 
      {
        $playerId = $player->id;
        $leaguePlayer = $am->getInfoLeagueByIdPlayer($player->id);
        $currentGamePlayer = $am->getPlayerInGame($player->id);
      }
    }
    else
    {
      $leaguePlayer = null;
    }

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

    <!-- Icones animés -->
    <link rel="stylesheet" href="css/font-awesome-animation.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <script type="text/javascript" src="jquery/jquery-2.0.3.js"></script>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;height:800px;">
        <div id="affichage">

        <?php if ($errorMessage == ""): ?>
            <!-- tableau associatif pour rank -->
          <?php $replace = array( 
          ' III' => '_3',
          ' II' => '_2',
          ' IV' => '_4',
          ' I' => '_1', 
          ' V' => '_5'); ?>

          <?php if ($playerProp != null) { ?>
            <?php $ranked = false; ?>
            <?php if (!isset($leaguePlayer->status)) { ?>
              <!-- on prend le premier élément du tableau -->
              <?php foreach(current($leaguePlayer) as $league): ?>

                <?php if ($league->queue == "RANKED_SOLO_5x5") { ?>
                  <?php $tierLower = strtolower($league->tier); ?>
                  <div id="champRecherche">
                    <div id="imageRecherche">
                        <?php if (!isset($currentGamePlayer->status)) { // Vérification si le joueur est en jeu ?>
                          <a class='btn btn-info' style="margin-top: 350px;" href='#'><i class='fa fa-bell faa-ring animated' aria-hidden='true'></i> En Jeu</a>
                        <?php } ?>
                    </div>

                    <?php foreach($league->entries as $entry): ?>

                      <?php if ($entry->playerOrTeamId == $playerId): ?>

                          <?php $ranked = true; ?>
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

            <?php } ?>

          <!-- Le joueur n'a pas été trouvé -->
          <?php } else { ?>
            <div id="champRecherche">
                  <div id="imageRecherche"></div>

                      <?php $ranked = true;

                      echo "<br /><b>" . $_GET['pseudo'] . "</b></a>";

                      echo "<br /><br /><br />Ce joueur n'existe pas."; ?>

            </div>
          <?php } ?>

          <!-- Le joueur n'est pas classé -->
          <?php if ($ranked == false) { ?>
              <div id="champRecherche">
                  <div id="imageRecherche"></div>

                      <?php echo "<br /><b>" . $_GET['pseudo'] . "</b></a>"; ?>

                      <br />

                      <img src="http://lkimg.zamimg.com/images/medals/unknown.png"></img>

                      <br /><br /><br />UNRANKED

              </div>
          <?php } ?>

        <?php else: ?>

          <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
              <strong>Ho non!</strong> L'API de League Of Legends a renvoyé une erreur : <?= $errorMessage ?>.
          </div>

        <?php endif ?>
        </div>    
      </div>
      
      <?php include("includes/footer.php"); ?>

  </body>
</html>
