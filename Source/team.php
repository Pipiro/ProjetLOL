<?php
    require 'required.php';

    // onglet actif
    $activeTab = "team";

    // récupération des tems
    $tm = new PdoTeamsManager();
    $teams = $tm->getTeams();
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
    <!-- <script type="text/javascript" src="jquery/script_deplacement.js"></script> -->



    <script type="text/javascript">
     // Au premier chargement on appel ajax
      $( document ).ready(function() {
          getPlayersTeam(<?= $teams[0]->getId() ?>);
      });

      function getPlayersTeam(idTeam)
      {
        //affichage du chargement
       $('#affichage').html("<br /><br /><div style='text-align: center;'><p>Récupération et traitement des données</p><img src='images/loader.gif' alt='chargement...'/></div>")
       
       //requête ajax, appel du fichier _returnNumberKeysAvailables.php
       $.ajax(
       {
        type: "GET",
        url: "ajax/_returnPlayersTeam.php?idTeam="+idTeam,
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
          $("#affichage").empty().hide();
          //on affecte les resultats au div
          $("#affichage").append(data);
          //on affiche les resultats avec la transition
          $('#affichage').fadeIn(800);
        }
       });
      }

    </script>
  </head>

  <body>

    <div class="container">

     <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;height:800px;">

        <form action='statsPlayer.php' method='post'>
                  <select onchange="getPlayersTeam(this.value)">
                    <?php foreach($teams as $team): ?>
                      <?php echo "<option value='".$team->getId()."'>".$team->getName()."</option>"; ?>
                    <?php endforeach; ?>
                  </select>
        </form>

        <div id="affichage">

        </div>

      </div>

      <?php include("includes/footer.php"); ?>

   </div> <!-- /container -->

  </body>
</html>
