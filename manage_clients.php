<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>utilisateurs</title>
</head>
<body>
    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>
    <div class="container">

    <h4>Liste des clients enregistrés</h4><br>
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM clients");
    $stmt->execute(); 
    $clients = $stmt->fetchAll(PDO::FETCH_OBJ);   

    foreach($clients as $client){
        echo $client->nom_client.
        //bouton delete
        '<form action="delete_client.php" method="POST" onSubmit="return confirm(\'Supprimer le client?\')">
            <input type="hidden" name="nom_client" value="'.$client->nom_client.'">
            <input type="submit" class="btn btn-danger btn-sm" name="submitD'.$client->nom_client.'" value="Supprimer">
        </form><br>';
    }
    ?>
    <!-- formulaire d'ajout -->
    <br>Ajouter un client<br>
    <form method="post" action="add_client.php">
        <input type="text" id="nom_client" name="nom_client" placeholder="Nom du client">
        <input type="submit" class="btn btn-primary" name="add_client" value="Ajouter">
    </form>
    </div>
</body>
</html>