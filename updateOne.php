<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="5;URL=tournees.php"/>
</head>

<body>
<?php
if(isset($_POST['update'])){ 
    $database = new Database();
    $connexion = $database->getConnection();
    $stmtA = $connexion->prepare("UPDATE points SET nom=?, adresse=?, code_postal=?, ville=? WHERE id=?");
    $stmtA->bindParam(1, $_POST['nomU']);
    $stmtA->bindParam(2, $_POST['adresseU']);
    $stmtA->bindParam(3, $_POST['codePostal']);
    $stmtA->bindParam(4, $_POST['villeU']); 
    $stmtA->bindParam(5, $_POST['idU']); 
    $stmtA->execute(); 
    echo "modifié !";
}
?>
</body>
