<?php include_once 'Database.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>up</title>
</head>
<body>

<?php
$tournee = $_POST['tournee_id'];
echo 'Tournée n°'.$tournee.'<br>';
  // chemin serveur :
  // $target_dir = "/home/postfutur/www/corpscan/uploads/"; //où va le fichier?
  // chemin local : 
  $target_dir = "/var/www/html/Ben/uploads/"; //où va le fichier?

  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); //définir son nom complet (avec chemin d'accès)
  // Check if file already exists
  if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
  } else {    
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { // on le move
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }}
  //@ Warning: fgetcsv() expects parameter 1 to be resource, string given.
  @fgetcsv($target_file, 10000, ","); //on récupère le fichier (max 10000 caractère ',' est le séparateur)
  $h = fopen($target_file, "r"); //on l'ouvre (r = reading)
  $data = fgetcsv($h, 10000, ","); //on demande de lire une ligne

  if (($h = fopen($target_file, "r")) !== FALSE){
    while (($data = fgetcsv($h, 10000, ",")) !== FALSE)  // boucle pour répêter l'opération sur chaque ligne
    {
      $CSVrows[]=$data; // Each individual array is being pushed into the nested array
    }
  fclose($h); // fucking close the file !
  }

  $database = new Database();
  $connexion = $database->getConnection();
  $stmt = $connexion->prepare("INSERT INTO points (categorie, infos, nom, adresse, code_postal, ville, exemplaires, tournees) VALUES (?, ?, ?, ?, ?, ?, ?, $tournee)");

  foreach($CSVrows as $CSVrow)
  {
    $stmt->execute($CSVrow);
  }
    echo "<br> points de livraison ajoutés";
?>

</body>
</html>