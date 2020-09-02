<?php
include_once 'Database.php';

$data = json_decode(file_get_contents("php://input"));

//On affiche les points de distributions attribués
$database = new Database();
$connexion = $database->getConnection();

$stmtU = $connexion->prepare("SELECT * FROM user WHERE tournees=:tournees");
$stmtU->bindParam(':tournees', $data);
$stmtU->execute();
$users = $stmtU->fetchAll(PDO::FETCH_OBJ);

$stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND exemplaires > 0 ORDER BY ordre ASC");
$stmt->bindParam(':tournees', $data);

if($stmt->execute()) {
    $points = $stmt->fetchAll(PDO::FETCH_OBJ);
    $arr_points=[];
    foreach($points as $point){
        $arr_point=array(
            "id"=>$point->id,
            "nom"=>$point->nom,
            "adresse"=>$point->adresse,
            "code_postal"=>$point->code_postal,
            "ville"=>$point->ville,
            "exemplaires"=>$point->exemplaires,
            "last_update"=>$point->last_update,
            "heure"=>$point->heure,
            "motif"=>$point->motif,
            "distribués"=>$point->distribués,
            "commentaires"=>$point->commentaires,
            "users"=>$users
        );
        array_push($arr_points, $arr_point);
    }

    http_response_code(200);
    echo json_encode($arr_points);
}else{
    http_response_code(503);
    echo json_encode(array("message" => "pas ok"));
}

?>

