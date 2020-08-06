<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=tournees.php"/>
</head>

<body>
<?php
$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("INSERT INTO points(nom, adresse, code_postal, ville, tournees, infos, exemplaires, categorie) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
$stmt->bindParam(1, $_POST['nomP']);
$stmt->bindParam(2, $_POST['adresseP']);
$stmt->bindParam(3, $_POST['codePostal']);
$stmt->bindParam(4, $_POST['villeP']);
$stmt->bindParam(5, $_POST['tournee_id']);
$stmt->bindParam(6, $_POST['infos']);
$stmt->bindParam(7, $_POST['exemplaires']);
$stmt->bindParam(8, $_POST['categorie']);
$stmt->execute();
echo "Le point de livraison " .$_POST['nomP']. " a été ajouté";
?>
</body>
