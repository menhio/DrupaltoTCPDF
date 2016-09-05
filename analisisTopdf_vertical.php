<?php
include_once(libraries_get_path('tcpdf') . '/tcpdf.php');
$n = $node->nid;
$s = $node->title;
//$user = $site->user;
/*
 * Viaje Entity Wrapper
 */
$wrapper = entity_metadata_wrapper('node', $n);
$nid_barco = $wrapper->field_barco_viaje->raw();
$tid_viaje = $wrapper->field_viaje->raw();
$zarpe = $wrapper->field_fecha_zarpe_viaje->value();
$ing_puerto = $wrapper->field_ing_puerto_viaje->value();
$jefe_flota = $wrapper->field_jefe_flota_viaje->value();
$gte_rh = $wrapper->field_gerente_rh_viaje->value();
$gte_flota = $wrapper->field_gerente_flota_viaje->value();
$dir_ope = $wrapper->field_dir_oper_viaje->value();
$fc_trip = $wrapper->field_tripulacion_viaje->raw();
$cap_trip = $wrapper->field_capacidad_trip_barco->value();
/*
 * Barco Entity Wrapper
 */
$w_barco = entity_metadata_wrapper('node', $nid_barco);
$barco_name = $w_barco->title->value();
/*
 * Barco Taxonomy
 */
//$w_tid_viaje = entity_metadata_wrapper('taxonomy_vocabulary', $tid_viaje);
//$viaje_name = $w_tid_viaje->name->raw();
$query = db_select('taxonomy_term_data', 'tax_tbl');
$query->addField('tax_tbl', 'name', 'viaje_name');
$query->addField('tax_tbl', 'tid', 'tid_viaje');
$query->condition('tax_tbl.tid', $tid_viaje, '=');
$exeResults = $query->execute();
$results = $exeResults->fetchAll();
foreach ($results as $result) {
  $viaje_name = $result->viaje_name;
}
$dir = 'sites/default/files/pdf/';
$file = $dir . 'Analisis-Clinicos-' . $n . '.pdf';
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
        $this->SetFont('times', '', 10);
        $this->Text(165, 11, self::setDate());
  }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('www.mazpesca.com');
$pdf->SetTitle('PESCA AZTECA');
$pdf->SetSubject('LISTA DE TRIPULACION');
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
    <th>Departamento de Aseguramiento de Calidad</th>
  </tr>
  <tr bgcolor="#1F497D">
    <th>CONTROL DE ENFERMEDADES DE TRIPULACION DEL B/M: ' . $barco_name . ' ' . $viaje_name . '</th>
  </tr>
</table>';
$pdf->writeHTML($html, true, 0, true, true);
$pdf->Ln(1, FALSE);
$pdf->SetFont('times', '', 10);
$pdf->Ln(1, FALSE);
$pdf->SetFont('times', '', 7);
$html = '
<style>
table, th, td {
    border-collapse: collapse;
    padding: 4px;
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
    <th class="pull-center" width="30%"><strong>NOMBRE</strong></th>
    <th class="pull-center" width="9%"><strong>B/M</strong></th>
    <th class="pull-center" width="14%"><strong>PUESTO</strong></th>
    <th class="pull-center" width="9%"><strong>ÚLTIMO</strong></th>
    <th class="pull-center" width="9%"><strong>ESTADO</strong></th>
    <th class="pull-center" width="13%"><strong>VENCIMIENTO</strong></th>
    <th class="pull-center" width="16%"><strong>STATUS</strong></th>
  </tr>';
/*
 * Tripulantes Query
*/
$query2 = db_select('node', 'n'); // Viaje NID
$query2->leftJoin('field_data_field_barco_viaje', 'b_tid', 'b_tid.entity_id = n.nid');
$query2->leftJoin('node', 'b_nid', 'b_nid.nid = b_tid.field_barco_viaje_target_id');
$query2->leftJoin('field_data_field_nombre_barco', 'n_tid', 'n_tid.entity_id = b_nid.nid');
$query2->leftJoin('taxonomy_term_data', 'bar_tax', 'bar_tax.tid = n_tid.field_nombre_barco_tid');
$query2->leftJoin('field_data_field_tripulacion_viaje', 'trip', 'trip.entity_id = n.nid');
$query2->leftJoin('field_collection_item', 'fc', 'fc.item_id = trip.field_tripulacion_viaje_value');
$query2->leftJoin('field_data_field_nombre_tripulante', 'nom', 'nom.entity_id = fc.item_id');
$query2->leftJoin('node', 't', 't.nid = nom.field_nombre_tripulante_target_id'); // Tripulante NID
// Content Type Salud
$query2->leftJoin('field_data_field_tripulante_salud', 'salud_tid', 
    'salud_tid.field_tripulante_salud_target_id = t.nid');
$query2->leftJoin('node', 'sal', 'sal.nid = salud_tid.entity_id');
$query2->leftJoin('field_data_field_fecha_analisis_salud', 'ult', 'ult.entity_id = sal.nid'); // Ultimo Analisis
$query2->leftJoin('field_data_field_fecha_vencim_salud', 'ven', 'ven.entity_id = sal.nid'); // Vencimiento Analisis
$query2->leftJoin('field_data_field_edo_de_salud_salud', 'edo_tid', 'edo_tid.entity_id = sal.nid');
$query2->leftJoin('field_data_field_diagnostico_salud', 'st_sal', 'st_sal.entity_id = sal.nid'); // Ssatus
$query2->leftJoin('taxonomy_term_data', 'edo_tax', 'edo_tax.tid = edo_tid.field_edo_de_salud_salud_tid');
$query2->addField('salud_tid', 'field_tripulante_salud_target_id', 'saludtid');
$query2->addField('edo_tax', 'name', 'estado');
$query2->addField('ult', 'field_fecha_analisis_salud_value', 'ultimo'); // Ultimo Analisis
$query2->addField('ven', 'field_fecha_vencim_salud_value', 'vencimiento'); // Vencimiento Analisis
$query2->addField('st_sal', 'field_diagnostico_salud_value', 'status'); // Status
$query2->addField('st_sal', 'entity_id', 'status_id');
// Nombre del Tripulante
$query2->leftJoin('field_data_field_nombre_personal', 'nombre_join', 'nombre_join.entity_id = t.nid');
$query2->leftJoin('field_data_field_ap_paterno_personal', 'paterno_join', 'paterno_join.entity_id = t.nid');
$query2->leftJoin('field_data_field_ap_materno_personal', 'materno_join', 'materno_join.entity_id = t.nid');
$query2->leftJoin('field_data_field_puesto_personal', 'puesto_nid_join', 'puesto_nid_join.entity_id = fc.item_id');
/*
 * Taxonomies
 */
$query2->leftJoin('taxonomy_term_data', 'puesto_tax_join', 
    'puesto_tax_join.tid = puesto_nid_join.field_puesto_personal_tid');
$query2->leftJoin('field_data_field_jerarquia_trip', 'orden_tax_join', 
    'orden_tax_join.entity_id = puesto_tax_join.tid');
$query2->leftJoin('field_data_field_categoria_personal', 'cate_nid_join', 
    'cate_nid_join.entity_id = t.nid');
$query2->leftJoin('taxonomy_term_data', 'cate_tax_join', 
    'cate_tax_join.tid = cate_nid_join.field_categoria_personal_tid');
$query2->leftJoin('field_data_field_folio_personal', 'folio_nid_join', 'folio_nid_join.entity_id = t.nid');
$query2->leftJoin('field_data_field_vigencia_personal', 'vigen_nid_join', 'vigen_nid_join.entity_id = t.nid');
$query2->leftJoin('field_data_field_observaciones_viaje', 'obser_nid_join', 'obser_nid_join.entity_id = n.nid');
$query2->leftJoin('field_data_field_escolaridad_personal', 'esco_nid_join', 'esco_nid_join.entity_id = fc.item_id');
$query2->leftJoin('taxonomy_term_data', 'esco_tax_join', 
    'esco_tax_join.tid = esco_nid_join.field_escolaridad_personal_tid');
/*
 * Ing de Puerto
 */
$query2->leftJoin('field_data_field_ing_puerto_viaje', 'join_ing_puerto', 'join_ing_puerto.entity_id = n.nid');
$query2->leftJoin('users', 'join_user', 'join_user.uid = join_ing_puerto.field_ing_puerto_viaje_target_id');
$query2->leftJoin('field_data_field_nombre_account', 'join_ing_puerto_nombre', 
    'join_ing_puerto_nombre.entity_id = join_ing_puerto.field_ing_puerto_viaje_target_id');
$query2->leftJoin('field_data_field_ap_paterno_account', 'join_ing_puerto_paterno', 
    'join_ing_puerto_paterno.entity_id = join_ing_puerto.field_ing_puerto_viaje_target_id');
$query2->leftJoin('field_data_field_ap_materno_account', 'join_ing_puerto_materno', 
    'join_ing_puerto_materno.entity_id = join_ing_puerto.field_ing_puerto_viaje_target_id');
/*
 * Capitán
 */
$query2->leftJoin('field_data_field_jefe_flota_viaje', 'join_jefe_flota', 'join_jefe_flota.entity_id = n.nid');
$query2->leftJoin('users', 'join_user2', 'join_user2.uid = join_jefe_flota.field_jefe_flota_viaje_target_id');
$query2->leftJoin('field_data_field_nombre_account', 'join_jefe_flota_nombre', 
    'join_jefe_flota_nombre.entity_id = join_jefe_flota.field_jefe_flota_viaje_target_id');
$query2->leftJoin('field_data_field_ap_paterno_account', 'join_jefe_flota_paterno', 
    'join_jefe_flota_paterno.entity_id = join_jefe_flota.field_jefe_flota_viaje_target_id');
$query2->leftJoin('field_data_field_ap_materno_account', 'join_jefe_flota_materno', 
    'join_jefe_flota_materno.entity_id = join_jefe_flota.field_jefe_flota_viaje_target_id');
/*
 * Fields
 */
$query2->addField('n', 'nid', 'viaje_n');
$query2->addField('n', 'title', 'title_n');
$query2->addField('b_nid', 'title', 'barco');
$query2->addField('trip', 'entity_id', 'trip_eid');
$query2->addField('trip', 'field_tripulacion_viaje_value', 'trip_tarid');
$query2->addField('fc', 'item_id', 'item_id');
$query2->addField('nom', 'entity_id', 'nom_eid');
$query2->addField('nom', 'field_nombre_tripulante_target_id', 'nom_tarid');
$query2->addField('t', 'nid', 't_nid');
$query2->addField('t', 'title', 't_title');
$query2->addField('nombre_join', 'field_nombre_personal_value', 'nombre');
$query2->addField('paterno_join', 'field_ap_paterno_personal_value', 'ap_paterno');
$query2->addField('materno_join', 'field_ap_materno_personal_value', 'ap_materno');
$query2->addField('puesto_tax_join', 'name', 'puesto_name');
$query2->addField('puesto_tax_join', 'tid', 'puesto_tid');
$query2->addField('cate_tax_join', 'name', 'cate_name');
$query2->addField('folio_nid_join', 'field_folio_personal_value', 'docu_name');
$query2->addField('vigen_nid_join', 'field_vigencia_personal_value', 'vigen_name');
$query2->addField('esco_tax_join', 'name', 'esco_name');
$query2->addField('join_user', 'name', 'ing_puerto_name');
// Ing de Puerto
$query2->addField('join_ing_puerto_nombre', 'field_nombre_account_value', 'ing_puerto_nombre');
$query2->addField('join_ing_puerto_paterno', 'field_ap_paterno_account_value', 'ing_puerto_paterno');
$query2->addField('join_ing_puerto_materno', 'field_ap_materno_account_value', 'ing_puerto_materno');
// Capitán
$query2->addField('join_jefe_flota_nombre', 'field_nombre_account_value', 'jefe_flota_nombre');
$query2->addField('join_jefe_flota_paterno', 'field_ap_paterno_account_value', 'jefe_flota_paterno');
$query2->addField('join_jefe_flota_materno', 'field_ap_materno_account_value', 'jefe_flota_materno');
$query2->addField('obser_nid_join', 'field_observaciones_viaje_value', 'obser_viaje');
$query2->addField('orden_tax_join', 'field_jerarquia_trip_value', 'order_jerarquia');
$query2->condition('n.nid', $n, '=');
$query2->addExpression('MAX(ult.field_fecha_analisis_salud_value)', 'max_ult');
$query2->addExpression('MAX(ven.field_fecha_vencim_salud_value)', 'max_ven');
$query2->groupBy('t.nid');
$query2->orderBy('orden_tax_join.field_jerarquia_trip_value', 'ASC');
$exeResults2 = $query2->execute();
$results2 = $exeResults2->fetchAll();
foreach ($results2 as $result2) {
  //$x += 1;
  if (!empty($result2->vigen_name)) {
    $date = new DateTime($result2->vigen_name);
    $vigencia = $date->format('d/m/Y');
  } else {
    $vigencia = '';
  } 
  if (!empty($result2->max_ult)) {
    //$date = new DateTime($result2->vigen_name);
    $ult = new DateTime($result2->max_ult);
    $ultimo = $ult->format('d/m/Y');
  } else {
    $ultimo = 'N/A';
  }
  if (!empty($result2->max_ven)) {
    //$date = new DateTime($result2->vigen_name);
    $ven = new DateTime($result2->max_ven);
    $vencimiento = $ven->format('d/m/Y');
  } else {
    $vencimiento = 'Próxima Estadía';
  }
  
  $estado = !empty($result2->estado) ?  $result2->estado : 'N/A';
  $status = !empty($result2->status) ? $result2->status : 'N/A';
  $puesto = $result2->puesto_name;
  $puesto_tid = $result2->puesto_tid;
  
  // Get Capitan
  if ( $puesto_tid == 1298) {
    $capitan = $result2->nombre . ' ' . $result2->ap_paterno . ' ' . $result2->ap_materno;
  }
  
  $html .= '<tr>';
  $html .= '<td class="pull-left">';
  $html .= $result2->nombre . ' ' . $result2->ap_paterno . ' ' . $result2->ap_materno;
  $html .= '</td>';
  $html .= '<td class="pull-center">'. $result2->barco .'</td>';
  $html .= '<td class="pull-center">' . $puesto . '</td>';
  $html .= '<td class="pull-center">' . $ultimo . '</td>'; 
  $html .= '<td class="pull-center">' . $estado . '</td>';
  $html .= '<td class="pull-center">' . $vencimiento . '</td>';
  $html .= '<td class="pull-center">' . ucfirst(strtolower($status)) . '</td>';
  $html .= '</tr>';
  $ing_puerto = $result2->ing_puerto_nombre . ' ' . $result2->ing_puerto_paterno
      . ' ' . $result2->ing_puerto_materno;
  $jefe_flota = $result2->jefe_flota_nombre . ' ' . $result2->jefe_flota_paterno
      . ' ' . $result2->jefe_flota_materno;
}
$html .= '</table>';
$html .= '<br><br>'
    . '<table>'
    . '<tr bgcolor="#dddddd">'
    . '<td width="100%"><strong>NOTAS:</strong></td>'
    . '</tr>'
    . '<tr>'
    . '<td width="4%">1. </td>'
    . '<td width="96%">La vigencia de los análisi es de 1 año, después de haber resultado sano a los exámenes realizados.</td>'
    . '</tr>'
    . '<tr>'
    . '<td width="4%">2. </td>'
    . '<td width="96%">A los tripulantes que sus exámenes medicos están VENCIDOS, NO TIENEN, resultaron POSITIVO, '
    . 'o que TOMARON TRATAMIENTO, favor de pasar a la oficina de Aseguramiento de Calidad (costado de taller de redes) '
    . 'a recoger el frasco para la toma de muestra</td>'
    . '</tr>'
    . '</table>'
    . '<br><br>';
$html .= '<table>'
    . '<tr bgcolor="#dddddd">'
    . '<td width="100%"><strong>Recibió Información:</strong></td>'
    . '</tr>'
    . '<tr>'
    . '<td width="20%">Capitán: </td>'
    . '<td width="80%">' . strtoupper($capitan) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td width="20%">Ingeniero de Puerto: </td>'
    . '<td width="80%">' . strtoupper($ing_puerto) . '</td>'
    . '</tr>'
    . '<tr>'
    . '<td width="20%">Jefe de Flota: </td>'
    . '<td width="80%">' . strtoupper($jefe_flota) . '</td>'
    . '</tr>'
    . '</table>';
$pdf->writeHTML($html, true, 0, true, true);
$html = '
<style>
.table2 {
  text-align: left;
  border-style: none;
  padding: 4px;
}
td, th {
  border: 1px solid #ffffff;
}
</style>';
$html .= '<div class="pos-table">'
    . '<table class="table2">'
    . '<tr><td width="100%" align="center"><a href="http://www.mazpesca.com" '
    . 'style="color: #B0B0B0; text-decoration: none; font-size: 14px;">'
    . 'www.mazpesca.com</a></td></tr>'
    . '</table>'
    . '</div>';
$pdf->writeHTMLCell($w=0, $h=0, $x=20, $y=260, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$pdf->Ln();
//Close and output PDF document
$pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/' .$file, 'F');
drupal_set_message(t('El archivo PDF se ha generado exitosamente y se envió a su correo.'));
?>
