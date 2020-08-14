<?php

$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("DELETE FROM points WHERE id={$_POST['id']}");
$stmt->execute();
echo "Le point de livraison a été supprimé";

?>