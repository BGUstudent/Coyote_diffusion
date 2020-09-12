<?php include_once 'Database.php'; 
	ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
	ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 365);
	session_start(); // on démarre une session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css" crossorigin="anonymous">
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<link rel="icon" type="img/logo_square.png" href="img/logo_square_30.png">
	<title>Coyote distribution login</title>
</head>
<body>
	<img src="img/COYOTE_LOGO_315.png" style="max-width: 310px;" class="img-fluid rounded mx-auto d-block mt-3" alt="logo_coyote">
    <!-- Login Form -->
	<div class="form mx-auto mt-4" style="width: 310px;">
		<form method="post" action="index.php">
			<fieldset>
				<legend style="font-weight:bold">Login</legend>
				<!-- Username -->
				<div class="form-group">
					<label for="email">Adresse mail ou numéro</label>
					<input class="form-control" placeholder="Entrez votre email ou votre numéro"type="text" name="email" id="email" required>
				</div>
				<!-- Password -->
				<div class="form-group">
					<label for="password">Mot de passe</label>
					<input class="form-control" placeholder="Saisir votre mot de passe"type="password" name="pass" id="pass" required>
				</div>
				<!-- Submit -->
				<div>
					<input type="submit" class="btn btn-primary" value="valider" name="submit">
				</div>
			</fieldset>
		</form>
	</div>
	</br>

<?php
	if (isset($_POST['submit'])) {	// on vérifie que les inputs soient remplis
		$email = $_POST['email']; //on récup l'input email et on l'affecte
		$email = sanitize($email); //on nettoie pour éviter les erreurs et les injections (fonction en bas)
		$pass = $_POST['pass']; //on récup l'input password et on l'affecte
		$pass = sanitize($pass); //on nettoie pour éviter les erreurs et les injections (fonction en bas)
		// $pass = md5($_POST['pass']); // on crypte
		$database = new Database();
		$connexion = $database->getConnection();
		// Requete qui selectionne les utilisateurs correspondant aux inputs
		$stmt = $connexion->prepare("SELECT * FROM user WHERE email=:email && password=:pass OR numero=:email && password=:pass");
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
				echo '<script>window.location.replace("user.php")</script>';
			}elseif ($accreditation == 2){
				echo '<script>window.location.replace("admin.php")</script>';
			}  
			echo $_SESSION['user']->accreditation;
		}else{
			echo "C'est mort";
		}    
	}

/* Function sanitize */
	function sanitize($input){
		$input = strip_tags(stripcslashes(htmlentities(htmlspecialchars(trim($input)))));
		$input = str_replace("'","",$input);
		return $input;
	}
?>

</body>
</html>