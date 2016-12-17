<?php
include_once(libraries_get_path('tcpdf') . '/tcpdf.php');
$n = $node->nid;
$s = $node->title;
/*
 * Jornada Semanal Entity Wrapper
 */
$wrapper = entity_metadata_wrapper('node', $n);

$fecha_inicio = new DateTime($wrapper->field_fecha_descarga->value()['value']);
$fecha_fin = new DateTime($wrapper->field_fecha_descarga->value()['value2']);
$inicio = $fecha_inicio->format('d/m/Y');
$fin = $fecha_fin->format('d/m/Y');

/*
 * Jefe de Maquinas
 */
$jefe_maq_id = $wrapper->field_jefe_de_maq_descarga->raw();
$query = db_select('node', 'n');
$query->leftJoin('field_data_field_nombre_personal', 'jNom', 
    'jNom.entity_id = n.nid');
$query->leftJoin('field_data_field_ap_paterno_personal', 'jPat', 
    'jPat.entity_id = n.nid');
$query->leftJoin('field_data_field_ap_materno_personal', 'jMat', 
    'jMat.entity_id = n.nid');
$query->addfield('n', 'title', 'title');
$query->addField('jNom', 'field_nombre_personal_value', 'nombre');
$query->addField('jPat', 'field_ap_paterno_personal_value', 'paterno');
$query->addField('jMat', 'field_ap_materno_personal_value', 'materno');

$query->condition('n.nid', $jefe_maq_id, '=');
$exeResults = $query->execute();
$results = $exeResults->fetchAll();

foreach ($results as $result) {
  $jefe_maq_title = $result->title;
  $jefe_nombre = ucwords(strtolower($result->nombre));
  $jefe_paterno = ucwords(strtolower($result->paterno));
  $jefe_materno = ucwords(strtolower($result->materno));
  
  $full = $jefe_nombre . ' ' . $jefe_paterno . ' ' . $jefe_materno;
}
/*
 * Lado de la Descarga
 */
$lado_tid = $wrapper->field_des_del_barco_desc->raw();
$table = 'taxonomy_term_data';
$query = db_select($table, 't');
$query->addfield('t', 'name', 'lado');
$query->condition('t.tid', $lado_tid, '=');
$exeResults = $query->execute();
$results = $exeResults->fetchAll();

foreach ($results as $result) {
  $lado = $result->lado;
}

/*
 * Numero de Bascula
 */
$bascula_nid = $wrapper->field_bascula_desc->raw();
$table = 'field_data_field_numero_bascula';
$query = db_select($table, 't');
$query->addfield('t', 'field_numero_bascula_value', 'num_bascula');
$query->condition('t.entity_id', $bascula_nid, '=');
$exeResults = $query->execute();
$results = $exeResults->fetchAll();

foreach ($results as $result) {
  $num_bascula = $result->num_bascula;
}

/*
 * Toneladas Estimadas
 */
$ton_esti_AAA_40 = $wrapper->field_aaa_40_estim_desc->value();
$ton_esti_AAA_20_40 = $wrapper->field_aaa_20a40_estim_desc->value();
$ton_esti_AAA_12_20 = $wrapper->field_aaa_12a20_estim_desc->value();
$ton_esti_AAA_5_12 = $wrapper->field_aaa_5a12_estim_desc->value();
$ton_esti_AAA_2_5 = $wrapper->field_aaa_2a5_estim_desc->value();
$ton_esti_BB_3_5 = $wrapper->field_bb_3_5_estim_desc->value();
$ton_esti_BB_2_3_5 = $wrapper->field_bb_2a3_5_estim_desc->value();
$ton_esti_AAA_1_2 = $wrapper->field_aaa_1a2_estim_desc->value();
$ton_esti_BB_1_2 = $wrapper->field_bb_1a2_estim_desc->value();
$total_esti = $ton_esti_AAA_40 + $ton_esti_AAA_20_40 + $ton_esti_AAA_12_20 +
    $ton_esti_AAA_5_12 + $ton_esti_AAA_2_5 + $ton_esti_BB_3_5 + $ton_esti_BB_2_3_5 +
    $ton_esti_AAA_1_2 + $ton_esti_BB_1_2;
/*
 * Porcentajes
 */
$ton_esti_AAA_40_perc = ($ton_esti_AAA_40 * 100) / $total_esti;
$ton_esti_AAA_20_40_perc = ($ton_esti_AAA_20_40 * 100) / $total_esti;
$$ton_esti_AAA_12_20_perc = ($ton_esti_AAA_12_20 * 100) / $total_esti;
$ton_esti_AAA_5_12_perc = ($ton_esti_AAA_5_12 * 100) / $total_esti;
$ton_esti_AAA_2_5_perc = ($ton_esti_AAA_2_5 * 100) / $total_esti;
$ton_esti_BB_3_5_perc = ($ton_esti_BB_3_5 * 100) / $total_esti;
$ton_esti_BB_2_3_5_perc = ($ton_esti_BB_2_3_5 * 100) / $total_esti;
$ton_esti_AAA_1_2_perc = ($ton_esti_AAA_1_2 * 100) / $total_esti;
$ton_esti_BB_1_2_perc = ($ton_esti_BB_1_2 * 100) / $total_esti;
$total_esti_perc = $ton_esti_AAA_40_perc + $ton_esti_AAA_20_40_perc +
    $$ton_esti_AAA_12_20_perc + $ton_esti_AAA_5_12_perc + $ton_esti_AAA_2_5_perc +
    $ton_esti_BB_3_5_perc + $ton_esti_BB_2_3_5_perc + $ton_esti_AAA_1_2_perc +
    $ton_esti_BB_1_2_perc;

/*
 * Primera Calidad
 */
$aaa_60_cal1 = $wrapper->field_aaa_60_cal1_desc->value();
$aaa_40a60_cal1 = $wrapper->field_aaa_40a60_cal1_desc->value();
$aaa_20a40_cal1 = $wrapper->field_aaa_20a40_cal1_desc->value();
$aaa_12a20_cal1 = $wrapper->field_aaa_12a20_cal1_desc->value();
$aaa_5a12_cal1 = $wrapper->field_aaa_5a12_cal1_desc->value();
$aaa_2a5_cal1 = $wrapper->field_aaa_2a5_cal1_desc->value();
$aaa_1a2_cal1 = $wrapper->field_aaa_1a2_cal1_desc->value();
$aaa_1_cal1 = $wrapper->field_aaa_1_cal1_desc->value();
$bte_5_cal1 = $wrapper->field_bte_5_cal1_desc->value();
$bte_3_5a5_cal1 = $wrapper->field_bte_3_5a5_cal1_desc->value();
$bte_2a3_5_cal1 = $wrapper->field_bte_2a3_5_cal1_desc->value();
$bte_1a2_cal1 = $wrapper->field_bte_1a2_cal1_desc->value();
$bte_1_cal1 = $wrapper->field_bte_1_cal1_desc->value();
$bte_ngo_1a2_cal1 = $wrapper->field_bte_ngo_1a2_cal1_desc->value();

$total_cal1 = $aaa_60_cal1 + $aaa_40a60_cal1 + $aaa_20a40_cal1 +
    $aaa_12a20_cal1 + $aaa_5a12_cal1 + $aaa_2a5_cal1 + $aaa_1a2_cal1 +
    $aaa_1_cal1 + $bte_5_cal1 + $bte_3_5a5_cal1 + $bte_2a3_5_cal1 +
    $bte_1a2_cal1 + $bte_1_cal1 + $bte_ngo_1a2_cal1;

/*
 * Segunda Calidad
 */
$aaa_60_cal2 = $wrapper->field_aaa_60_cal2_desc->value();
$aaa_40a60_cal2 = $wrapper->field_aaa_40a60_cal2_desc->value();
$aaa_20a40_cal2 = $wrapper->field_aaa_20a40_cal2_desc->value();
$aaa_12a20_cal2 = $wrapper->field_aaa_12a20_cal2_desc->value();
$aaa_5a12_cal2 = $wrapper->field_aaa_5a12_cal2_desc->value();
$aaa_2a5_cal2 = $wrapper->field_aaa_2a5_cal2_desc->value();
$aaa_1a2_cal2 = $wrapper->field_aaa_1a2_cal2_desc->value();
$aaa_1_cal2 = $wrapper->field_aaa_1_cal2_desc->value();
$bte_5_cal2 = $wrapper->field_bte_5_cal2_desc->value();
$bte_3_5a5_cal2 = $wrapper->field_bte_3_5a5_cal2_desc->value();
$bte_2a3_5_cal2 = $wrapper->field_bte_2a3_5_cal2_desc->value();
$bte_1a2_cal2 = $wrapper->field_bte_1a2_cal2_desc->value();
$bte_1_cal2 = $wrapper->field_bte_1_cal2_desc->value();
$bte_ngo_1a2_cal2 = $wrapper->field_bte_ngo_1a2_cal2_desc->value();

$total_cal2 = $aaa_60_cal2 + $aaa_40a60_cal2 + $aaa_20a40_cal2 +
    $aaa_12a20_cal2 + $aaa_5a12_cal2 + $aaa_2a5_cal2 + $aaa_1a2_cal2 +
    $aaa_1_cal2 + $bte_5_cal2 + $bte_3_5a5_cal2 + $bte_2a3_5_cal2 +
    $bte_1a2_cal2 + $bte_1_cal2 + $bte_ngo_1a2_cal2;

$total_cal = $total_cal1 + $total_cal2;
$total_cal1_perc = ($total_cal1 * 100) / $total_cal;
$total_cal2_perc = ($total_cal2 * 100) / $total_cal;
$total_cal1_2_perc = $total_cal1_perc + $total_cal2_perc;

$aaa_60_cal_perc = (($aaa_60_cal1 + $aaa_60_cal2) * 100) / $total_cal;
$aaa_40a60_cal_perc = (($aaa_40a60_cal1 + $aaa_40a60_cal2) * 100) / $total_cal;
$aaa_20a40_cal_perc = (($aaa_20a40_cal1 + $aaa_20a40_cal2) * 100) / $total_cal;
$aaa_12a20_cal_perc = (($aaa_12a20_cal1 + $aaa_12a20_cal2) * 100) / $total_cal;
$aaa_5a12_cal_perc = (($aaa_5a12_cal1 + $aaa_5a12_cal2) * 100) / $total_cal;
$aaa_2a5_cal_perc = (($aaa_2a5_cal1 + $aaa_2a5_cal2) * 100) / $total_cal;
$aaa_1a2_cal_perc = (($aaa_1a2_cal1 + $aaa_1a2_cal2) * 100) / $total_cal;
$aaa_1_cal_perc = (($aaa_1_cal1 + $aaa_1_cal2) * 100) / $total_cal;
$bte_5_cal_perc = (($bte_5_cal1 + $bte_5_cal2) * 100) / $total_cal;
$bte_3_5a5_cal_perc = (($bte_3_5a5_cal1 + $bte_3_5a5_cal2) * 100) / $total_cal;
$bte_2a3_5_cal_perc = (($bte_2a3_5_cal1 + $bte_2a3_5_cal2) * 100) / $total_cal;
$bte_1a2_cal_perc = (($bte_1a2_cal1 + $bte_1a2_cal2) * 100) / $total_cal;
$bte_1_cal_perc = (($bte_1_cal1 + $bte_1_cal2) * 100) / $total_cal;
$bte_ngo_1a2_cal_perc = (($bte_ngo_1a2_cal1 + $bte_ngo_1a2_cal2) * 100) / $total_cal;

$total_desc_perc = $aaa_60_cal_perc + $aaa_40a60_cal_perc + $aaa_20a40_cal_perc +
    $aaa_12a20_cal_perc + $aaa_5a12_cal_perc + $aaa_2a5_cal_perc + $aaa_1a2_cal_perc +
    $aaa_1_cal_perc + $bte_5_cal_perc + $bte_3_5a5_cal_perc + $bte_2a3_5_cal_perc +
    $bte_1a2_cal_perc + $bte_1_cal_perc + $bte_ngo_1a2_cal_perc;
/*
 * CLASIFICACION
 */
$marcado = $wrapper->field_marcado_cal2_desc->value();
$reventado = $wrapper->field_reventado_cal2_desc->value();
$tallado = $wrapper->field_tallado_cal2_desc->value();
$mal_olor = $wrapper->field_mal_olor_cal2_desc->value();

$total_seg_calidad = $marcado + $reventado + $tallado + $mal_olor;

$marcado_perc = ($marcado * 100) / $total_cal;
$reventado_perc = ($reventado * 100) / $total_cal;
$tallado_perc = ($tallado * 100) / $total_cal;
$mal_olor_perc = ($mal_olor * 100) / $total_cal;

$total_seg_calidad_perc = $marcado_perc + $reventado_perc + $tallado_perc +
    $mal_olor_perc;

/*
 * RECHAZO
 */
$aaa_bte_recha = $wrapper->field_aaa_bte_recha_desc->value();
$bule_recha = $wrapper->field_bule_recha_desc->value();
$fauna_recha = $wrapper->field_fauna_recha_desc->value();

$total_rechazo = $aaa_bte_recha + $bule_recha + $fauna_recha;

$dir = 'sites/default/files/pdf/';
$file = $dir . 'reporte-descarga-final-' . $n . '.pdf';
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
  
    public $today;
    public $html;
    
    public function setDate() {
      $this->today = 'Fecha: ' . date("d/m/Y");
      
      return $this->today;
    }
    
    //Page header
    public function Header() {
        // Logo
        $this->Ln(1, false);
        $image_file = 'sites/default/files/logos/paz_logo_short.png';
        $this->Image($image_file, 17, 10, 20, '', 'PNG', 'http://www.mazpesca.com', 'T', false, 300, '', false, false, 0, false, false, false);
        
        $this->Ln(2, false);
        // Set font
        $this->SetFont('times', '', 14);
        // Title
        //$this->Cell(0, 10, 'PESCA AZTECA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        //$this->Cell(0, 10, self::setDate(), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Text(72, 10, 'PESCA AZTECA S.A. DE C.V.');
        $this->SetFont('times', '', 12);
        $this->Text(73, 17, 'REPORTE DE DESCARGA FINAL');
        $this->SetFont('times', '', 10);
        $this->Text(165, 11, self::setDate());
  }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('www.mazpesca.com');
$pdf->SetTitle('PESCA AZTECA');
$pdf->SetSubject('REPORTE DE DESCARGA');
//$pdf->SetKeywords('Tripulantes');
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
// ---------------------------------------------------------
// set font
$pdf->SetFont('times', '', 10);
// add a page
$pdf->AddPage();
// ---------------------------------------------------------
/*
 * HTML Tables
 */
$html ='
<style>
table, th, td {
  border-collapse: collapse;
  color: #FFFFFF;
  padding: 4px;
}
td, th {
  text-align: center;
  border: 1px solid #dddddd;
}

</style>
<table style="width:100%">
  <tr bgcolor="#1F497D">
    <th>' . $s . '</th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
/*
 * Informacion General
 */
$pdf->Ln(1, FALSE);
$pdf->SetFont('times', '', 7);
$html ='
<style>
table, th, td {
  border-collapse: collapse;
  padding: 2px;
}
.pull-right {
    text-align: right;
}
.pull-left {
    text-align: left;
}
.pull-center {
    text-align: center;
}
</style>
<br>
<table style="width:100%">
  <tr>
    <td class="pull-left" width="100%">INFORMACIÓN GENERAL</td>
  </tr>
  <tr>
    <td class="pull-left" width="22.66%">Fecha de Inicio de Descarga: </td>
    <td class="pull-left" width="10.66%">' . $inicio . '</td>
    <td class="pull-left" width="16.66%">Descarga del Barco: </td>
    <td class="pull-left" width="6.66%">' . $lado . '</td>
    <td class="pull-left" width="30.66%">Cantidad Estimada por Descarga: </td>
    <td class="pull-right" width="12.66%">'. number_format($total_esti, 2) .'</td>
  </tr>
  <tr>
    <td class="pull-left" width="22.66%">Fecha de Fin de Descarga: </td>
    <td class="pull-left" width="10.66%">' . $fin . '</td>
    <td class="pull-left" width="16.66%">Bascula: </td>
    <td class="pull-left" width="6.66%">' . $num_bascula . '</td>
    <td class="pull-left" width="30.66%">Cantidad Descargada Total: </td>
    <td class="pull-right" width="12.66%">'. number_format(($total_cal), 3) .'</td>
  </tr>
  <tr>
    <td class="pull-left" width="100%">Jefe de Maquinas: Ing. ' . $full . '</td>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
/*
 * Grey Bar 1
 */
$pdf->Ln(1, FALSE);
$html = '
<style>
table, td {
    border-collapse: collapse;
    padding: 1px;
}
.pull-center {
    text-align: center;
}
td, th {
    border: 1px solid #dddddd;
}
</style>
<table>
  <tr bgcolor="#dddddd">
    <th class="pull-center" width="100%"></th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
//$pdf->Ln(1, FALSE);
/*
 * Plan de Estiba
 */

$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 2px;
}
.table1 {
  width: 100%;
  margin-left: 50px;
}
.table2 {
  width: 100%;
  margin-left: 50px;
}
td {
  width: 150px; 
}
.col {
  padding: 15px;
  background-color: #ccc;
  border: 1px solid #000;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
.pull-right {
    text-align: right;
}
</style>
<table class="table1">
  <tr>
    <td>PLAN DE ESTIBA ESTIMADO POR DESCARGAR</td>
  </tr>
</table>
<table class="table2">
  <tr>
    <td></td>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
    <td class="pull-center">TALLA</td>
    <td class="pull-center">1º CALIDAD</td>
    <td class="pull-center">2º CALIDAD</td>
    <td class="pull-center">SUBTOTAL</td>
    <td class="pull-center">%</td>
  </tr>
  <tr>
    <td></td>
    <td class="pull-center">CANTIDAD</td>
    <td class="pull-center">%</td>
    <td class="pull-left">AAA +60</td>
    <td class="pull-center">'. number_format($aaa_60_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_60_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_60_cal1 + $aaa_60_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_60_cal1 + $aaa_60_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td>AAA +40</td>
    <td class="pull-center">' . number_format($ton_esti_AAA_40, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_40_perc, 2) .'%</td>
    <td>AAA 40/60</td>
    <td class="pull-center">'. number_format($aaa_40a60_cal1, 3).'</td>
    <td class="pull-center">'. number_format($aaa_40a60_cal2, 3).'</td>
    <td class="pull-center">'. number_format(($aaa_40a60_cal1 + $aaa_40a60_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_40a60_cal1 + $aaa_40a60_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td>AAA 20/40</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_20_40, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_20_40_perc, 2) .'%</td>
    <td>AAA 20/40</td>
    <td class="pull-center">'. number_format($aaa_20a40_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_20a40_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_20a40_cal1 + $aaa_20a40_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_20a40_cal1 + $aaa_20a40_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td>AAA 12/20</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_12_20, 0) .'</td>
    <td class="pull-center">'. number_format($$ton_esti_AAA_12_20_perc, 2) .'%</td>
    <td>AAA 12/20</td>
    <td class="pull-center">'. number_format($aaa_12a20_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_12a20_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_12a20_cal1 + $aaa_12a20_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_12a20_cal1 + $aaa_12a20_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td>AAA 5/12</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_5_12, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_5_12_perc, 2) .'%</td>
    <td>AAA 5/12</td>
    <td class="pull-center">'. number_format($aaa_5a12_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_5a12_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_5a12_cal1 + $aaa_5a12_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_5a12_cal1 + $aaa_5a12_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td>AAA 2/5</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_2_5, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_2_5_perc, 2) .'%</td>
    <td>AAA 2/5</td>
    <td class="pull-center">'. number_format($aaa_2a5_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_2a5_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_2a5_cal1 + $aaa_2a5_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_2a5_cal1 + $aaa_2a5_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td class="pull-left">B.B. 3.5</td>
    <td class="pull-center">'. number_format($ton_esti_BB_3_5, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_BB_3_5_perc, 2) .'%</td>
    <td>AAA 1/2</td>
    <td class="pull-center">'. number_format($aaa_1a2_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_1a2_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_1a2_cal1 + $aaa_1a2_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_2a5_cal1 + $aaa_2a5_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td class="pull-left">B.B. 2/3.5</td>
    <td class="pull-center">'. number_format($ton_esti_BB_2_3_5, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_BB_2_3_5_perc, 2) .'%</td>
    <td>AAA -1</td>
    <td class="pull-center">'. number_format($aaa_1_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($aaa_1_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($aaa_1_cal1 + $aaa_1_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($aaa_1_cal1 + $aaa_1_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td class="pull-left">AAA 1/2</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_1_2, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_AAA_1_2_perc, 2) .'%</td>
    <td>BTE +5</td>
    <td class="pull-center">'. number_format($bte_5_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_5_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_5_cal1 + $bte_5_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_5_cal1 + $bte_5_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td class="pull-left">B.B. 1/2</td>
    <td class="pull-center">'. number_format($ton_esti_BB_1_2, 0) .'</td>
    <td class="pull-center">'. number_format($ton_esti_BB_1_2_perc, 2) .'%</td>
    <td>BTE 3.5/5</td>
    <td class="pull-center">'. number_format($bte_3_5a5_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_3_5a5_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_3_5a5_cal1 + $bte_3_5a5_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_3_5a5_cal1 + $bte_3_5a5_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>BTE 2/3.5</td>
    <td class="pull-center">'. number_format($bte_2a3_5_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_2a3_5_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_2a3_5_cal1 + $bte_2a3_5_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_2a3_5_cal1 + $bte_2a3_5_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>BTE 1/2</td>
    <td class="pull-center">'. number_format($bte_1a2_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_1a2_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_1a2_cal1 + $bte_1a2_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_1a2_cal1 + $bte_1a2_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>BTE -1</td>
    <td class="pull-center">'. number_format($bte_1_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_1_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_1_cal1 + $bte_1_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_1_cal1 + $bte_1_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>BTE NGO 1/2</td>
    <td class="pull-center">'. number_format($bte_ngo_1a2_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($bte_ngo_1a2_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($bte_ngo_1a2_cal1 + $bte_ngo_1a2_cal2), 3) .'</td>
    <td class="pull-center">'. number_format(((($bte_ngo_1a2_cal1 + $bte_ngo_1a2_cal2) * 100) / $total_cal), 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td class="pull-left">TOTAL</td>
    <td class="pull-center">'. number_format($total_esti, 3) .'</td>
    <td class="pull-center">'. round($total_esti_perc, 2) .'%</td>
    <td class="pull-left">TOTALES</td>
    <td class="pull-center">'. number_format($total_cal1, 3) .'</td>
    <td class="pull-center">'. number_format($total_cal2, 3) .'</td>
    <td class="pull-center">'. number_format(($total_cal1 + $total_cal2), 3) .'</td>
    <td class="pull-center">'. round($total_desc_perc, 2) .'%</td>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
/*
 * Grey Bar 2
 */
$pdf->Ln(1, FALSE);
$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 2px;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
td {
    text-align: left;
}
td, th {
    border: 1px solid #dddddd;
}
</style>
<table>
  <tr bgcolor="#dddddd">
    <th class="pull-center" width="100%"><strong></strong></th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
/*
 * Clasificacion General
 */
$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 2px;
}
.table1 {
  width: 100%;
  margin-left: 50px;
}
.table2 {
  width: 100%;
  margin-left: 50px;
}
td {
  width: 150px; 
}
.col {
  padding: 15px;
  background-color: #ccc;
  border: 1px solid #000;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
.pull-right {
    text-align: right;
}
</style>
<table class="table1">
  <tr>
    <td class="pull-center">PORCENTAJE DE CLASIFICACIÓN GENERAL</td>
    <td class="pull-center">CLASIFICACIÓN DE PESCADO 2º CALIDAD</td>
    <td class="pull-center">PESCADO RECHAZADO</td>
  </tr>
</table>
<table class="table2">
  <tr>
    <td></td>
    <td></td>
    <td>CLASIFICACIÓN</td>
    <td class="pull-center">CANTIDAD</td>
    <td class="pull-center">%</td>
    <td>ESPECIE</td>
    <td class="pull-center">CANTIDAD</td>
  </tr>
  <tr>
    <td>1º CALIDAD</td> 
    <td class="pull-center">'. number_format($total_cal1_perc, 2) .'%</td>
    <td>MARCADO</td>
    <td class="pull-center">'. number_format($marcado, 3) .'</td>
    <td class="pull-center">'. number_format($marcado_perc, 2) .'%</td>
    <td>AAA / BTE</td>
    <td class="pull-center">'. number_format($aaa_bte_recha, 3) .'</td>
  </tr>
  <tr>
    <td>2º CALIDAD</td>
    <td class="pull-center">'. number_format($total_cal2_perc, 2) .'%</td>
    <td>REVENTADO</td>
    <td class="pull-center">'. number_format($reventado, 3) .'</td>
    <td class="pull-center">'. number_format($reventado_perc, 2) .'%</td>
    <td>BULE</td>
    <td class="pull-center">'. number_format($bule_recha, 3) .'</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>TALLADO</td>
    <td class="pull-center">'. number_format($tallado, 3) .'</td>
    <td class="pull-center">'. number_format($tallado_perc, 2) .'%</td>
    <td>FAUNA ACOMP.</td>
    <td class="pull-center">'. number_format($fauna_recha, 3) .'</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>MAL OLOR</td>
    <td class="pull-center">'. number_format($mal_olor, 3) .'</td>
    <td class="pull-center">'. number_format($mal_olor_perc, 2) .'%</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td class="pull-center">'. round($total_cal1_2_perc, 2) .'%</td>
    <td>TOTAL</td>
    <td class="pull-center">'. number_format($total_seg_calidad, 3) .'</td>
    <td class="pull-center">'. number_format($total_seg_calidad_perc, 2) .'%</td>
    <td>TOTAL</td>
    <td class="pull-center">'. number_format($total_rechazo, 3) .'</td>
  </tr>
</table>';

$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
/*
 * Grey Bar 3
 */
$pdf->Ln(1, FALSE);
$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 2px;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
td {
    text-align: left;
}
td, th {
    border: 1px solid #dddddd;
}
</style>
<table>
  <tr bgcolor="#dddddd">
    <th class="pull-center" width="100%"><strong></strong></th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);

/*
 * Descargas por Turno
 */

$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 2px;
}
.table1 {
  width: 100%;
  margin-left: 50px;
}
.table2 {
  width: 100%;
  margin-left: 50px;
}
td {
  width: 150px; 
}
.col {
  padding: 15px;
  background-color: #ccc;
  border: 1px solid #000;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
.pull-right {
    text-align: right;
}
</style>
<table class="table1">
  <tr>
    <td class="pull-left">INFORMACIÓN DE CANTIDADES DESCARGADAS POR TURNO</td>
  </tr>
</table>
<table class="table1">
  <tr>
    <td></td>
    <td class="pull-center">HORA DE INICIO Y TERMINO DE 1er TURNO</td>
    <td class="pull-center">HORA DE INICIO Y TERMINO DE 2do TURNO</td>
  </tr>
</table>
<table class="table2">
  <tr>
    <td></td>
    <td class="pull-center">1er TURNO</td>
    <td class="pull-center">2do TURNO</td>
    <td class="pull-center">3er TURNO</td>
    <td class="pull-center">DESCARGA</td>
    <td class="pull-center">INICIO</td>
    <td class="pull-center">FINAL</td>
    <td class="pull-center">GANCHOS DESCARGA</td>
    <td class="pull-center">INICIO</td>
    <td class="pull-center">FINAL</td>
    <td class="pull-center">GANCHOS DESCARGA</td>
  </tr>';

$query = db_select('turnos_tons_view3', 'turnos');
  $query->leftJoin('field_data_field_fecha_turno', 'jfecha', 
      'jfecha.entity_id = turnos.eid');
  $query->leftJoin('turno1_horas_nid_view', 'h1', 'h1.eid = turnos.eid');
  $query->leftJoin('turno2_horas_nid_view', 'h2', 'h2.nid = turnos.nid');
  
  $query->addField('turnos', 'nid', 'nid');
  $query->addField('turnos', 'fechas', 'fecha');
  $query->addField('turnos', 'turno1', 'turno1');
  $query->addField('turnos', 'turno2', 'turno2');
  $query->addField('jfecha', 'field_fecha_turno_value', 'hora1');
  $query->addField('jfecha', 'field_fecha_turno_value2', 'hora2');
  
  $query->addField('h1', 'inicio', 'inicio1');
  $query->addField('h1', 'fin', 'fin1');
  $query->addField('h2', 'inicio', 'inicio2');
  $query->addField('h2', 'fin', 'fin2');

  $query->condition('turnos.nid', $n, '=');

  $query->groupBy('fecha');
  $exeResults = $query->execute();
  $results = $exeResults->fetchAll();
  //$html .= '<table>';
  
  foreach ($results as $result) {
    
    $hora1_raw = new DateTime($result->hora1);
    $hora1_raw->modify('-7 hours');
    $hora1 = $hora1_raw->format('H:i');
    
    $hora2_raw = new DateTime($result->hora2);
    $hora2_raw->modify('-7 hours');
    $hora2 = $hora2_raw->format('H:i');
    
    if (isset($result->inicio1)) {
      $inicio1_raw = new DateTime($result->inicio1);
      $inicio1_raw->modify('-7 hours');
      $inicio1 = $inicio1_raw->format('H:i');
    }
    else {
      $inicio1 = '';
    }
    
    if (isset($result->fin1)) {
      $fin1_raw = new DateTime($result->fin1);
      $fin1_raw->modify('-7 hours');
      $fin1 = $fin1_raw->format('H:i');
    }
    else {
      $fin1 = '';
    }
    if (isset($result->inicio2)) {
      $inicio2_raw = new DateTime($result->inicio2);
      $inicio2_raw->modify('-7 hours');
      $inicio2 = $inicio2_raw->format('H:i');
    }
    else {
      $inicio2 = '';
    }
    if (isset($result->fin2)) {
      $fin2_raw = new DateTime($result->fin2);
      $fin2_raw->modify('-7 hours');
      $fin2 = $fin2_raw->format('H:i');
    }
    else {
      $fin2 = '';
    }
    
    $total_turnos = $result->turno1 + $result->turno2;
    $total_final += $total_turnos;
    $html .= '<tr>';
    $html .= '<td class="pull-center">' . $result->fecha . '</td>';
    $html .= '<td class="pull-center">' . $result->turno1 . '</td>';
    $html .= '<td class="pull-center">' . $result->turno2 . '</td>';
    $html .= '<td class="pull-center">' . '' . '</td>';
    $html .= '<td class="pull-center">' . $total_turnos . '</td>';
    //$html .= '<td class="pull-center">' . $hora1 . '</td>';
    //$html .= '<td class="pull-center">' . $hora2 . '</td>';
    $html .= '<td class="pull-center">' . $inicio1 . '</td>';
    $html .= '<td class="pull-center">' . $fin1 . '</td>';
    $html .= '<td class="pull-center">' . '' . '</td>';
    $html .= '<td class="pull-center">' . $inicio2 . '</td>';
    $html .= '<td class="pull-center">' . $fin2 . '</td>';
    $html .= '</tr>';
  }
$html .= '<tr>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . 'Acumulado' . '</td>';
$html .= '<td class="pull-center">' . $total_final . '</td>';
$html .= '<td class="pull-center">' . 'Ton.' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '<td class="pull-center">' . '' . '</td>';
$html .= '</tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
/*
 * Grey Bar 3
 */
$pdf->Ln(1, FALSE);
$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 1px;
}
.pull-center {
    text-align: center;
}
.pull-left {
    text-align: left;
}
td {
    text-align: left;
}
td, th {
    border: 1px solid #dddddd;
}
</style>
<table>
  <tr bgcolor="#dddddd">
    <th class="pull-center" width="100%"><strong></strong></th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
/*
 * Temperatura Interna
 */
$html = '
<style>
  table, th, td {
      border-collapse: collapse;
      padding: 1px;
  }
  .table1 {
      width: 25%;
  }
  td {
      width: 150px; 
  }
  .col {
      padding: 15px;
      background-color: #ccc;
      border: 1px solid #000;
  }
  .pull-center {
      text-align: center;
  }
  .pull-left {
      text-align: left;
  }
  .pull-right {
      text-align: right;
  }
  .suelto {
    background-color: #ccc;
  }
  .tronador {
    background-color: #fff;
  }
  .pegado {
    background-color: #000;
  }
  .invisible {
      color: #ccc;
  }
  .invisible-tronador {
      color: #fff;
  }
  .invisible-pegado {
      color: #000;
  }
  .table-div {
      display: inline-table;
      border: 1px dotted gray;
      vertical-align: top;
  }
  .simbologia {
    margin-bottom: 16px;
  }
</style>
<table class="table1">
  <tr class="pull-center"><td colspan="5">Temperatura Interna del Pescado en Descarga (C)</td></tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">BR</td>
    <td class="pull-center"></td>
    <td class="pull-center">ER</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
/*
 * Cantidades Reportadas
 */
$html = '
<style>
  table, th, td {
      border-collapse: collapse;
      padding: 1px;
  }
  .table1 {
      width: 25%;
  }
  td {
      width: 150px; 
  }
  .col {
      padding: 15px;
      background-color: #ccc;
      border: 1px solid #000;
  }
  .pull-center {
      text-align: center;
  }
  .pull-left {
      text-align: left;
  }
  .pull-right {
      text-align: right;
  }
  .suelto {
    background-color: #ccc;
  }
  .tronador {
    background-color: #fff;
  }
  .pegado {
    background-color: #000;
  }
  .invisible {
      color: #ccc;
  }
  .invisible-tronador {
      color: #fff;
  }
  .invisible-pegado {
      color: #000;
  }
  .table-div {
      display: inline-table;
      border: 1px dotted gray;
      vertical-align: top;
  }
  .simbologia {
    margin-bottom: 16px;
  }
</style>
<table class="table1">
  <tr class="pull-center"><td colspan="5">Cantidades Reportadas VS Registradas en Bascula por Tanque</td></tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">BR</td>
    <td class="pull-center"></td>
    <td class="pull-center">ER</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
  </tr>
</table>';
$pdf->writeHTMLCell($w=180, $h=0, $x=55, $y=219, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);
/*
 * Evaluación de los tanques
 */
$html = '
<style>
  table, th, td {
    border-collapse: collapse;
    padding: 1px;
  }
  .table1 {
    width: 25%;
  }
  td {
    width: 150px; 
  }
  .col {
      padding: 15px;
      background-color: #ccc;
      border: 1px solid #000;
  }
  .pull-center {
      text-align: center;
  }
  .pull-left {
      text-align: left;
  }
  .pull-right {
      text-align: right;
  }
  .suelto {
    background-color: #ccc;
  }
  .tronador {
    background-color: #fff;
  }
  .pegado {
    background-color: #000;
  }
  .invisible {
      color: #ccc;
  }
  .invisible-tronador {
      color: #fff;
  }
  .invisible-pegado {
      color: #000;
  }
  .table-div {
      display: inline-table;
      border: 1px dotted gray;
      vertical-align: top;
  }
  .simbologia {
    margin-bottom: 16px;
  }
</style>
<table class="table1">
  <tr class="pull-center"><td colspan="5">Evaluación de los Tanques Descargados</td></tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">BR</td>
    <td class="pull-center"></td>
    <td class="pull-center">ER</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
    <td class="pull-center">1</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
    <td class="pull-center">2</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
    <td class="pull-center">3</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
    <td class="pull-center">4</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
    <td class="pull-center">5</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
    <td class="pull-center">6</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
    <td class="pull-center">7</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
    <td class="pull-center">8</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
    <td class="pull-center">9</td>
    <td class="pull-center"></td>
  </tr>
  <tr>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
    <td class="pull-center">10</td>
    <td class="pull-center"></td>
  </tr>
</table>';
$pdf->writeHTMLCell($w=180, $h=0, $x=100, $y=219, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);
/*
 * Simbologia
 */
$html = '
<style>
  table, th, td {
      border-collapse: collapse;
      
  }
  .table1 {
      width: 25%;
      padding: 1.5px;
  }
  td {
      width: 150px; 
  }
  .col {
      padding: 15px;
      background-color: #ccc;
      border: 1px solid #000;
  }
  .pull-center {
      text-align: center;
  }
  .pull-left {
      text-align: left;
  }
  .pull-right {
      text-align: right;
  }
  .suelto {
    background-color: #ccc;
  }
  .tronador {
    background-color: #fff;
  }
  .pegado {
    background-color: #000;
  }
  .invisible {
      color: #ccc;
  }
  .invisible-tronador {
      color: #fff;
  }
  .invisible-pegado {
      color: #000;
  }
  .table-div {
      display: inline-table;
      border: 1px dotted gray;
      vertical-align: top;
  }
  .simbologia {
    margin-bottom: 16px;
  }
</style>
<table class="table1">
  <tr class="pull-center simbologia"><td colspan="2">Evaluación de los Tanques Descargados<br></td></tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="suelto invisible">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">Pescado Suelto</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="tronador invisible-tronador" 
          style="border: 1px dotted #000">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">Pescado Tronador</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="pegado invisible-pegado">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">Pescado Pegado</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
      </td>
      <td class="pull-left" style="width: 80%">Criterio de Evaluación</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="suelto invisible">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">Sin Barrear Tanque</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="tronador invisible-tronador" style="border: 1px dotted #000">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">1 a 2 Barreos del Tanque</td>
    </tr>
    <tr>
      <td class="pull-center" style="width: 20%">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr><td class="pegado invisible-pegado">-</td>
          </tr>
        </table>
      </td>
      <td class="pull-left" style="width: 80%">> 3 Barreos o Descarga en Seco</td>
    </tr>
  </table>';
$pdf->writeHTMLCell($w=180, $h=0, $x=145, $y=219, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->Ln(1, FALSE);
/*
 * Footer
 */
$html = '<div class="pos-table">'
    . '<table class="table2">'
    . '<tr><td width="100%" align="center"><a href="http://www.mazpesca.com" '
    . 'style="color: #B0B0B0; text-decoration: none; font-size: 14px;">'
    . 'www.mazpesca.com</a></td></tr>'
    . '</table>'
    . '</div>';
$pdf->writeHTMLCell($w=0, $h=0, $x=20, $y=267, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->Ln();
//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/' .$file, 'F');
drupal_set_message(t('El archivo PDF se ha generado exitosamente y se envió a su correo.'));

?>
