<?php include_once 'Database.php';

if (!isset($_SESSION)){
	session_start();
};
if($_SESSION['user']->accreditation!=2){
	header("Location:index.php");
};

$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("INSERT INTO clients(nom_client) VALUES (?) ");
$stmt->bindParam(1, $_POST['nom_client']);
$stmt->execute();
header("Location:manage_clients.php");
?>