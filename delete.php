<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=tournees.php"/>
</head>

<body>
    
<?php
$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("DELETE FROM points WHERE id={$_POST['id']}");
$stmt->execute();
echo "Le point de livraison a été supprimé";
?>

</body>

