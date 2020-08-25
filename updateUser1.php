<?php
include_once 'Database.php';

$database = new Database();
$connexion = $database->getConnection();

// Si c'est un user alors nom devient mdp


if(isset($_POST['submitOne'])){
    if(isset($_POST['numero'])){
        $stmt = $connexion->prepare("UPDATE user SET prenom=?, nom=?, numero=?, permis=?, password=? WHERE id=?");
        $stmt->bindParam(1, $_POST['prenom']);
        $stmt->bindParam(2, $_POST['nom']);
        $stmt->bindParam(3, $_POST['numero']); 
        $stmt->bindParam(4, $_POST['permis']); 
        $stmt->bindParam(5, $_POST['nom']); 
        $stmt->bindParam(6, $_POST['id']);
    }else{
        $stmt = $connexion->prepare("UPDATE user SET prenom=?, nom=?, email=?, permis=? WHERE id=?");
        $stmt->bindParam(1, $_POST['prenom']);
        $stmt->bindParam(2, $_POST['nom']);
        $stmt->bindParam(3, $_POST['email']);
        $stmt->bindParam(4, $_POST['permis']); 
        $stmt->bindParam(5, $_POST['id']);
    }
    $stmt->execute();
    header('location:manage_users.php');
}
?>