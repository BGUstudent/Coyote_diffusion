<?php include_once 'Database.php'; ?>

<!DOCTYPE html>

<?php
	session_start();
	if($_SESSION['user']->accreditation < 1 && $_SESSION['user']->accreditation > 2){
		header("Location:index.php");
	};
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user sheet</title>
</head>
<body>
    <?php
    echo "<h2>Bienvenue "; echo $_SESSION['user']->prenom; echo "</h2><br>Voici les points à livrer :<br><br>";
    $tournee = $_SESSION['user']->tournees;
    //On affiche les points de distributions attribués
    $database = new Database();
    $connexion = $database->getConnection();    $stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND paquets > 0");
    $stmt->bindParam(':tournees', $tournee);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach( $result as $row ) {
        echo "<span id='point".$row->id."'>"
        . $row->nom . ", " 
        . $row->adresse . " " 
        . $row->code_postal . " " 
        . $row->ville . "</span><button onclick='copy(".$row->id.")'>copier</button> | " 
        .$row->paquets. " paquets";
        if ($row->infos != ""){
            echo "<button onclick='expand(".$row->id.")'>+ d'infos</button>
            <div style='display:none' id='infos".$row->id."'>".$row->infos."</div>";
        }
        echo'<br>';
    }
    ?>
    <br>
    <div class="logout">
        <form method="post" action="logout.php">
            <input type="submit" name="logout" value="Se déconnecter" action="logout.php" id="logout">
        </form>
    </div>
    <?php
	if($_SESSION['user']->accreditation == 2){
       echo '<br><a href="admin.php">Retourner au menu Admin</a>';
	};
    ?>

<!-- Script pour toggle/hide les infos supp -->
    <script>
    function expand(id) {
        var x = document.getElementById("infos"+id);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function copy(i) {
        var element = document.getElementById("point"+i); //select the span
        var elementText = element.textContent; //get the text content from the span
        navigator.clipboard.writeText(elementText); //use the Clipboard API writeText method
    }
    </script>
</body>
</html>