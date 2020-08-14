<head>
    <title>reset</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="4;URL=monitoring.php" />
</head>

<?php
include_once 'Database.php'; 

session_start();
if($_SESSION['user']->accreditation < 1 && $_SESSION['user']->accreditation > 2){
    header("Location:index.php");
};

//On recupere les données de la tournée affectée à cet utlisateur
$tournee = $_POST['tournee_info'];

$database = new Database();
$connexion = $database->getConnection();    
$stmt = $connexion->prepare("UPDATE points SET last_update=NULL, heure=NULL, distribués=0, motif=NULL WHERE tournees=?");
$stmt->bindParam(1, $tournee);
if($stmt->execute()){
    echo 'les points de la tournée ont été réinitialisés';
}else{
    print_r($stmt->errorInfo());
}

$user = $_POST['user_info'];
$stmtR = $connexion->prepare("UPDATE user SET tournees=NULL WHERE id=?");
$stmtR->bindParam(1, $user);
if($stmtR->execute()){
    echo "<br>La tournée n'est plus assignée";
}else{
    print_r($stmt->errorInfo());
}

if(isset($_POST['user2_info'])){
    $user2 = $_POST['user2_info'];
    $stmtR2 = $connexion->prepare("UPDATE user SET tournees=NULL WHERE id=?");
    $stmtR2->bindParam(1, $user2);
    $stmtR2->execute();
}

?>