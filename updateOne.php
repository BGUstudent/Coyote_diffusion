<?php include_once 'Database.php'; ?>

<?php

// get Json
$data = json_decode(file_get_contents("php://input"));
 
try{
    $database = new Database();
    $connexion = $database->getConnection();
    $stmtA = $connexion->prepare("UPDATE points SET nom=?, adresse=?, code_postal=?, ville=?, infos=?, exemplaires=?, categorie=? WHERE id=?");
    $stmtA->bindParam(1, $data->nom);
    $stmtA->bindParam(2, $data->adresse);
    $stmtA->bindParam(3, $data->codePostal);
    $stmtA->bindParam(4, $data->ville); 
    $stmtA->bindParam(5, $data->infos); 
    $stmtA->bindParam(6, $data->exemplaires);
    $stmtA->bindParam(7, $data->categorie); 
    $stmtA->bindParam(8, $data->id); 
    if($stmtA->execute()) {
        // set response code - 200 ok
    http_response_code(200);
        // tell the user
    echo json_encode(array("message" => "Le point a été modifié"));
    }

    else{
        // set response code - 503 service unavailable
    http_response_code(503);
        // tell the user
    echo json_encode(array("message" => "échec de la modification"));
    }
}
catch (PDOException $error){
    echo $error->getMessage();
}


?>
