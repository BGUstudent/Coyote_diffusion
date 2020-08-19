<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=manage_users.php"/>
</head>

<body>
<?php
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
echo $_POST['prenom'].' '.$_POST['nom'].' a été ajouté';
?>
</body>