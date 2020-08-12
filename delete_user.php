<?php include_once 'Database.php'; ?>

<head>
	<meta http-equiv="refresh" content="3;URL=manage_users.php"/>
</head>

<body>
    
<?php
$database = new Database();
$connexion = $database->getConnection();
$stmt = $connexion->prepare("DELETE FROM user WHERE id={$_POST['id']}");
$stmt->execute();
echo "L'utilisateur a été supprimé";
?>

</body>

