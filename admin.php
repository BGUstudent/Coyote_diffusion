<?php include 'header_admin.php';
 include_once 'Database.php'; ?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <title>admin board</title>
</head>
<body>
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

    $stmtT = $connexion->prepare("SELECT u.id, u.prenom, u.nom, u.permis, t.client, t.next_date, t.nom as tt FROM user as u LEFT JOIN rounds AS t ON (u.tournees=t.id)");
    $stmtT->execute();
    $userJ = $stmtT->fetchAll(PDO::FETCH_OBJ);
    ?>
    <form method="POST" action=''>
    <div class="form-row">
        <div class="form-group col-md-6">
            <select class="custom-select mb-1" name="clients" id="clients-select" onchange="changeTournees(this.value)">
                <option value="">Selectionnez un client</option>';
                <?php
                foreach($clients as $client){ //Choix du client
                    echo'<option value="'.$client->nom_client.'">'.$client->nom_client.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <select class="custom-select  mb-1" name="tournees" id="tournees-select" onchange="showUsers(this)">
                <option value="" data-equipe="">puis une tournée</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <select class="custom-select mb-1" name="user" id="user-select">
                <option value="">Attribuer un livreur</option>
                <?php
                foreach($users as $user){
                    echo'<option value="'.$user->id.'">'.$user->prenom.' '.$user->nom.' - Permis : '.$user->permis.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <select class="custom-select mb-1" name="user2" style='display:none' id="user2-select">
                <option value="">Attribuer un 2ème livreur</option>
                <?php
                foreach($users as $user){
                    echo'<option value="'.$user->id.'">'.$user->prenom.' '.$user->nom.' - Permis : '.$user->permis.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label class="pt-2 pl-2"for="date">Date de la tournée </label>
            <input type="date" id="date" name="date">
        </div>
    </div>
        <input type="submit" class="btn btn-primary" name="attribuer" value="Attribuer la tournée">
    </form>

    <div>
    <br><br><h4 class="mb-3">Tournées déjà attribuées</h4>
    <ul>
        <?php
            foreach($userJ as $user){
                if($user->tt){
                    echo '<li>le '. date("l d F", strtotime($user->next_date)) .' : '.$user->client.' - tournée '.$user->tt.' attribué à '.$user->prenom.' '.$user->nom.'</li><br>';
                }
            }
        ?>
    </ul>
    </div>    

</div>
<?php
// fonction pour attribuer la tournée en BDD
if(isset($_POST['attribuer'])){ 
    $stmt = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
    $stmt->bindParam(1, $_POST['tournees']);
    $stmt->bindParam(2, $_POST['user']);
    $stmt->execute();
    $stmtD = $connexion->prepare("UPDATE rounds SET next_date = ? WHERE id = ?");
    $stmtD->bindParam(1, $_POST['date']);
    $stmtD->bindParam(2, $_POST['tournees']);
    $stmtD->execute();
        if($_POST['user2']){
        $stmt = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
        $stmt->bindParam(1, $_POST['tournees']);
        $stmt->bindParam(2, $_POST['user2']);
        $stmt->execute();
    }
    echo("<script>location.href = 'admin.php';</script>");
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