<?php 

  $am = new PdoApiKeyManager();
  $numberKeyAvailable = $am->getNumberKeysAvailable();
?>

<script type="text/javascript">
      $(document).ready(function() 
      {
        function getNumberKey()
        {
          var oldNumberKey = $("#number_keys_available").html();
          //requête ajax, appel du fichier _returnNumberKeysAvailables.php
          $.ajax(
          {
            type: "GET",
            url: "ajax/_returnNumberKeysAvailables.php",
            dataType : "html",
            //affichage de l'erreur en cas de problème
            error:function(msg, string)
            {
            alert( "Error !: " + string );
            },
            success:function(data)
            {
              //on met à jour la div number_keys_available avec les données reçus si le nombre de clés à changé
              if (oldNumberKey != data)
              {
                //on vide la div et on le cache
                $("#number_keys_available").empty().hide();
                //on affecte les resultats au div
                $("#number_keys_available").append(data);
                //on affiche les resultats avec la transition
                $('#number_keys_available').fadeIn(2000);
                            //on supprime les class du bouton
                $('#button_key').removeClass( "btn-success" );
                $('#button_key').removeClass( "btn-info" );
                $('#button_key').removeClass( "btn-danger" );
                //on met a jours la class
                if (data == 7) {
                  $('#button_key').addClass( "btn-success" );
                } else if (data > 3) {
                  $('#button_key').addClass( "btn-info" );
                } else {
                  $('#button_key').addClass( "btn-danger" );
                }
              }
            }
          });
        }
        setInterval(getNumberKey, 5000)
      })

    </script>


<div style="width:1200px; margin-top: -10px;">
  <?php if ($numberKeyAvailable == 7)  { ?>
    <button id="button_key" class="btn btn-success">Clées disponibles <span class="badge text-success" id="number_keys_available"><?php echo $numberKeyAvailable; ?></span></button>
  <?php } else if ($numberKeyAvailable > 3) { ?>
    <button id="button_key" class="btn btn-info">Clées disponibles <span class="badge text-success" id="number_keys_available"><?php echo $numberKeyAvailable; ?></span></button>
  <?php } else  { ?>
    <button id="button_key" class="btn btn-danger">Clées disponibles <span class="badge text-success" id="number_keys_available"><?php echo $numberKeyAvailable; ?></span></button>
  <?php } ?>
  <div class='btn btn-info' style='float: right;'><i class='fa fa-cogs' aria-hidden='true'></i> Version 6.8 </div>
</div>  
