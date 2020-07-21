<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <!-- Login Form -->
	<div class="form">
		<form method="post" action="index.php">
			<fieldset>
				<legend style="font-weight:bold">login</legend>
				<!-- Username -->
				<div>
					<label for="email">adresse mail</label>
					<input placeholder="entrez votre adresse mail"type="text" name="email" id="email" required>
				</div>
				<!-- Password -->
				<div>
					<label for="password">mot de passe</label>
					<input placeholder="Saisir votre mot de passe"type="password" name="pass" id="pass" required>
				</div>
				<!-- Submit -->
				<div>
					<input type="submit" value="valider"name="submit">
				</div>
			</fieldset>
		</form>
	</div>
	</br>

<?php
	session_start();// on démarre une session

	if (isset($_POST['submit'])) {	// on vérifie que les inputs soient remplis
		$email = $_POST['email']; //on récup l'input email et on l'affecte
		$email = sanitize($email); //on nettoie pour éviter les erreurs et les injections (fonction en bas)
		$pass = $_POST['pass']; //on récup l'input password et on l'affecte
		$pass = sanitize($pass); //on nettoie pour éviter les erreurs et les injections (fonction en bas)
		// $pass = md5($_POST['pass']); // on crypte
		$database = new Database();
		$connexion = $database->getConnection();
		// Requete qui selectionne les utilisateurs correspondant aux inputs
		$stmt = $connexion->prepare("SELECT * FROM user WHERE email=:email && password=:pass");
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':pass', $pass);
		$stmt->execute();
		$_SESSION['user'] = $stmt->fetch(PDO::FETCH_OBJ);
		
		$accreditation = $_SESSION['user']->accreditation;

		// On compte le nombre de ligne qui correspondent dans la BDD (normalement 1 seul)
		$say = $stmt->rowCount();
		if( $say > 0 ){
			// renvoi à différents menus selon accreditation
			if ($accreditation == 1){
				header('location:user.php');
			}elseif ($accreditation == 2){
				header('location:admin.php');
			}  
		}else{
			echo "C'est mort";
		}    
	}

/* Function sanitize */
	function sanitize($input){
		$input = strip_tags(stripcslashes(htmlentities(trim($input))));
		$input = str_replace("'","",$input);
		return $input;
	}
?>
</body>
</html>