<?php include_once 'Database.php'; ?>

<!DOCTYPE html>

<?php
	session_start();
	if($_SESSION['user']->accreditation!=2){
		header("Location:index.php");
	};
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin board</title>
</head>
<body>
    <?php echo "Bienvenue "; echo $_SESSION['user']->prenom; ?>
    <br><br>
    <a href="user.php">Ma tournée</a>
    <br><br>
    <a href="manage_users.php">Gérer les utilisateurs</a>
    <br>
    <a href="tournees.php">Gérer les tournées</a>
    <br>
    <h2>Assigner une tournée</h2>
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT * FROM user");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $stmtT = $connexion->prepare("SELECT * FROM tournees");
    $stmtT->execute();
    $tournees = $stmtT->fetchAll(PDO::FETCH_OBJ);
    
    foreach( $result as $row ) {
        echo $row->prenom . " " . $row->nom . ", permis : " . $row->permis;
        if ($row->tournees){echo ",  tournée assignée ".$tournees[$row->tournees-1]->nom;} //on affiche la tournée actuellement assignée s'il y a 
        echo '<form id="form'.$row->id.'" method="post" action="admin.php">
        Assigner une nouvelle tournée :
        <select name="tournees" id="tournees-select">
        <option value="null">'.'aucune'.'</option>';
        foreach($tournees as $x){
            echo '<option value="'.$x->id.'">'.$x->nom. " - ".$x->equipe.'</option>';
        }
        echo '</select>
        <input type="submit" value="valider" name="submit'.$row->id.'"></input>
        </form><br>';
        if (isset($_POST['submit'.$row->id])) {
            $stmtUpdate = $connexion->prepare("UPDATE user SET tournees = ? WHERE id = ?");
            if($_POST['tournees'] == "null"){
                $stmtUpdate->bindValue(1, null, PDO::PARAM_INT);
            }else{
                $stmtUpdate->bindValue(1, $_POST['tournees'], PDO::PARAM_INT);
            }
            $stmtUpdate->bindValue(2, $row->id, PDO::PARAM_INT);
            $stmtUpdate->execute();
            header('location:admin.php');
        }
    }
    ?>
    <div class="logout">
        <form method="post" action="logout.php">
            <input type="submit" name="logout" value="Se déconnecter" action="logout.php" id="logout">
        </form>
    </div>
    </body>
</html>