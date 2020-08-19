<?php
include_once 'Database.php'; 
include 'header_admin.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stat</title>
</head>
<body>

<div class="container">

<!-- Selection de la categorie -->
<h4>Selectionner une catégorie</h4>

<!-- afficher le filtre CP -->
<button name="expand" class="btn btn-link" onclick="expand()">Filtrer par code postal</button>

<form class="form" method="post" action="">
    <select class="custom-select custom-select mr-1" name="categorie" style="max-width:400px;" id="categorie-select">
    <?php
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("SELECT DISTINCT categorie FROM points");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo'<option value="">Toutes categories</option>';
    foreach($categories as $categorie){
        echo '<option value="'.$categorie->categorie.'">'.$categorie->categorie.'</option>';
    }
    echo'</select>';
    $stmtCP = $connexion->prepare("SELECT DISTINCT code_postal, ville FROM points ORDER BY code_postal ASC");
    $stmtCP->execute();
    $code_postaux = $stmtCP->fetchAll(PDO::FETCH_OBJ);
    ?>
    
    <div id="hide" style="display:none"><?php
    foreach($code_postaux as $code_postal){
        echo'<div class="form-check">
            <input class="form-check-input" name="checkbox'.$code_postal->code_postal.'" type="checkbox" value="'.$code_postal->code_postal.'">
            <label class="form-check-label" for="'.$code_postal->code_postal.'">'.$code_postal->code_postal.' '.$code_postal->ville.'</label>
        </div>';
    }
    ?>
    </div>
    <input class="btn btn-primary" type="submit" value="Rechercher" name="submit"></input>
</form>

<?php
if(isset($_POST['submit'])){
    // on rajoute des conditions si code postaux
    $addSQL="";
    $count=0;
    $parenthese=false;
    foreach($code_postaux as $code_postal){
        if(isset($_POST['checkbox'.$code_postal->code_postal])){
            $count++;
            if($_POST['categorie']){ //si une cat est selectionnée
                if($count===1){
                    $parenthese=true;
                    $addSQL.=" AND (code_postal=$code_postal->code_postal";
                }else{
                    $addSQL.=" OR code_postal=$code_postal->code_postal";
                }
            }else{ // sinon toutes catégories
                if($count===1){
                    $addSQL.=" WHERE code_postal=$code_postal->code_postal";
                }else{
                    $addSQL.=" OR code_postal=$code_postal->code_postal";
                }
            }
        }
    }
    if($parenthese){
        $addSQL.=")";
    }

    $database = new Database();
    $connexion = $database->getConnection();    
    //Si catégorie selectionnée
    if($_POST['categorie']){
        $sql="SELECT * FROM points WHERE categorie=?";
        $sql.=$addSQL;
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(1, $_POST['categorie']);
    //Si toutes catégories confondues
    }else{
        if($count===0){
            echo'<br><b>Recapitulatif:</b><br>';
            foreach($categories as $categorie){
                $sql="SELECT COUNT(*) FROM points WHERE categorie=?";
                $stmtC = $connexion->prepare($sql);
                $stmtC->bindParam(1, $categorie->categorie);
                $stmtC->execute();
                $total = $stmtC->fetchColumn();
                echo $total.' points dans la catégorie '.$categorie->categorie.'<br>';       
            }
        }
        $sql="SELECT * FROM points";
        $sql.=$addSQL;
        $stmt = $connexion->prepare($sql);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    //afficher les résultats
    // echo $sql;
    echo '<br><h5>'.count($result).' points trouvés</h5><br>
    <div id="liste">';
    foreach($result as $x){
        echo '<b>'.$x->nom.' </b>
         : '.$x->adresse.' '.$x->code_postal.', '.$x->ville.'<br>';
    }
    echo '</div>';
}
?>
</div>
<script>
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
</body>
</html>