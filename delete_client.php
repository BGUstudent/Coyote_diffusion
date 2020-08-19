<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=manage_clients.php"/>
</head>

<body>
    
<?php
$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("DELETE FROM clients WHERE nom_client='{$_POST['nom_client']}'");
$stmt->execute();
echo "Le client ".$_POST['nom_client']." a été supprimé";
?>

</body>