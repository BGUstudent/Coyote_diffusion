<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les reportings</title>
</head>
<body>
    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>

    <?php
    $dir = 'rapports/';
    $files = array_diff(scandir($dir), array('..', '.')); //Elimine les retours aux dossiers parents.
    echo '<ul>';
    foreach($files as $file){
      echo'<li><a href="rapports/'.$file.'">'.$file.'</a></li>';
    }    
    echo'</ul>';
    ?>

    <br>
    <a href="admin.php">Retour</a>
</body>
</html>