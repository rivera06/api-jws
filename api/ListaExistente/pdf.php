<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" || $_SERVER['REQUEST_METHOD'] == "OPTIONS") {
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: text/html; charset=UTF-8");
  header("Access-Control-Allow-Methods: GET,OPTIONS");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  include_once '../../config/database.php';
  include_once '../objects/lista_existente.php';
  require_once('../../libs/tcpdf/tcpdf.php');


  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('1');
  $pdf->SetTitle('2');
  $pdf->SetSubject('3');
  $pdf->SetKeywords('4');

  // set default header data
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
  $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

  // set header and footer fonts
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  // set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  

  // set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

  // set some language-dependent strings (optional)
  if (@file_exists(dirname(_FILE_) . '/lang/spa.php')) {
    require_once(dirname(_FILE_) . '/lang/spa.php');
    
    $pdf->setLanguageArray($l);
  }

  // ---------------------------------------------------------

  // set default font subsetting mode
  $pdf->setFontSubsetting(true);

  // Set font
  // dejavusans is a UTF-8 Unicode font, if you only need to
  // print standard ASCII chars, you can use core fonts like
  // helvetica or times to reduce file size.
  $pdf->SetFont('dejavusans', '', 14, '', true);

  // Add a page
  // This method has several options, check the source code documentation for more information.
  $pdf->AddPage();

  // set text shadow effect
  $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

  $pdf->Write(0, 'Lista Existente', '', 0, 'L', true, 0, false, false, 0);
  $pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
  $database = new Database();
  $db = $database->getConnection();
  $lista_existente = new lista_existente($db);
  $lista_existente->FechaCreacion='2019-08-17';
  $stmt = $lista_existente->ListaExistente();
  $ID = $lista_existente->IdListaExistene;

$lista_existente->IdListaExistene = $ID;
$stmt = $lista_existente->ListaDetalle();
  $num = $stmt->rowCount();

  //echo $html ="<style>td{align: center;}</style>";
  $html = "<h5>Usuario creador: ".$lista_existente->Nombre_Usuario." "." Sucursal: ".$lista_existente->Sucursal."</h5><br />";
  if ($num > 0) {
    $html .= '<table border="1"><tr style=" background-color: #4CAF50; color: white;"><th align="center">Producto</th><th align="center">Cantidad</th><th align="center">Porcion</th></tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $html .= '<tr ><td align="center">' . $Producto . '</td><td align="center">' . $Cantidad . '</td><td align="center">' . $Porcion . '</td></tr>';
    }
    $html .= "</table>";

     // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output('ListaDetalle.pdf', 'I');

    http_response_code(200);
  } else {
    http_response_code(404);
    echo json_encode(
      array("message" => "No se encontraron datos.")
    );
  }
} else {
  http_response_code(404);
}