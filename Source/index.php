<?php
    require 'required.php';

    //onglet actif
    $activeTab = "index";

    //on récupére les teams
    $tm = new PdoTeamsManager();
    $teams = $tm->getTeams();

    $pttm = new PdoPlayersToTeamManager();

    $pm = new PdoPlayersManager();
    //$players = $pm->getActivesPlayers();
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

         <center><img src="images/LeagueOfLegendsLogo.png"></center>

          <?php //on récupére les joueurs a surveiller
          foreach($teams as $team)
          {
            echo "<h1>".$team->getName()."</h1><br \>";
            $playersToTeam = $pttm->getPlayersByTeamId($team->getId());
            foreach($playersToTeam as $playerToTeam)
            {
              $player = $pm->getPlayerbyId($playerToTeam->getIdPlayer());
              echo $player->getName()."<br \>";
            }
          } 
          ?>
       
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

  </body>
</html>
