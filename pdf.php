<?php
require('fpdf/fpdf.php');
include_once 'Database.php'; 

session_start();
if($_SESSION['user']->accreditation < 1 && $_SESSION['user']->accreditation > 2){
    header("Location:index.php");
};

$tournee = $_SESSION['user']->tournees;

$database = new Database();
$connexion = $database->getConnection();    
$stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND exemplaires > 0");
$stmt->bindParam(':tournees', $tournee);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
// En-tete
$pdf->Cell(40,10, 'Reporting de distribution : ');
$pdf->Ln();
$pdf->Cell(40,10, 'Effectue le '.date('d-m-Y'));
$pdf->Ln();
$pdf->Cell(40,10, 'par '.$_SESSION['user']->prenom.' '.$_SESSION['user']->nom);
$pdf->Ln();

// loop
$pdf->SetFont('Arial','',12);
foreach( $result as $row ) {
    $pdf->Cell(40,10,utf8_decode($row->nom . ", "
    . $row->adresse . " " 
    . $row->code_postal . " " 
    . $row->ville));
    $pdf->Ln();
    $pdf->Cell(40,10, utf8_decode("nombre d'exemplaire distribués : ".$row->distribués."   (prévu : ".$row->exemplaires.")"));
    $pdf->Ln();
    $pdf->Ln();
}

$pdf->Output('F', $tournee.', '.$_SESSION['user']->prenom.', '.$_SESSION['user']->nom.' '.date('d-m-Y'));
?>