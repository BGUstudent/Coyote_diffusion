<?php include_once 'Database.php'; ?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>monitoring</title>
</head>
<body>

<!-- header -->
<?php include 'header_admin.php';?>
<br>
<div class="container">

    <h3>Choisir une tournée </h3>
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
    ?>
    <form class="form-inline" id="form" method="post" action="admin.php">
        <select class="custom-select custom-select mr-1" name="clients" id="clients-select" onchange="changeTournees(this.value)">
            <option value="">Selectionnez un client</option>
            <?php
            foreach($clients as $client){ //Choix du client
                echo'<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
            }
            ?>
        </select>
        <select class="custom-select custom-select mr-1" name="tournees" id="tournees-select" onchange="changeMonitoring(this.value)">
            <option value="">puis une tournée</option>
        </select>
    </form><br>

    <div id='doneBy'></div>
    <div class='' id='monitoring' style="max-width: 600px;"><b>Veuillez selectionner une tournée à surveiller</b></div>
        <br>
    <div id="buttonsToHide" style='display:none'>
        <!-- créer le PDF de reporting -->
        <div class="pdf">
            <form method="post" action="pdf.php">
                <input type="hidden" name="users" id="users" value="">
                <input type="hidden" name="users2" id="users2" value="">
                <input type="hidden" name="tournee" id="tournee" value="">
                <input type="submit" class="btn btn-success" name="pdf" value="Valider la tournée" action="pdf.php" id="pdf">
            </form>
        </div>
        <br>

        <!-- Reset -->
        <div>
            <form method="post" action="reset.php">
                <input type="hidden" name="user_info" id="user_info" value="">
                <input type="hidden" name="user2_info" id="user2_info" value="">
                <input type="hidden" name="tournee_info" id="tournee_info" value="">
                <input type="submit" class="btn btn-warning" name="reset" value="Réinitialiser la tournée" action="reset.php" id="reset">
            </form>
        </div>
    </div>
</div>

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
                +=`<option value="${JSON.stringify(item.id).replace(/\"/g, "")}">
                ${JSON.stringify(item.nom).replace(/\"/g, "")}</option>`
             })
        })
        .catch((error) => console.log(error));
    }

    function changeMonitoring(that){
        document.getElementById("buttonsToHide").style.display = "block"; 
        document.getElementById("tournee").value = that;//change 'tournee' value
        document.getElementById("tournee_info").value = that;//change 'tournee_info' value
        document.getElementById("doneBy").innerHTML=''

        document.getElementById("monitoring").innerHTML=""
        var url = "monitoring_one.php"; // service url
        fetch(url, {
            method : 'POST',
            body: JSON.stringify(that)
        })
        .then(function(response){
            return response.json();
        })
        .then(function(data){
            document.getElementById("monitoring").innerHTML+=
            `<ul class="list-group">`
            data.forEach(function(item){
                if(item.motif=="livré"){ //Si livré
                    document.getElementById("monitoring").innerHTML+=`
                        <li class="list-group-item" style='background-color: #abfaba;'>
                        <b>${JSON.stringify(item.nom).replace(/\"/g, "")}</b>,<br> 
                         ${JSON.stringify(item.adresse).replace(/\"/g, "")}<br>
                         <b>${JSON.stringify(item.motif).replace(/\"/g, "")}</b>
                         à : ${JSON.stringify(item.heure).replace(/\"/g, "")}<br>
                         Prévu : ${JSON.stringify(item.exemplaires).replace(/\"/g, "")}
                         Distribués : ${JSON.stringify(item.distribués).replace(/\"/g, "")}</li>`
                }else if(item.last_update){ //Si livraison impossible
                    document.getElementById("monitoring").innerHTML+=`
                        <li class="list-group-item bg-warning">
                        <b>${JSON.stringify(item.nom).replace(/\"/g, "")}</b>,<br> 
                         ${JSON.stringify(item.adresse).replace(/\"/g, "")}<br>
                         <b>${JSON.stringify(item.motif).replace(/\"/g, "")}</b>, 
                         heure de passage : ${JSON.stringify(item.heure).replace(/\"/g, "")}<br>
                         Prévu : ${JSON.stringify(item.exemplaires).replace(/\"/g, "")}
                         Distribués : ${JSON.stringify(item.distribués).replace(/\"/g, "")}</li>`
                }else{ //Si pas encore atteint
                    document.getElementById("monitoring").innerHTML+=`
                        <li class="list-group-item">
                        <b>${JSON.stringify(item.nom).replace(/\"/g, "")}</b>,<br> 
                         ${JSON.stringify(item.adresse).replace(/\"/g, "")}<br>
                         Prévu : ${JSON.stringify(item.exemplaires).replace(/\"/g, "")}</li>`
                }
             })
            document.getElementById("monitoring").innerHTML+='</ul>'
            //transmettre l'id de la (des) personne(s) affectée(s) à la tournée
            document.getElementById("users").value=JSON.stringify(data[0].users[0].id).replace(/\"/g, "")
            document.getElementById("users2").value=JSON.stringify(data[0].users[1].id).replace(/\"/g, "")

            document.getElementById("user_info").value=JSON.stringify(data[0].users[0].id).replace(/\"/g, "")
            document.getElementById("user2_info").value=JSON.stringify(data[0].users[1].id).replace(/\"/g, "")

            //Effectué par...
            document.getElementById("doneBy").innerHTML='<h4>Effectué par '
            +JSON.stringify(data[0].users[0].prenom).replace(/\"/g, "")
            +' '+JSON.stringify(data[0].users[0].nom).replace(/\"/g, "")+'</h4>';
            // Si 2 livreurs
            if(JSON.stringify(data[0].users[1])){
                document.getElementById("doneBy").innerHTML+=
                '<h4> et '+JSON.stringify(data[0].users[1].prenom).replace(/\"/g, "")
                +' '+JSON.stringify(data[0].users[1].nom).replace(/\"/g, "")+'</h4>'
            }
        })
        .catch((error) => console.log(error));
    }

</script>

</body>
</html>