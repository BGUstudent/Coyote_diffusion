<?php include_once 'Database.php'; ?>

<?php

// get Json
$data = json_decode(file_get_contents("php://input"));
 
try{
    $database = new Database();
    $connexion = $database->getConnection();
    $ordre=0;
    foreach($data as $n){
        $ordre++;
        $stmt = $connexion->prepare("UPDATE points SET ordre=? WHERE id=?");
        $stmt->bindParam(1, $ordre);
        $stmt->bindParam(2, $n); 
        if($stmt->execute()) {
            // set response code - 200 ok
        http_response_code(200);
        }else{
            // set response code - 503 service unavailable
        http_response_code(503);
        }
    }
}
catch (PDOException $error){
    echo $error->getMessage();
}
?>