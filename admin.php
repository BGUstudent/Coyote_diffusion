<?php include_once 'Database.php'; ?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin board</title>
</head>
<body>

    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>

    <h2>Assigner une tournée</h2>
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM user");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmtT = $connexion->prepare("SELECT * FROM tournees");
    $stmtT->execute();
    $tournees = $stmtT->fetchAll(PDO::FETCH_OBJ);
    
    $stmtC = $connexion->prepare("SELECT * FROM clients");
    $stmtC->execute();
    $clients = $stmtC->fetchAll(PDO::FETCH_OBJ);

    foreach( $result as $row ) {
        echo $row->prenom . " " . $row->nom . ", permis : " . $row->permis;
        if ($row->tournees){echo ",  tournée assignée ".$tournees[$row->tournees-1]->nom;} //on affiche la tournée actuellement assignée s'il y a 
        echo '<form id="form'.$row->id.'" method="post" action="admin.php">
            Assigner une nouvelle tournée :
            <select name="clients'.$row->id.'" id="clients-select'.$row->id.'" onchange="changeTournees(this.value, '.$row->id.')">
            <option value="">Selectionnez un client</option>';
            foreach($clients as $client){ //Choix du client
                echo'<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
            }
            echo '</select>

            <select name="tournees'.$row->id.'" id="tournees-select'.$row->id.'">
                <option value="">tournée</option>
            </select>

            <input type="submit" value="valider" name="submit'.$row->id.'"></input>
        </form><br>';
        if (isset($_POST['submit'.$row->id])) {
            $stmtUpdate = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
            if($_POST['tournees'.$row->id] == "null"){
                $stmtUpdate->bindValue(1, null, PDO::PARAM_INT);
            }else{
                $stmtUpdate->bindValue(1, $_POST['tournees'.$row->id], PDO::PARAM_INT);
            }
            $stmtUpdate->bindValue(2, $row->id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            //Rafraichir la page avec JS pour éviter les problèmes de header
            ?><script type="text/javascript">window.location.href = 'http://localhost/Ben/admin.php';
            </script><?php
        }
    }
    ?>
    
    <div class="logout">
        <form method="post" action="logout.php">
            <input type="submit" name="logout" value="Se déconnecter" action="logout.php" id="logout">
        </form>
    </div>

<script>
    function changeTournees(that, id){
        document.getElementById("tournees-select"+id).innerHTML=""
        var url = "select_tournee.php"; // service url
        fetch(url, {
            method : 'POST',
            body: JSON.stringify(that)
        })
        .then(function(response){
            return response.json();
        })
        .then(function(data){
            data.forEach(function(item){
                document.getElementById("tournees-select"+id).innerHTML
                +=`<option value="${JSON.stringify(item.id).replace(/\"/g, "")}">${JSON.stringify(item.nom).replace(/\"/g, "")}</option>`
             })
        })
        .catch((error) => console.log(error));
    }
</script>

    </body>
</html>