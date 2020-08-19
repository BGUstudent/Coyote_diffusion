<?php
	session_start();
	session_destroy();
	echo 'Vous êtes bien deconnectés, vous allez être redirigés.';
?>
		
<head>
	<title>logout</title>
	<meta http-equiv="refresh" content="3;URL=index.php" />
</head>