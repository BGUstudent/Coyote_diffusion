<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>utilisateurs</title>
</head>
<body>
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
        '<form action="delete_user.php" method="POST">
        <input type="hidden" name="id" value="'.$user->id.'">
        <input type="submit" name="submitD'.$user->id.'" value="Supprimer"></form><br>';
    }
?>
<!-- formulaire d'ajout -->
<br>Ajouter un utilisateur<br>
    <form method="post" action="add_user.php">
        <input type="text" id="prenom" name="prenom" placeholder="Prénom">
        <input type="text" id="nom" name="nom" placeholder="Nom">
        <input type="email" id="email" name="email" placeholder="Email">
        <input type="password" id="pass" name="pass" placeholder="Mot de passe">
        <select id="accred" name="accred">
            <option value="1">User</option>
            <option value="2">Admin</option>
        </select>
        <select id="permis" name="permis">
            <option value="Oui">Oui</option>
            <option value="Non">Non</option>
            <option value="Etranger">Etranger</option>
        </select>
        <input type="submit" name="add_user" value="Ajouter">
    </form>
    <a href="admin.php">Retour</a>
</body>
</html>