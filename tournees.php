<?php include_once 'Database.php';
include 'header_admin.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gérer les tournées</title>
</head>
<body>
<div class="container-fluid">
    <?php
    if(isset($_SESSION['roundID'])){
        $_POST['tournee_info'] = $_SESSION['roundID'];
        unset($_SESSION['roundID']);
    }
    ?>
    <!-- Selection de la tournée -->
    <h4>Selectionner une tournée</h4>

    <form class="form-inline" method="post" action="tournees.php">
        <select class="custom-select custom-select mr-1" name="tournees" id="tournees-select">
        <?php
        $database = new Database();
        $connexion = $database->getConnection();
        $stmt = $connexion->prepare("SELECT * FROM rounds");
        $stmt->execute();
        $tournees = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($tournees as $row){
            if(isset($_POST['tournee_info']) && ($_POST['tournee_info']===$row->id)){
                echo '<option value="'.$row->id.'" selected>'.$row->nom.'</option>';
            }else{
                echo '<option value="'.$row->id.'">'.$row->nom.'</option>';
            }
        }
        ?>
        </select>
        <input class="btn btn-primary" type="submit" value="valider" name="submitT" id="submitT"></input>
    </form>
    <br>
    <?php
    if(isset($_POST['tournee_info'])){
        echo '<script>document.getElementById("submitT").click(); </script>';
    }
    
    if(isset($_POST['submitT'])){ 
        $stmtA = $connexion->prepare("SELECT nom FROM rounds WHERE id=?");
        $stmtA->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
        $stmtA->execute(); 
        $nom_tournee = $stmtA->fetch(PDO::FETCH_OBJ);   
    ?>
    
    <button class='btn btn-link' onclick='expand()'>Ajouter un point de livraison à la tournée <?php echo $nom_tournee->nom ?></button>
    <div id="hide" style='display:none'>
    <!-- formulaire d'ajout -->
        <form method="post" action="add.php">
            <input type="text" id="nomP" name="nomP" placeholder="Nom du point" style="max-width:220px;" required>
            <input type="text" id="adresseP" name="adresseP" placeholder="Adresse" style="max-width:220px;" required>
            <input type="text" id="codePostal" name="codePostal" placeholder="Code postal" style="max-width:220px;" required>
            <input type="text" id="villeP" name="villeP" placeholder="Ville" style="max-width:220px;" required>
            <input type="hidden" name="tournee_id" value="<?php echo $_POST['tournees'] ?>">
            <input type="text" id="infos" name="infos" placeholder="infos" style="max-width:220px;">
            <input type="text" id="exemplaires" name="exemplaires" placeholder="nb de exemplaires" style="max-width:220px;" required>
            <input type="text" id="categorie" name="categorie" placeholder="catégorie" style="max-width:220px;" required>
            <input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter">
        </form>
        <br>

    <!-- formulaire injection CSV --> 
        Importer un fichier .csv: (categorie, infos, nom, adresse, code_postal, ville, exemplaires, ordre)
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <label for="file">Selection le fichier .csv à importer</label>
            <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
            <input type="file" id="file" name="fileToUpload" accept=".csv">
            <input type="hidden" name="tournee_id" value="<?php echo $_POST['tournees'] ?>">
            <input type="submit" name='submit' class="btn btn-primary" value="Importer"></input>
        </form>
    </div>
    <br>

    <!-- Afficher les points d'une tournée -->
        <br><ul id="points">
        <?php
        $stmtP = $connexion->prepare("SELECT * FROM points WHERE tournees = ? ORDER BY ordre ASC");
        $stmtP->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
        $stmtP->execute();
        $points = $stmtP->fetchAll(PDO::FETCH_OBJ);
        foreach($points as $x){
            echo'<div id="'.$x->id.'">';
                if($x->motif && $x->motif !== "livré"){
                    echo'<div style="max-width:1404px;" class="bg-danger">Lors de la dernière tournée : <b>'.$x->motif.'</b></div>';
                }elseif($x->motif && $x->exemplaires > $x->distribués){
                    echo'<div style="max-width:1404px;" class="bg-warning">Lors de la dernière tournée : <b>'.$x->motif.'</b>, '.$x->distribués.' exemplaires distribués</div>';
                }
                if($x->exemplaires < $x->distribués){
                    echo'<div style="max-width:1404px;" class="bg-success">Lors de la dernière tournée : <b>'.$x->motif.'</b>, '.$x->distribués.' exemplaires distribués</div>';
                }
                if($x->commentaires){
                    echo'<div style="max-width:1404px;" class="bg-info">Commentaire du livreur : '.$x->commentaires.'</div>';
                }
                echo '<li id="li'.$x->id.'" class="form-inline mb-4" method="post" action="">
                <svg class="grab" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-horizontal" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" id="nom'.$x->id.'" name="nom" value="'.$x->nom.'">
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" id="adresse'.$x->id.'" name="adresse" value="'.$x->adresse.'">
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" id="codePostal'.$x->id.'" name="codePostal" value="'.$x->code_postal.'">
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" id="ville'.$x->id.'" name="ville" value="'.$x->ville.'">
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" placeholder="infos" id="infos'.$x->id.'" name="infos" value="'.$x->infos.'">
                <input class="form-control mr-sm-1" style="max-width:50px;" type="text" id="exemplaires'.$x->id.'" name="exemplaires" value="'.$x->exemplaires.'">
                <input class="form-control mr-sm-1" type="text" style="max-width:220px;" id="categorie'.$x->id.'" name="categorie" value="'.$x->categorie.'">
                <input type="hidden" id="id'.$x->id.'" name="id" value="'.$x->id.'">
            
                <button class="btn btn-primary mr-2 btn-sm" onclick="updateOne('.$x->id.')" style="width:100px">Modifier</button>

                <button class="btn btn-danger btn-sm" onclick="deleteOne('.$x->id.')" style="width:100px">Supprimer</button>

                <div id="done'.$x->id.'"></div>
                </li>
            </div>';
        }
        echo'</ul>';
    }
    ?>
    </div>
        
<script>
// Drag and drop, sort, send new postition to DB
$(document).ready(function () {
    $('#points').sortable({
        axis: 'y',
        //Jaune quand drag
        sort: function( event, ui ) {
            $(ui.item).css("background-color", "yellow");
        },
        //Redevient blanc quand dragover puis inscrit les changements en BDD
        stop: function (event, ui) {
            $(ui.item).css("background-color", "white");
            var data = $(this).sortable('toArray');
            var url = "updateOrder.php"; // service url
            var data = JSON.stringify(data);
            fetch(url, {
                method : 'PUT',
                body: data
            })
            .catch((error) => console.log(error));
        }
    });
});

//update
function updateOne(y){
    var url = "updateOne.php"; // service url
    var data = {}; 
    data.id=(document.getElementById('id'+y).value);
    data.nom=(document.getElementById('nom'+y).value);
    data.adresse=(document.getElementById('adresse'+y).value);
    data.codePostal=(document.getElementById('codePostal'+y).value);
    data.ville=(document.getElementById('ville'+y).value);
    data.infos=(document.getElementById('infos'+y).value);
    data.exemplaires=(document.getElementById('exemplaires'+y).value);
    data.categorie=(document.getElementById('categorie'+y).value);
    var data = JSON.stringify(data);

    fetch(url, {
        method : 'PUT',
        body: data
        })
    .then(function(){
        document.getElementById("done"+y).innerHTML=' Modifié !';
    })
    .catch((error) => console.log(error));
}
//delete
function deleteOne(y){
    if(confirm("Supprimer le point?")){
        var url = "deletePoint.php"; // service url

        var data=(document.getElementById('id'+y).value);
        var data = JSON.stringify(data);

        fetch(url, {
            method : 'POST',
            body: data
            })
        .catch((error) => console.log(error));
    }
    location.reload(true)
}

// Script pour toggle/hide
function expand() {
    var x = document.getElementById("hide");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
</script>
</div>
</body>
</html>