<?php 
//securite
if (!isset($activeTab))header('Location: ../index.php'); 
?>

<div class="header">
    <ul class="nav nav-pills pull-right">
      <?php if ($activeTab == "index") { ?>
      <li class="active"><a href="index.php">Mes joueurs</a></li>
      <?php } else { ?>
      <li><a href="index.php">Mes joueurs</a></li>
      <?php } ?>
      <?php if ($activeTab == "team") { ?>
      <li class="active"><a href="team.php">Team</a></li>
      <?php } else { ?>
      <li><a href="team.php">Team</a></li>
      <?php } ?>
      <?php if ($activeTab == "statistiques") { ?>
      <li class="active"><a href="statistiques.php">Statistiques</a></li>
      <?php } else { ?>
      <li><a href="statistiques.php">Statistiques</a></li>
      <?php } ?>
      <li style="margin-top:6px;margin-left:50px;margin-right:-50px;">
          <form action="searchPlayer.php" method="get">
            <input type="text" name="pseudo" value="Pseudo" onclick="this.value='';" style="vertical-align: middle;">
             <button type="submit" class="btn btn-primary">Rechercher</button>
           </form>
      </li>
      </ul>
      <a href="index.php" style="text-decoration:none;"><h3 class="text-muted">League Of Legends Stats <img src="images/Beta.png"></img></h3></a>
</div>
<br />
