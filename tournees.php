<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gérer les tournées</title>
</head>
<body>
    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>
<div class="container-fluid">

    <!-- Selection de la tournée -->
    <h4>Selectionner une tournée</h4>

    <form class="form-inline" method="post" action="tournees.php">
        <select class="custom-select custom-select mr-1" name="tournees" id="tournees-select">
        <?php
        $database = new Database();
        $connexion = $database->getConnection();
        $stmt = $connexion->prepare("SELECT * FROM tournees");
        $stmt->execute();
        $tournees = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($tournees as $row){
            echo '<option value="'.$row->id.'">'.$row->nom.'</option>';
        }
        ?>
        </select>
        <input class="btn btn-primary" type="submit" value="valider" name="submitT"></input>
    </form>
    <br>
        <?php
        if(isset($_POST['submitT'])){ 
            $stmtA = $connexion->prepare("SELECT nom FROM tournees WHERE id=?");
            $stmtA->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
            $stmtA->execute(); 
            $nom_tournee = $stmtA->fetch(PDO::FETCH_OBJ);   
        ?>
<button class='btn btn-link' onclick='expand()'>Ajouter un point de livraison à la tournée <?php echo $nom_tournee->nom ?></button>
<div id="hide" style='display:none'>
<!-- formulaire d'ajout -->
    <form method="post" action="add.php">
        <input type="text" id="nomP" name="nomP" placeholder="Nom du point" required>
        <input type="text" id="adresseP" name="adresseP" placeholder="Adresse" required>
        <input type="text" id="codePostal" name="codePostal" placeholder="Code postal" required>
        <input type="text" id="villeP" name="villeP" placeholder="Ville" required>
        <input type="hidden" name="tournee_id" value="<?php echo $_POST['tournees'] ?>">
        <input type="text" id="infos" name="infos" placeholder="infos">
        <input type="text" id="exemplaires" name="exemplaires" placeholder="nb de exemplaires" required>
        <input type="text" id="categorie" name="categorie" placeholder="catégorie" required>
        <input type="submit" name="ajouter" value="Ajouter">
    </form>
    <br>

<!-- formulaire injection CSV --> 
        Importer un fichier .csv:
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <label for="file">Selection le fichier .csv à importer</label>
            <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
            <input type="file" id="file" name="fileToUpload" accept=".csv">
            <input type="hidden" name="tournee_id" value="<?php echo $_POST['tournees'] ?>">
            <input type="submit" name='submit'></input>
        </form>
</div>
<br>

<!-- Afficher les points d'une tournée -->
    <div id=points>
    <?php
    $stmtP = $connexion->prepare("SELECT * FROM points WHERE tournees = ? ");
    $stmtP->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
    $stmtP->execute();
    $points = $stmtP->fetchAll(PDO::FETCH_OBJ);
    foreach($points as $x){
        echo '<div class="form-inline mb-3" method="post" action="">
        <input class="form-control mr-sm-1" type="text" id="nom'.$x->id.'" name="nom" value="'.$x->nom.'">
        <input class="form-control mr-sm-1" type="text" id="adresse'.$x->id.'" name="adresse" value="'.$x->adresse.'">
        <input class="form-control mr-sm-1" type="text" id="codePostal'.$x->id.'" name="codePostal" value="'.$x->code_postal.'">
        <input class="form-control mr-sm-1" type="text" id="ville'.$x->id.'" name="ville" value="'.$x->ville.'">
        <input class="form-control mr-sm-1" type="text" placeholder="infos" id="infos'.$x->id.'" name="infos" value="'.$x->infos.'">
        <input class="form-control mr-sm-1" style="max-width:60px;" type="text" id="exemplaires'.$x->id.'" name="exemplaires" value="'.$x->exemplaires.'">
        <input class="form-control mr-sm-1" type="text" id="categorie'.$x->id.'" name="categorie" value="'.$x->categorie.'">
        <input type="hidden" id="id'.$x->id.'" name="id" value="'.$x->id.'">
    
        <button class="btn btn-primary mr-2 btn-sm" onclick="updateOne('.$x->id.')" style="width:100px">Modifier</button>
      
        <input class="btn btn-danger btn-sm" type="submit" name="delete" value="Supprimer">
        <div id="done'.$x->id.'"></div>
        </div>';
        }
    }
    ?>
    </div>
    <br>

    <script>
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