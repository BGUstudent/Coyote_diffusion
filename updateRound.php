<?php include_once 'Database.php';
include 'header_admin.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update round</title>
</head>
<body>
<div class="container">
<?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM rounds WHERE id=?");
    $stmt->bindParam(1, $_POST['id']); 
    $stmt->execute(); 
    $round = $stmt->fetch(PDO::FETCH_OBJ);   
?>
<!-- formulaire de modification -->
<h4>Modifier la tournée</h4>
<form class="form-inline" method="post" action="">
    <input type="hidden" name="id" value="<?php echo $round->id ?>">
    <input type="text" class="form-control mr-sm-1" id="nom" name="nom" value="<?php echo $round->nom ?>" required>
    <select class="custom-select custom-select-sm w-25" id="equipe" name="equipe">
        <option value="Solo"<?php echo($round->equipe==="Solo")  ? 'selected' : '' ?>>Solo</option>
        <option value="Binôme"<?php echo($round->equipe==="Binôme")  ? 'selected' : '' ?>>Binôme</option>
    </select>
    <input type="submit" class="btn btn-primary" name="submit" value="Modifier">
</form>

<?php
if(isset($_POST['submit'])){
    $stmt = $connexion->prepare("UPDATE rounds SET nom=?, equipe=? WHERE id=?");
    $stmt->bindParam(1, $_POST['nom']);
    $stmt->bindParam(2, $_POST['equipe']);
    $stmt->bindParam(3, $_POST['id']); 
    $stmt->execute();
    echo("<script>location.href = 'add_tournee.php';</script>");
}
?>
</body>