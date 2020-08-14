<?php include_once 'Database.php'; ?>

<!DOCTYPE html>

<?php
	session_start();
	if($_SESSION['user']->accreditation < 1 && $_SESSION['user']->accreditation > 2){
		header("Location:index.php");
    };
    
    if($_SESSION['user']->accreditation == 2){
        include 'header_admin.php';
        echo '<br>';
    }

    if($_SESSION['user']->accreditation == 1){
        echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>';
    }
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user sheet</title>
</head>
<body>
<div class="container">

    <?php
    echo "<h2>Bienvenue "; echo $_SESSION['user']->prenom;
    if($_SESSION['user']->accreditation == 1){
        echo '<br><a href="logout.php" class="h-100 btn btn-info"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>';
    }
    echo "</h2><br>Voici les points à livrer :<br><br>";
    $tournee = $_SESSION['user']->tournees;
    //On affiche les points de distributions attribués
    $database = new Database();
    $connexion = $database->getConnection();    
    $stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND exemplaires > 0");
    $stmt->bindParam(':tournees', $tournee);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo '<ul class="list-group">'; //Start list
    foreach( $result as $row ) {
        echo "<li class='list-group-item' id='li".$row->id."' ".(($row->last_update)?'style="background-color: #abfaba;"':"")//green if done
        ."><button class='btn btn-light btn-sm float-right' onclick='copy(".$row->id.")'>Copier l'adresse</button><span id='point".$row->id."'><b>"
        . $row->nom . "</b> <br>" 
        . $row->adresse . " " 
        . $row->code_postal . " " 
        . $row->ville . " </span><br>" 
        . $row->exemplaires. " exemplaires <button class='btn btn-primary float-right' data-toggle='modal' data-target='#exampleModal".$row->id."'>Terminé</button>";// onclick='message(".$row->exemplaires.",".$row->id.")'
        if ($row->infos != ""){
            echo "<button class='btn btn-link' onclick='expand(".$row->id.")'>+ d'infos</button>
            <div style='display:none' id='infos".$row->id."'>".$row->infos."</div>";
        }
        // Menu modal
        echo'
        <div class="modal fade" id="exampleModal'.$row->id.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">'. $row->nom .'</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <select class="custom-select" id="etat'.$row->id.'" onchange="check(this,'.$row->id.');" required>
                            <option value="livré" selected>Livré</option>
                            <option value="refus">Refusé</option>
                            <option value="indisponible">Indisponible</option>
                            <option value="fermeture définitive">Fermé définitivement</option>
                        </select>
                         
                        <div class="form-group" id="ifLivré'.$row->id.'" style="display: block;">
                            <label for="exemplaires">Nombre d\'exemplaires distribués</label>
                            <input type="number" class="form-control" id="exemplaires'.$row->id.'" value="'.$row->exemplaires.'">
                        </div>

                        <div class="form-group">
                            <label for="commentairesSupp">Un commentaire?</label>
                            <input type="text" class="form-control" id="commentairesSupp'.$row->id.'" placeholder:"facultatif">
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button onclick="validate('.$row->id.')" type="submit" class="btn btn-primary">Valider</button>
                </div>
                </div>
            </div>
        </div>';
        echo'</li><br>';
    }
    echo '</ul">';
    ?>
</div>

<!-- Script pour toggle/hide les infos supp -->
    <script>
    function expand(id) {
        var x = document.getElementById("infos"+id);
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

// Script pour copier
    function copy(i) {
        var element = document.getElementById("point"+i); //select the span
        var elementText = element.textContent; //get the text content from the span
        navigator.clipboard.writeText(elementText); //use the Clipboard API writeText method
    }

//script pour faire apparaitre l'input nombre d'exemplaire
    function check(that, id) {
        if (that.value == "livré") {
            document.getElementById("ifLivré"+id).style.display = "block";
        } else {
            document.getElementById("ifLivré"+id).style.display = "none";
        }
    }

// script de validation fenetre modale
    function validate(y){
        var url = "report_one.php"; // service url
        
        var data = {}; 
        if ((document.getElementById("etat"+y).value) === "livré") {
            data.distribués = (document.getElementById("exemplaires"+y).value);
        }else{
            data.distribués = 0;
        }
        data.motif = (document.getElementById("etat"+y).value);
        data.commentaires = (document.getElementById("commentairesSupp"+y).value);
        data.id = y;
        var data = JSON.stringify(data);

        fetch(url, {
            method : 'PUT',
            body: data
        })
        .then(function(response){
            return response.json();
        })
        .then(function(data){
            alert(`${JSON.stringify(data.message, null, 4)}`);
        })
        .catch((error) => console.log(error));
        document.getElementById("li"+y).style.backgroundColor = "#abfaba";//Green background if validated
        $('#exampleModal'+y).modal('hide'); //close modal
    }
    </script>
</body>
</html>