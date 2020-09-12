<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'Database.php';
 
$database = new Database();
$connexion = $database->getConnection();
 
$data = json_decode(file_get_contents("php://input"));
date_default_timezone_set('Europe/Paris');
    $laDate=date('Y-m-d H:i:s');
    $heure=date('H:i:s');
    $stmt = $connexion->prepare("UPDATE points SET last_update=?, heure=?, distribués=?, motif=?, commentaires=? WHERE id=?");
    $stmt->bindParam(1, $laDate);
    $stmt->bindParam(2, $heure);
    $stmt->bindParam(3, $data->distribués);
    $stmt->bindParam(4, $data->motif);
    $stmt->bindParam(5, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($data->commentaires)))))); //Sanitize input
    $stmt->bindParam(6, $data->id);
    if($stmt->execute()) {
        http_response_code(200);
    }
    else{
        http_response_code(503);
    }
?>