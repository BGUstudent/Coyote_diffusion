<?php include_once 'Database.php'; ?>

<?php

// get Json
$data = json_decode(file_get_contents("php://input"));
 
try{
    $database = new Database();
    $connexion = $database->getConnection();
    $stmt = $connexion->prepare("DELETE FROM points WHERE id=$data");
    if($stmt->execute()) {
        // set response code - 200 ok
    http_response_code(200);
        // tell the user
    echo json_encode(array("message" => "Le point a été supprimé"));
    }

    else{
        // set response code - 503 service unavailable
    http_response_code(503);
        // tell the user
    echo json_encode(array("message" => "échec de la suppression"));
    }
}
catch (PDOException $error){
    echo $error->getMessage();
}

?>