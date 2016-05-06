<?php
    require 'required.php';

    //onglet actif
    $activeTab = "statistiques";

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

    <!-- Icones animés -->
    <link rel="stylesheet" href="css/font-awesome-animation.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <script type="text/javascript" src="jquery/jquery-2.0.3.js"></script>

     <script type="text/javascript">


      function getDataTeam(idTeam)
      {
        //affichage du chargement
       $('#affichage').html("<br /><br /><div style='text-align: center;'><p>Récupération et traitement des données</p><img src='images/loader.gif' alt='chargement...'/></div>")
       
       //requête ajax, appel du fichier _returnNumberKeysAvailables.php
       $.ajax(
       {
        type: "GET",
        url: "ajax/_returnDataTeam.php?idTeam="+idTeam,
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

        Rechercher des matchs en mode normal dans les 10 dernières parties pour :
        <form>
            <select id="selectTeam">
                <?php foreach($teams as $team): ?>
                    <?php echo "<option value='".$team->getId()."'>".$team->getName()."</option>"; ?>
                <?php endforeach; ?>
            </select>
            <div class='btn btn-success' onclick="getDataTeam(selectTeam.value)"><i class='fa fa-chevron-right' aria-hidden='true'></i> Rechercher</div>
        </form>

        <div id="affichage">
       
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

  </body>
</html>
