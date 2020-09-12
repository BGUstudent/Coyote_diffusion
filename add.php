<?php include_once 'Database.php';

if (!isset($_SESSION)){
	session_start();
};
if($_SESSION['user']->accreditation!=2){
	header("Location:index.php");
};

$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("INSERT INTO points(nom, adresse, code_postal, ville, tournees, infos, exemplaires, categorie) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
$stmt->bindParam(1, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['nomP']))))));
$stmt->bindParam(2, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['adresseP']))))));
$stmt->bindParam(3, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['codePostal']))))));
$stmt->bindParam(4, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['villeP']))))));
$stmt->bindParam(5, $_POST['tournee_id']);
$stmt->bindParam(6, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['infos']))))));
$stmt->bindParam(7, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['exemplaires']))))));
$stmt->bindParam(8, strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($_POST['categorie']))))));
$stmt->execute();
header('Location: ' . $_SERVER['HTTP_REFERER']);?>
