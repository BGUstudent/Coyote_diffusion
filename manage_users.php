<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>utilisateurs</title>
</head>
<body>
    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>
    <div class="container">

    <h4>Liste des utilisateurs enregistrés</h4><br>
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT u.id, u.prenom, u.nom, u.email, u.accreditation, u.permis, t.nom as tt FROM user as u LEFT JOIN tournees AS t ON (u.tournees=t.id)");
    $stmt->execute(); 
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);   

    foreach($users as $user){
        $user->accreditation == 2 ? $accred='Admin' : $accred='User'; 
        echo $user->prenom.' '.$user->nom. ", permis : " . $user->permis.', '.$accred.', '.$user->email. ', tournée actuelle : '.$user->tt.
        '<form class="form" action="delete_user.php" method="POST">
        <input type="hidden" name="id" value="'.$user->id.'">
        <input class="btn btn-danger btn-sm" type="submit" name="submitD'.$user->id.'" value="Supprimer"></form><br>';
    }
?>

<!-- formulaire d'ajout -->
<br><h5>Ajouter un utilisateur</h5>
    <form class="form" style="width: 400px;" method="post" action="add_user.php">
        <input class="form-control" type="text" id="prenom" name="prenom" placeholder="Prénom" required>
        <input class="form-control" type="text" id="nom" name="nom" placeholder="Nom" required>
        <input class="form-control" type="text" id="tel" name="tel" placeholder="numéro de téléphone">
        <div class="text-right">
            <label for="accred">Niveau d'accreditation</label>
            <select class="custom-select custom-select-sm w-25" id="accred" name="accred">
                <option value="1">User</option>
                <option value="2">Admin</option>
            </select>
        </div>
        <div class="text-right">
            <label for="accred">Permis de conduire</label>
            <select class="custom-select custom-select-sm w-25" id="permis" name="permis">
                <option value="Oui">Oui</option>
                <option value="Non">Non</option>
                <option value="Etranger">Etranger</option>
            </select>
        </div>
        <input type="submit" class="float-right btn btn-primary" name="add_user" value="Ajouter">
    </form>
</div>
</body>
</html>