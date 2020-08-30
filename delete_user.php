<?php include_once 'Database.php';

if (!isset($_SESSION)){
	session_start();
};
if($_SESSION['user']->accreditation!=2){
	header("Location:index.php");
};

$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("DELETE FROM user WHERE id={$_POST['id']}");
$stmt->execute();
header("Location:manage_users.php");
?>