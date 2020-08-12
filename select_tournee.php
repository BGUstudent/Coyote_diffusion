<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'Database.php';
 
$database = new Database();
$connexion = $database->getConnection();
 
$data = json_decode(file_get_contents("php://input"));

    $stmt = $connexion->prepare("SELECT * FROM tournees WHERE client=?");
    $stmt->bindParam(1, $data);
    if($stmt->execute()) {
        $tournees = $stmt->fetchAll(PDO::FETCH_OBJ);
        $arr_tournees=[];
        foreach($tournees as $tournee){
            $arr_tour=array(
                "id"=>$tournee->id,
                "nom"=>$tournee->nom
            );
            array_push($arr_tournees, $arr_tour);
        }
        http_response_code(200);
        echo json_encode($arr_tournees);
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "pas ok"));
    }
?>