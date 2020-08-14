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

<div class="container">
    <h2>Assigner une tournée</h2>

    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM user");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
  
    $stmtC = $connexion->prepare("SELECT * FROM clients");
    $stmtC->execute();
    $clients = $stmtC->fetchAll(PDO::FETCH_OBJ);
    ?>
    <select name="clients" id="clients-select" onchange="changeTournees(this.value)">
    <option value="">Selectionnez un client</option>';
    <?php
    foreach($clients as $client){ //Choix du client
        echo'<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
    }
    ?>
    </select>

    <div id="showUsers">
        <form method="POST" action='admin.php'>
            <select name="tournees" id="tournees-select" onchange="showUsers(this)">
                <option value="" data-equipe="">puis une tournée</option>
            </select>
            <select name="user" id="user-select">
                <option value="">Attribuer un livreur</option>
                <?php
                foreach($users as $user){
                    echo'<option value="'.$user->id.'">'.$user->prenom.' '.$user->nom.' - Permis : '.$user->permis.'</option>';
                }
                ?>
            </select>
            <select name="user2" style='display:none' id="user2-select">
                <option value="">Attribuer un 2ème livreur</option>
                <?php
                foreach($users as $user){
                    echo'<option value="'.$user->id.'">'.$user->prenom.' '.$user->nom.' - Permis : '.$user->permis.'</option>';
                }
                ?>
            </select>
            <input type="submit" name="attribuer" value="Attribuer la tournée">
        </form>
    </div>
</div>
    <?php
    // fonction pour attribuer la tournée en BDD
    if(isset($_POST['attribuer'])){ 
        $stmt = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
        $stmt->bindParam(1, $_POST['tournees']);
        $stmt->bindParam(2, $_POST['user']);
        $stmt->execute();
        if($_POST['user2']){
            $stmt = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
            $stmt->bindParam(1, $_POST['tournees']);
            $stmt->bindParam(2, $_POST['user2']);
            $stmt->execute();
        }
    }
    ?>
<script>
    function changeTournees(that){
        document.getElementById("tournees-select").innerHTML=""
        var url = "select_tournee.php"; // service url
        fetch(url, {
            method : 'POST',
            body: JSON.stringify(that)
        })
        .then(function(response){
            return response.json();
        })
        .then(function(data){
            document.getElementById("tournees-select").innerHTML
            +=`<option value="">- Selectionnez -</option>`
            data.forEach(function(item){
                document.getElementById("tournees-select").innerHTML
                +=`<option value="${JSON.stringify(item.id).replace(/\"/g, "")}" data-equipe="${JSON.stringify(item.equipe).replace(/\"/g, "")}">${JSON.stringify(item.nom).replace(/\"/g, "")}</option>`
             })
        })
        .catch((error) => console.log(error));
    }

    function showUsers(that){
        if(that.options[that.selectedIndex].getAttribute("data-equipe")=="Binôme"){
            document.getElementById("user2-select").style.display = "inline";
        }else{
            document.getElementById("user2-select").style.display = "none";
        }
    }
</script>

    </body>
</html>