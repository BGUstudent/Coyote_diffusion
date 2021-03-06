<?php
    if (!isset($_SESSION['user'])){
        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
        ini_set('session.gc-maxlifetime', 60 * 60 * 24 * 365);
        session_start();
    };
	if($_SESSION['user']->accreditation!=2){
		header("Location:index.php");
	};
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://bootswatch.com/4/united/bootstrap.min.css" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="icon" type="img/logo_square.png" href="img/logo_square_30.png">
    <style>
        @media only screen and (max-width: 1000px) {
            .encart{
                max-width: 161px;
                text-align: center;
            }
            .grab{
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
        <a class="navbar-brand" href="admin.php"><img src="img/COYOTE_LOGO_120.png" alt="logo_coyote" style="max-width: 100px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user.php">Ma tournée</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Assigner une tournée</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="tournees.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Gérer les tournées</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="tournees.php">Modifier les points de distributions</a>
                        <a class="dropdown-item" href="add_tournee.php">Ajouter/Modifier/Supprimer une tournée</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_clients.php">Gérer les clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Gérer les utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="monitoring.php">Monitoring</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="tournees.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Data</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="nav-link" href="stat.php">Statistiques</a>
                        <a class="nav-link" href="read_reporting.php">Consulter les rapports de distribution</a>
                        <a class="nav-link" href="report_client.php">Rapports clients</a>
                    </div>
                </li>
            </ul>
            <ul class="encart navbar-nav border border-secondary rounded">
                <li class="nav-item">
                    <a class="nav-link" href=""><b><?php echo "Bonjour "; echo $_SESSION['user']->prenom; ?></b></a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="h-100 btn btn-danger"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
</body>