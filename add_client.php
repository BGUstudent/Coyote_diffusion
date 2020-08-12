<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=manage_clients.php"/>
</head>

<body>
<?php
$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("INSERT INTO clients(nom_client) 
VALUES (?) ");
$stmt->bindParam(1, $_POST['nom_client']);
$stmt->execute();
echo $_POST['nom_client'].' a été ajouté';
?>
</body>