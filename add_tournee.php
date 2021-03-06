<?php include_once 'Database.php';
include 'header_admin.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout tournées</title>
</head>
<body>
    <div class="container">

    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM clients");
    $stmt->execute(); 
    $clients = $stmt->fetchAll(PDO::FETCH_OBJ);   

    $stmtR = $connexion->prepare("SELECT * FROM rounds");
    $stmtR->execute(); 
    $rounds = $stmtR->fetchAll(PDO::FETCH_OBJ);   
    ?>

    <!-- formulaire d'ajout -->
    <h4>Ajouter une tournée</h4>
    <form class="form-inline" method="post" action="add_tournee.php">
        <input type="text" class="form-control mr-sm-1" id="nom" name="nom" placeholder="Nom de la tournée" required>
        <select class="custom-select custom-select mr-1" name="client" id="client-select">
            <?php
            foreach($clients as $client){
                echo '<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
            }
            ?>
        </select>
        <select class="custom-select custom-select mr-1" name="equipe" id="equipe-select">
            <option value="Solo">Solo</option>
            <option value="Binôme">Binôme</option>'
        </select>
        <input type="submit" class="btn btn-primary" name="submit" value="Ajouter">
    </form>
    <?php
    if(isset($_POST['submit'])){ 
        $stmt = $connexion->prepare("INSERT INTO rounds(nom, client, equipe) VALUES (?, ?, ?) ");
        $stmt->bindParam(1, $_POST['nom']);
        $stmt->bindParam(2, $_POST['client']);
        $stmt->bindParam(3, $_POST['equipe']);
        $stmt->execute();
        echo("<script>location.href='add_tournee.php';</script>");
    }
    ?>
    
    <br><h4>Modifier/Supprimer une tournée</h4><br>

    <?php
    foreach($rounds as $round){
        echo '
        <b>'.$round->client.' -> '.$round->nom. '</b> ('.$round->equipe.')   
        <div class="form-inline"><form class="form-inline mr-4" action="updateRound.php" method="POST">
            <input type="hidden" name="id" value="'.$round->id.'">
            <input class="btn btn-primary btn-sm" type="submit" name="submitU'.$round->id.'" value="Modifier">
        </form>
        <form class="form-inline" action="add_tournee.php" method="POST" onSubmit="return confirm(\'Supprimer cette tournée ?\')">
            <input type="hidden" name="id'.$round->id.'" value="'.$round->id.'">
            <input class="btn btn-danger btn-sm ml-3" type="submit" name="submitD'.$round->id.'" value="Supprimer">
        </form></div><br>';
    }
    if(isset($_POST['submitD'.$round->id])){
        $stmtD = $connexion->prepare("DELETE FROM rounds WHERE id=?");
        $stmtD->bindParam(1, $_POST['id'.$round->id]);
        $stmtD->execute();
        echo("<script>location.href='add_tournee.php';</script>");
    }
    ?>

    </div>
</body>
</html>