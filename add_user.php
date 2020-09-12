<?php include_once 'Database.php'; 

if (!isset($_SESSION)){
	session_start();
};
if($_SESSION['user']->accreditation!=2){
	header("Location:index.php");
};

$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("INSERT INTO user(prenom, nom, numero, password, accreditation, permis) VALUES (?, ?, ?, ?, ?, ?) ");
$stmt->bindParam(1, $_POST['prenom']);
$stmt->bindParam(2, $_POST['nom']);
$stmt->bindParam(3, $_POST['tel']);
$stmt->bindParam(4, $_POST['nom']);
$stmt->bindParam(5, $_POST['accred']);
$stmt->bindParam(6, $_POST['permis']);
$stmt->execute();
header("Location:manage_users.php");
?>
