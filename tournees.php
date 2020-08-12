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

    <!-- Selection de la tournée -->
    <form method="post" action="tournees.php">
        Selectionner une tournée :
        <select name="tournees" id="tournees-select">
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
        <input type="submit" value="valider" name="submitT"></input>
    </form>
    <br>
        <?php
        if(isset($_POST['submitT'])){ 
            $stmtA = $connexion->prepare("SELECT nom FROM tournees WHERE id=?");
            $stmtA->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
            $stmtA->execute(); 
            $nom_tournee = $stmtA->fetch(PDO::FETCH_OBJ);   
        ?>

<!-- formulaire d'ajout -->
    <form method="post" action="add.php">
        Ajouter un point de livraison à la tournée <?php echo $nom_tournee->nom ?> :<br>
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
    <div>
        Importer un fichier .csv:
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <label for="file">Selection le fichier .csv à importer</label>
            <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
            <input type="file" id="file" name="fileToUpload" accept=".csv">
            <input type="hidden" name="tournee_id" value="<?php echo $_POST['tournees'] ?>">
            <input type="submit" name='submit'></input>
        </form>
    </div><br>

<!-- Afficher les points d'une tournée -->
    <div id = points>
<?php
    $stmtP = $connexion->prepare("SELECT * FROM points WHERE tournees = ? ");
    $stmtP->bindValue(1, $_POST['tournees'], PDO::PARAM_STR);
    $stmtP->execute();
    $points = $stmtP->fetchAll(PDO::FETCH_OBJ);
    foreach($points as $x){
        echo $x->nom.', '
        .$x->adresse.' '
        .$x->code_postal.' '
        .$x->ville.' | '
        .$x->exemplaires.' exemplaires. <i>'
        .$x->infos
        .'</i><form action="update.php" method="POST">
        <input type="hidden" name="id" value="'.$x->id.'">
        <input type="submit" name="submitU'.$x->id.'" value="Modifier"></form>
        
        <form action="delete.php" method="POST">
        <input type="hidden" name="id" value="'.$x->id.'">
        <input type="submit" name="submitD'.$x->id.'" value="Supprimer"></form>';
    }
}
?>
    </div>
    <a href="add_tournee.php">Ajouter une tournée</a><br>
    <br>
    <a href="admin.php">Retour</a>
</body>
</html>