<?php include_once 'Database.php';
include 'header_admin.php';?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports clients</title>
</head>
<body>

<div class="container">

    <h3>Choisir un client </h3>
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    
    $stmt = $connexion->prepare("SELECT * FROM clients");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <form class="form-inline" id="form" method="post" action="">
        <select class="custom-select custom-select mr-1" name="client" id="clients-select">
            <option value="">Selectionnez un client</option>
            <?php
            foreach($clients as $client){ //Choix du client
                echo'<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
            }
            ?>
        </select>
        <input type="submit" class="btn btn-primary" name="submit" value="Afficher le rapport">
    </form><br>

    <?php
    if(isset($_POST['submit'])){
        $stmtT = $connexion->prepare("SELECT * FROM rounds WHERE client ='{$_POST['client']}'");
        $stmtT->execute();
        $tournees = $stmtT->fetchAll(PDO::FETCH_OBJ);
        $totalExemplaires=0;
        $totalPoints=0;
        echo'
        <table class="table">
            <thead>
                <tr>
                    <th>Nom de la tournée</th>
                    <th class="text-center">Nombre de points de distributions</th>
                    <th class="text-center">Nombre total d\'exemplaires à distribuer</th>
                </tr>
            </thead>
            <tbody>';
        foreach($tournees as $tournee){
            $stmtP = $connexion->prepare("SELECT * FROM points WHERE tournees = $tournee->id");
            $stmtP->execute();
            $points = $stmtP->fetchAll(PDO::FETCH_OBJ);

            $stmtC = $connexion->prepare("SELECT COUNT(*) FROM points WHERE tournees = $tournee->id");
            $stmtC->execute();
            $points = $stmtC->fetchColumn();
            $totalPoints+=$points;

            $stmtE = $connexion->prepare("SELECT SUM(exemplaires) FROM points WHERE tournees = $tournee->id");
            $stmtE->execute();
            $exemplaires = $stmtE->fetchColumn();
            $totalExemplaires+=$exemplaires;

            echo'
            <tr>
                <td>'.$tournee->nom.'</td>
                <td class="text-center">'.$points.'</td>
                <td class="text-center">'.$exemplaires.'</td>
            </tr>';
        }
        echo'
        <tr>
            <td><b>Total</b></td>
            <td class="text-center"><b>'.$totalPoints.'</b></td>
            <td class="text-center"><b>'.$totalExemplaires.'</b></td>
        </tr>
        </tbody></table>';
    }
    ?>
</div>