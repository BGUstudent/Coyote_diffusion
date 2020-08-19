<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update user</title>
</head>
<body>
    <!-- header -->
    <?php include 'header_admin.php';?>
    <br>
    <div class="container">
<body>
<?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM user WHERE id=?");
    $stmt->bindParam(1, $_POST['id']); 
    $stmt->execute(); 
    $user = $stmt->fetch(PDO::FETCH_OBJ);   
?>
<!-- formulaire de modification -->
<h4>Modifier l'utilisateur</h4>
<form class="form-inline" method="post" action="updateUser1.php">
    <input type="hidden" name="id" value="<?php echo $user->id ?>">
    <input type="text" class="form-control mr-sm-1" id="prenom" name="prenom" value="<?php echo $user->prenom ?>" required>
    <input type="text" class="form-control mr-sm-1" id="nom" name="nom" value="<?php echo $user->nom ?>" required>
    <?php
    if($user->accreditation==2){
        echo'<input type="text" class="form-control mr-sm-1" name="email" value="'. $user->email.'" required>';
    }else{
        echo'<input type="text" class="form-control mr-sm-1" name="numero" value="'.$user->numero.'" required>';
    }
    ?>
    <select class="custom-select custom-select-sm w-25" id="permis" name="permis">
        <option value="Oui"<?php echo($user->permis==="Oui")  ? 'selected' : '' ?>>Oui</option>
        <option value="Non"<?php echo($user->permis==="Non")  ? 'selected' : '' ?>>Non</option>
        <option value="Etranger"<?php echo($user->permis==="Etranger")  ? 'selected' : '' ?>>Etranger</option>
    </select>
    <input type="submit" class="btn btn-primary" name="submitOne" value="Modifier">
</form>

</body>