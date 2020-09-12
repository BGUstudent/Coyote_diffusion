<?php include_once 'Database.php';

    if (!isset($_SESSION['user'])){
        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
        ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 365);
        session_start();
    };	if($_SESSION['user']->accreditation < 1 && $_SESSION['user']->accreditation > 2){
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
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css" crossorigin="anonymous">
        <link rel="icon" type="img/logo_square.png" href="img/logo_square_30.png">';
    }
?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coyote distribution</title>
</head>
<body>
<div class="container">

    <?php

    //On recupere les infos de l'utilisateur
    $id = $_SESSION['user']->id;
    $database = new Database();
    $connexion = $database->getConnection();    
    $stmt = $connexion->prepare("SELECT * FROM user WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    //On récupère les détails de la tournée
    $stmt = $connexion->prepare("SELECT * FROM rounds WHERE id=?");
    $stmt->bindParam(1, $user->tournees); 
    $stmt->execute(); 
    $round = $stmt->fetch(PDO::FETCH_OBJ);   

    //On affiche les points de distributions attribués
    $stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND exemplaires > 0 ORDER BY ordre ASC");
    $stmt->bindParam(':tournees', $user->tournees);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo "<h2>Bienvenue "; echo $_SESSION['user']->prenom;
    if($_SESSION['user']->accreditation == 1){
        echo '<br><a href="logout.php" class="float-right btn btn-info"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>';
    }
    echo '</h2>
    <div class="card" style="width: 18rem;">
    <div class="card-header"><b>Détails de la tournée</b></div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Client : '.$round->client.'</li>
            <li class="list-group-item">Tournée : '.$round->nom.'</li>
            <li class="list-group-item">Nombre de point à distribuer : '.count($result).'</li>
            <li class="list-group-item">Nombre total d\'exemplaires : '.array_sum(array_column($result, 'exemplaires')).'</li>
        </ul>
    </div>

    <button class="btn btn-link" onclick="expand()">Ajouter un point de livraison</button>
    <div id="hide" style="display:none">
        <form method="post" action="add.php">
            <input type="text" id="nomP" name="nomP" placeholder="Nom du point" style="max-width:220px;" required>
            <input type="text" id="adresseP" name="adresseP" placeholder="Adresse" style="max-width:220px;" required>
            <input type="text" id="codePostal" name="codePostal" placeholder="Code postal" style="max-width:220px;" required>
            <input type="text" id="villeP" name="villeP" placeholder="Ville" style="max-width:220px;" required>
            <input type="hidden" name="tournee_id" value="'.$user->tournees.'">
            <input type="text" id="infos" name="infos" placeholder="infos" style="max-width:220px;">
            <input type="text" id="exemplaires" name="exemplaires" placeholder="nb de exemplaires" style="max-width:220px;" required>
            <input type="text" id="categorie" name="categorie" placeholder="catégorie" style="max-width:220px;" required>
            <input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter">
        </form>
    </div>

    <br>Voici les points à livrer :<br><br>
    <ul class="list-group">'; //Start list
    foreach( $result as $row ) {
        echo "<li class='list-group-item' id='li".$row->id."' ".(($row->last_update)?'style="background-color: #abfaba;"':"")//green if done
        ."><button class='btn btn-light btn-sm float-right' onclick='copy(".$row->id.")'>
        <svg style='z-index:1;' width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-clipboard' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
            <path fill-rule='evenodd' d='M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z'/>
            <path fill-rule='evenodd' d='M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z'/>
        </svg>
        </button>
        <div class='float-right mr-1' id='copied".$row->id."' style='display:none; color:#e95420'><b>Copié!</b></div>
        <span id='point".$row->id."'><b>"
        . $row->nom . "</b><br> " 
        . $row->adresse . " " 
        . $row->code_postal . " " 
        . $row->ville . " </span><br>(".$row->categorie.")<br>" 
        . $row->exemplaires. " exemplaires <button class='btn btn-primary float-right' data-toggle='modal' data-target='#exampleModal".$row->id."'>Terminé</button>";// onclick='message(".$row->exemplaires.",".$row->id.")'
        if ($row->infos != ""){
            echo "<div style='color:#ff5c11' id='infos".$row->id."'><b>".$row->infos."</b></div>";
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

<script>

// Script pour copier
    function copy(i) {
        var element = document.getElementById("point"+i); //select the span
        var elementText = element.textContent; //get the text content from the span
        navigator.clipboard.writeText(elementText); //use the Clipboard API writeText method
        $( "#copied"+i ).show(); 
        setTimeout(function() {
            $( "#copied"+i ).hide();
            }, 2000);
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

        .catch((error) => console.log(error));
        document.getElementById("li"+y).style.backgroundColor = "#abfaba";//Green background if validated
        $('#exampleModal'+y).modal('hide'); //close modal
    }

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