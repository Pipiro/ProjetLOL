<?php
    require 'required.php';

    //onglet actif
    $activeTab = "";

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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <style type=”text/css”>

       .ms-vb2-icon { background: red; width: 10px; height: 10px; }
    
    </style>

    <script type="text/javascript" src="jquery/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="jquery/jquery-ui.js"></script>

    <script>


    $( document ).ready(function() {

      $('#test').on('click', function(){
          $('#test').removeClass('btn-info');
          $('#test').toggleClass('btn-success');
          $('#test').html("<i class='fa fa-spinner fa-spin fa-3x fa-fw margin-bottom' aria-hidden='true'></i></a>");
          //$('#test').html("<i class='glyphicon glyphicon-ok-sign fa-2x pull-left' aria-hidden='true'></i>&nbspJoueur ajouté</a>");
          

      });


      var img = { x1: 830, x2: 1030, y1: 116, y2:516 , url: "http://ddragon.leagueoflegends.com/cdn/img/champion/splash/Annie_0.jpg" };
      $("#myDiv").css({
      width: img.x2 - img.x1,
      height: img.y2 - img.y1,
      background: "url('" + img.url + "')",
      backgroundPosition: -img.x1 + "px " + -img.y1 + "px"
    });

});
</script>


  </head>

  <body>

    <div class="container">

      <?php include("includes/header.php"); ?>

      <div class="jumbotron" style="width:1200px;height:800px;">
        <div id="affichage">

          <a id="test" name="test" class="btn btn-info" href="#">
            <i class="fa fa-arrow-up fa-2x pull-left" aria-hidden="true"></i> Ajouter dans mes joueurs</a>
        
        <br \><br \>
            Window:
            <div id="myDiv"></div>

            Full Image:
            <img src="http://dummyimage.com/600x400/000/fff.jpg&text=Test+Image" />
        </div>    
      </div>
      
      <?php include("includes/footer.php"); ?>

  </body>
</html>
