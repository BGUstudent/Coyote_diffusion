<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update point</title>
</head>

<body>
    <?php
    $id = $_POST['id'];
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM points WHERE id={$_POST['id']}");
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_OBJ);

    echo '<form method="post" action="updateOne.php">
    <input type="text" id="nomU" name="nomU" value="'.$info->nom.'">
    <input type="text" id="adresseU" name="adresseU" value="'.$info->adresse.'">
    <input type="text" id="codePostal" name="codePostal" value="'.$info->code_postal.'">
    <input type="text" id="villeU" name="villeU" value="'.$info->ville.'">
    <input type="hidden" name="idU" value="'.$id.'">
    <input type="submit" name="update" value="modifier">
    </form>';
    ?>

</body>
</html>

