<html lang="fr">
	<head>
		<title>logout</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta http-equiv="refresh" content="3;URL=index.php" />
	</head>
	
	<body>
		<div>
            <?php
                session_start();
                session_destroy();
                echo 'Vous êtes bien deconnectés, vous allez être redirigés.';
            ?>