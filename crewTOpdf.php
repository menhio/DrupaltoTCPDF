<?php
$output = '';
  /*
   * Get Barcos ID
   */
  
  function getBarcoETA($barco_id) {
    
  $query = db_select('node', 'b');
  
  /*
   * Join Viaje - Barco
   */
  $query->leftJoin('field_data_field_barco_viaje', 'jv', 
      'jv.field_barco_viaje_target_id = b.nid');
  
  $query->leftJoin('node', 'v', 
      'v.nid = jv.entity_id');
  $query->addField('v', 'type', 'type');
  $query->addField('v', 'nid', 'nid');
  /*
   * Join Viaje - Fecha de Zarpe
   */
  $query->leftJoin('field_data_field_fecha_zarpe_viaje', 'jz', 
      'jz.entity_id = v.nid');
  
  /*
   * Join Viaje - Fecha de Arribo
   */
  $query->leftJoin('field_data_field_fecha_arribo', 'ja', 
      'ja.entity_id = v.nid');
  
  $query->addField('b', 'title', 'barco');
  
  $query->addField('jz', 'entity_id', 'id_zarpe');
  
  $query->addField('jz', 'field_fecha_zarpe_viaje_value', 'zarpe');
  $query->addField('ja', 'field_fecha_arribo_value', 'arribo');
  $query->addField('b', 'nid', 'bnid');
  
  $query->condition('v.type', 'viaje', '=');
  $query->condition('b.nid', $barco_id, '=');
  $query->orderBy('v.nid', 'DESC');
  $query->range(0,1);
  
  $exeResults = $query->execute();
  $results = $exeResults->fetchAll();
  
  foreach ($results as $result) {
    date_default_timezone_set("America/Mazatlan");
    $date1 = new DateTime($result->zarpe);
    $date2 = new DateTime($result->arribo);
    $fecha_zarpe = $date1->format('d-M-y');
    $fecha_arribo = $date2->format('d-M-y');
    $barco_name = $result->barco;
    $today = date('d-m-Y');
    $str = strtotime($fecha_arribo) - strtotime($today);
    $eta = floor($str/3600/24);

  }
  
    
    return compact('barco_name', 'fecha_zarpe', 'fecha_arribo', 'eta');
  }
  /*
   * Azteca 1
   */
  $azteca1BarcoETA = getBarcoETA(262);
  $azteca1_name = $azteca1BarcoETA['barco_name'];
  $azteca1_zarpe = $azteca1BarcoETA['fecha_zarpe'];
  $azteca1_arribo = $azteca1BarcoETA['fecha_arribo'];
  $azteca1ETA = $azteca1BarcoETA['eta'];
  
  /*
   * Azteca 2
   */
  $azteca2BarcoETA = getBarcoETA(392);
  $azteca2_name = $azteca2BarcoETA['barco_name'];
  $azteca2_zarpe = $azteca2BarcoETA['fecha_zarpe'];
  $azteca2_arribo = $azteca2BarcoETA['fecha_arribo'];
  $azteca2ETA = $azteca2BarcoETA['eta'];
  /*
   * Azteca 3
   */
  $azteca3BarcoETA = getBarcoETA(394);
  $azteca3_name = $azteca3BarcoETA['barco_name'];
  $azteca3_zarpe = $azteca3BarcoETA['fecha_zarpe'];
  $azteca3_arribo = $azteca3BarcoETA['fecha_arribo'];
  $azteca3ETA = $azteca3BarcoETA['eta'];
  /*
   * Azteca 4
   */
  $azteca4BarcoETA = getBarcoETA(395);
  $azteca4_name = $azteca4BarcoETA['barco_name'];
  $azteca4_zarpe = $azteca4BarcoETA['fecha_zarpe'];
  $azteca4_arribo = $azteca4BarcoETA['fecha_arribo'];
  $azteca4ETA = $azteca4BarcoETA['eta'];
  /*
   * Azteca 5
   */
  $azteca5BarcoETA = getBarcoETA(396);
  $azteca5_name = $azteca5BarcoETA['barco_name'];
  $azteca5_zarpe = $azteca5BarcoETA['fecha_zarpe'];
  $azteca5_arribo = $azteca5BarcoETA['fecha_arribo'];
  $azteca5ETA = $azteca5BarcoETA['eta'];
  /*
   * Azteca 6
   */
  $azteca6BarcoETA = getBarcoETA(397);
  $azteca6_name = $azteca6BarcoETA['barco_name'];
  $azteca6_zarpe = $azteca6BarcoETA['fecha_zarpe'];
  $azteca6_arribo = $azteca6BarcoETA['fecha_arribo'];
  $azteca6ETA = $azteca6BarcoETA['eta'];
  /*
   * Azteca 7
   */
  $azteca7BarcoETA = getBarcoETA(398);
  $azteca7_name = $azteca7BarcoETA['barco_name'];
  $azteca7_zarpe = $azteca7BarcoETA['fecha_zarpe'];
  $azteca7_arribo = $azteca7BarcoETA['fecha_arribo'];
  $azteca7ETA = $azteca7BarcoETA['eta'];
  /*
   * Azteca 8
   */
  $azteca8BarcoETA = getBarcoETA(258);
  $azteca8_name = $azteca8BarcoETA['barco_name'];
  $azteca8_zarpe = $azteca8BarcoETA['fecha_zarpe'];
  $azteca8_arribo = $azteca8BarcoETA['fecha_arribo'];
  $azteca8ETA = $azteca8BarcoETA['eta'];
  /*
   * Azteca 9
   */
  $azteca9BarcoETA = getBarcoETA(399);
  $azteca9_name = $azteca9BarcoETA['barco_name'];
  $azteca9_zarpe = $azteca9BarcoETA['fecha_zarpe'];
  $azteca9_arribo = $azteca9BarcoETA['fecha_arribo'];
  $azteca9ETA = $azteca9BarcoETA['eta'];
  /*
   * Azteca 10
   */
  $azteca10BarcoETA = getBarcoETA(393);
  $azteca10_name = $azteca10BarcoETA['barco_name'];
  $azteca10_zarpe = $azteca10BarcoETA['fecha_zarpe'];
  $azteca10_arribo = $azteca10BarcoETA['fecha_arribo'];
  $azteca10ETA = $azteca10BarcoETA['eta'];
  /*
   * Camila
   */
  $camilaBarcoETA = getBarcoETA(405);
  $camila_name = $camilaBarcoETA['barco_name'];
  $camila_zarpe = $camilaBarcoETA['fecha_zarpe'];
  $camila_arribo = $camilaBarcoETA['fecha_arribo'];
  $camilaETA = $camilaBarcoETA['eta'];
  /*
   * Clipperton
   */
  $clippertonBarcoETA = getBarcoETA(402);
  $clipperton_name = $clippertonBarcoETA['barco_name'];
  $clipperton_zarpe = $clippertonBarcoETA['fecha_zarpe'];
  $clipperton_arribo = $clippertonBarcoETA['fecha_arribo'];
  $clippertonETA = $clippertonBarcoETA['eta'];
  /*
   * El Dorado
   */
  $eldoradoBarcoETA = getBarcoETA(401);
  $eldorado_name = $eldoradoBarcoETA['barco_name'];
  $eldorado_zarpe = $eldoradoBarcoETA['fecha_zarpe'];
  $eldorado_arribo = $eldoradoBarcoETA['fecha_arribo'];
  $eldoradoETA = $eldoradoBarcoETA['eta'];
  /*
   * Franz
   */
  $franzBarcoETA = getBarcoETA(403);
  $franz_name = $franzBarcoETA['barco_name'];
  $franz_zarpe = $franzBarcoETA['fecha_zarpe'];
  $franz_arribo = $franzBarcoETA['fecha_arribo'];
  $franzETA = $franzBarcoETA['eta'];
  /*
   * Hanna
   */
  $hannaBarcoETA = getBarcoETA(404);
  $hanna_name = $hannaBarcoETA['barco_name'];
  $hanna_zarpe = $hannaBarcoETA['fecha_zarpe'];
  $hanna_arribo = $hannaBarcoETA['fecha_arribo'];
  $hannaETA = $hannaBarcoETA['eta'];
  /*
   * Mazatun
   */
  $mazatunBarcoETA = getBarcoETA(400);
  $mazatun_name = $mazatunBarcoETA['barco_name'];
  $mazatun_zarpe = $mazatunBarcoETA['fecha_zarpe'];
  $mazatun_arribo = $mazatunBarcoETA['fecha_arribo'];
  $mazatunETA = $mazatunBarcoETA['eta'];
  /*
   * Mazpesca 2
   */
  $mazpesca2BarcoETA = getBarcoETA(275);
  $mazpesca2_name = $mazpesca2BarcoETA['barco_name'];
  $mazpesca2_zarpe = $mazpesca2BarcoETA['fecha_zarpe'];
  $mazpesca2_arribo = $mazpesca2BarcoETA['fecha_arribo'];
  $mazpesca2ETA = $mazpesca2BarcoETA['eta'];
  /*
   * Paco C
   */
  $pacocBarcoETA = getBarcoETA(406);
  $pacoc_name = $pacocBarcoETA['barco_name'];
  $pacoc_zarpe = $pacocBarcoETA['fecha_zarpe'];
  $pacoc_arribo = $pacocBarcoETA['fecha_arribo'];
  $pacocETA = $pacocBarcoETA['eta'];
  /*
   * Tamara
   */
  $tamaraBarcoETA = getBarcoETA(259);
  $tamara_name = $tamaraBarcoETA['barco_name'];
  $tamara_zarpe = $tamaraBarcoETA['fecha_zarpe'];
  $tamara_arribo = $tamaraBarcoETA['fecha_arribo'];
  $tamaraETA = $tamaraBarcoETA['eta'];
  /*
   * Titis
   */
  $titisBarcoETA = getBarcoETA(407);
  $titis_name = $titisBarcoETA['barco_name'];
  $titis_zarpe = $titisBarcoETA['fecha_zarpe'];
  $titis_arribo = $titisBarcoETA['fecha_arribo'];
  $titisETA = $titisBarcoETA['eta'];
  
  $output .= '<div class="table-responsive">';
  $output .= '
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th align="left">Barco</td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-1-2">' . $azteca1_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-2-0">' . $azteca2_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-3-0">' . $azteca3_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-4-0">' . $azteca4_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-5-0">' . $azteca5_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-6-0">' . $azteca6_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-7-0">' . $azteca7_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-8-2">' . $azteca8_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-9-0">' . $azteca9_name . '</a></td>
          <th class="text-center"><a href="http://mazpesca.com/barcos/azteca-10-0">' . $azteca10_name . '</a></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th align="left">Zarpe</td>
          <td align="middle">'. $azteca1_zarpe .'</td>
          <td align="middle">'. $azteca2_zarpe .'</td>
          <td align="middle">'. $azteca3_zarpe .'</td>
          <td align="middle">'. $azteca4_zarpe .'</td>
          <td align="middle">'. $azteca5_zarpe .'</td>
          <td align="middle">'. $azteca6_zarpe .'</td>
          <td align="middle">'. $azteca7_zarpe .'</td>
          <td align="middle">'. $azteca8_zarpe .'</td>
          <td align="middle">'. $azteca9_zarpe .'</td>
          <td align="middle">'. $azteca10_zarpe .'</td>
        </tr>
        <tr>
          <th align="left">Arribo</td>
          <td align="middle">'. $azteca1_arribo .'</td>
          <td align="middle">'. $azteca2_arribo .'</td>
          <td align="middle">'. $azteca3_arribo .'</td>
          <td align="middle">'. $azteca4_arribo .'</td>
          <td align="middle">'. $azteca5_arribo .'</td>
          <td align="middle">'. $azteca6_arribo .'</td>
          <td align="middle">'. $azteca7_arribo .'</td>
          <td align="middle">'. $azteca8_arribo .'</td>
          <td align="middle">'. $azteca9_arribo .'</td>
          <td align="middle">'. $azteca10_arribo .'</td>
        </tr>
        <tr>
          <th align="left">ETA</td>
          <td align="middle">'. $azteca1ETA . '</td>
          <td align="middle">'. $azteca2ETA . '</td>
          <td align="middle">'. $azteca3ETA . '</td>
          <td align="middle">'. $azteca4ETA . '</td>
          <td align="middle">'. $azteca5ETA . '</td>
          <td align="middle">'. $azteca6ETA . '</td>
          <td align="middle">'. $azteca7ETA . '</td>
          <td align="middle">'. $azteca8ETA . '</td>
          <td align="middle">'. $azteca9ETA . '</td>
          <td align="middle">'. $azteca10ETA . '</td>
        </tr>
      </tbody>
    </table>
    <table class="table table-striped table-hover">
      <thead>
      <tr>
        <th align="left">Barco</td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/camila-0">' . $camila_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/clipperton-0">' . $clipperton_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/el-dorado-0">' . $eldorado_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/franz-0">' . $franz_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/hanna-0">' . $hanna_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/mazatun-0">' . $mazatun_name . '</a></td>
        <th class="text-center"><a href="http://www.mazpesca.com/barcos/mazpesca-2-0">' . $mazpesca2_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/paco-c-0">' . $pacoc_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/tamara-0">' . $tamara_name . '</a></td>
        <th class="text-center"><a href="http://mazpesca.com/barcos/titis-0">' . $titis_name . '</a></td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <th align="left">Zarpe</td>
        <td align="middle">' . $camila_zarpe . '</td>
        <td align="middle">' . $clipperton_zarpe . '</td>
        <td align="middle">' . $eldorado_zarpe . '</td>
        <td align="middle">' . $franz_zarpe . '</td>
        <td align="middle">' . $hanna_zarpe . '</td>
        <td align="middle">' . $mazatun_zarpe . '</td>
        <td align="middle">' . $mazpesca2_zarpe . '</td>
        <td align="middle">' . $pacoc_zarpe . '</td>
        <td align="middle">' . $tamara_zarpe . '</td>
        <td align="middle">' . $titis_zarpe . '</td>
      </tr>
      <tr>
        <th align="left">Arribo</td>
        <td align="middle">' . $camila_arribo . ' </td>
        <td align="middle">' . $clipperton_arribo . '</td>
        <td align="middle">' . $eldorado_arribo . '</td>
        <td align="middle">' . $franz_arribo . '</td>
        <td align="middle">' . $hanna_arribo . '</td>
        <td align="middle">' . $mazatun_arribo . '</td>
        <td align="middle">' . $mazpesca2_arribo . '</td>
        <td align="middle">' . $pacoc_arribo . '</td>
        <td align="middle">' . $tamara_arribo . '</td>
        <td align="middle">' . $titis_arribo . '</td>
      </tr>
      <tr>
        <th align="left">ETA</td>
        <td align="middle">' . $camilaETA . '</td>
        <td align="middle">' . $clippertonETA . '</td>
        <td align="middle">' . $eldoradoETA . '</td>
        <td align="middle">' . $franzETA . '</td>
        <td align="middle">' . $hannaETA . '</td>
        <td align="middle">' . $mazatunETA . '</td>
        <td align="middle">' . $mazpesca2ETA . '</td>
        <td align="middle">' . $pacocETA . '</td>
        <td align="middle">' . $tamaraETA . '</td>
        <td align="middle">' . $titisETA . '</td>
      </tr>
    </tbody>
  </table>';
  $output .= '</div>';
  
  return $output;
?>
