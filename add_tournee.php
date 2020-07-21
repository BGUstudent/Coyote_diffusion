<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout tournées</title>
</head>
<body>
    <!-- formulaire d'ajout -->
    <form method="post" action="add_tournee.php">
        Ajouter une tournée <br>
        <input type="text" id="nom" name="nom" placeholder="Nom de la tournée" required>
        <input type="text" id="client" name="client" placeholder="Nom du client" required>
        <select name="equipe" id="equipe-select">
            <option value="Solo">Solo</option>';
            <option value="Binôme">Binôme</option>';
        </select>
        <input type="submit" name="submit" value="Ajouter">
    </form>
    <?php
    if(isset($_POST['submit'])){ 
        $database = new Database();
        $connexion = $database->getConnection();
        $stmt = $connexion->prepare("INSERT INTO tournees(nom, client, equipe) VALUES (?, ?, ?) ");
        $stmt->bindParam(1, $_POST['nom']);
        $stmt->bindParam(2, $_POST['client']);
        $stmt->bindParam(3, $_POST['equipe']);
        $stmt->execute();
        echo "La tournée " .$_POST['nom']. " a été ajoutée";
    }
    ?>
    <a href="admin.php">Retour</a>
</body>
</html>