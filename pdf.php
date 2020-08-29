<?php include 'header_admin.php';
 include_once 'Database.php'; 
 require('Classes/PHPExcel.php');
 // require('fpdf/fpdf.php');
?>

<head>
    <title>pdf</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="5;URL=monitoring.php" />
</head>

<?php

//On recupere les données de la tournée affectée à cet utlisateur
$tournee = $_POST['tournee'];
$user = $_POST['users'];

$database = new Database();
$connexion = $database->getConnection();    
$stmt = $connexion->prepare("SELECT * FROM points WHERE tournees=:tournees AND exemplaires > 0");
$stmt->bindParam(':tournees', $tournee);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);

$stmt2 = $connexion->prepare("SELECT * FROM rounds WHERE id=:tournees");
$stmt2->bindParam(':tournees', $tournee);
$stmt2->execute();
$result2 = $stmt2->fetch(PDO::FETCH_OBJ);

$stmtU = $connexion->prepare("SELECT * FROM user WHERE id=:id");
$stmtU->bindParam(':id', $user);
$stmtU->execute();
$user_info = $stmtU->fetch(PDO::FETCH_OBJ);

if(isset($_POST['users2'])){
    $user2 = $_POST['users2'];
    $stmtU2 = $connexion->prepare("SELECT * FROM user WHERE id=:id");
    $stmtU2->bindParam(':id', $user2);
    $stmtU2->execute();
    $user2_info = $stmtU2->fetch(PDO::FETCH_OBJ);
}

// //Rapport PDF

// $pdf = new FPDF();
// $pdf->AddPage();
// $pdf->SetFont('Arial','B',16);
// // En-tete
// $pdf->Cell(40,10, 'Reporting de distribution : ');
// $pdf->Ln();
// $pdf->Cell(40,10, 'Effectue le '.date('d-m-Y'));
// $pdf->Ln();
// $pdf->Cell(40,10, 'par '.$user_info->prenom.' '.$user_info->nom);
// if($user2_info){
//     $pdf->Cell(40,10, ' et '.$user2_info->prenom.' '.$user2_info->nom);
// }
// $pdf->Ln();

// // loop
// $pdf->SetFont('Arial','',12);
// foreach( $result as $row ) {
//     $pdf->Cell(40,10,utf8_decode($row->nom . ", "
//     . $row->adresse . " " 
//     . $row->code_postal . " " 
//     . $row->ville));
//     $pdf->Ln();
//     $pdf->Cell(40,10, utf8_decode("nombre d'exemplaire distribués : ".$row->distribués."   (prévu : ".$row->exemplaires.")"));
//     $pdf->Ln();
//     $pdf->Ln();
// }

// //Output
// if($user2_info){
//     $pdf->Output('F', 'rapports/'.$result2->next_date.' '.$result2->client.', '.$result2->nom.' - '.$user_info->prenom.' '.$user_info->nom.' et '.$user2_info->prenom.' '.$user2_info->nom.' '.date('d-m-Y').'.pdf', true);
// }else{
//     $pdf->Output('F', 'rapports/'.$result2->next_date.' '.$result2->client.', '.$result2->nom.' - '.$user_info->prenom.' '.$user_info->nom.' '.date('d-m-Y').'.pdf', true);
// }

// echo "Le rapport PDF a été envoyé";

//Rapport Excel
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Nom')
            ->setCellValue('C1', 'Adresse')
            ->setCellValue('D1', 'Code postal')
            ->setCellValue('E1', 'Ville')
            ->setCellValue('F1', 'Tournée')
            ->setCellValue('G1', 'Info')
            ->setCellValue('H1', 'Exemplaires prévus')
            ->setCellValue('I1', 'Exemplaires distribués')
            ->setCellValue('J1', 'Jour')
            ->setCellValue('K1', 'Heure')
            ->setCellValue('L1', 'Catégorie')
            ->setCellValue('M1', 'Motif')
            ->setCellValue('N1', 'Commentaires livreur');
$count=1;
foreach($result as $row){
    $count++;
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$count, $row->id)
    ->setCellValue('B'.$count, $row->nom)
    ->setCellValue('C'.$count, $row->adresse)
    ->setCellValue('D'.$count, $row->code_postal)
    ->setCellValue('E'.$count, $row->ville)
    ->setCellValue('F'.$count, $row->tournees)
    ->setCellValue('G'.$count, $row->infos)
    ->setCellValue('H'.$count, $row->exemplaires)
    ->setCellValue('I'.$count, $row->distribués)
    ->setCellValue('J'.$count, $row->last_update)
    ->setCellValue('K'.$count, $row->heure)
    ->setCellValue('L'.$count, $row->categorie)
    ->setCellValue('M'.$count, $row->motif)
    ->setCellValue('N'.$count, $row->commentaires);
}

foreach(range('A','N') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->calculateColumnWidths();


$objPHPExcel->getActiveSheet()->setTitle('Rapport');

// Save Excel 2007 file
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//Output
if($user2_info){
    $objWriter->save('rapports/'.$result2->next_date.' '.$result2->client.', '.$result2->nom.' - '.$user_info->prenom.' '.$user_info->nom.' et '.$user2_info->prenom.' '.$user2_info->nom.' '.date('d-m-Y').'.xlsx');
}else{
    $objWriter->save('rapports/'.$result2->next_date.' '.$result2->client.', '.$result2->nom.' - '.$user_info->prenom.' '.$user_info->nom.' '.date('d-m-Y').'.xlsx');
}
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , ' File written to rapports/tournée'.$result2->next_date.' '.$result2->client.' - '.$result2->nom.', '.$user_info->prenom.' '.$user_info->nom.' '.date('d-m-Y').'.xlsx';

?>
