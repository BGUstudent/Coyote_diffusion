<?php include 'header_admin.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les reportings</title>
</head>
<body>

    <div class="container">

    <?php
    $dir = 'rapports/';
    $files = array_diff(scandir($dir), array('..', '.')); //Elimine les retours aux dossiers parents.
    echo '<ul>';
    foreach($files as $file){
      echo'<li><a href="rapports/'.$file.'">'.$file.'</a></li>';
    }    
    echo'</ul>';
    ?>
  </div>
</body>
</html>