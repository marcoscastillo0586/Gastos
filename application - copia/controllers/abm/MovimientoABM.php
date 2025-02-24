<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property LugarModel $l
 * @property MovimientoModel $m
 * @property EgresoModel $e
 */
class MovimientoABM extends CI_Controller {
		   public function __construct(){
        parent::__construct();
              $this->load->model('LugarModel','l',true);
              $this->load->model('MovimientoModel','m',true);
              $this->load->model('EgresoModel','e',true);
        }
        
	public function index()
	{
    $datos['Lugares']     = $this->l->darLugar();
    $datos_page['titulo'] = 'ABM de Movimientos';
  	$datos_page['page']   = $this->load->view('abm/movimiento/body',$datos, true);
    $this->load->view('menu/header');
    $this->load->view('abm/movimiento/header');
    $this->load->view('menu/body',$datos_page, false);
    $this->load->view('menu/footer');
    $this->load->view('abm/movimiento/footer');
	}

public function darMovimientos(){
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $categoria = $_POST['categoria'];
    $lugar = !isset($_POST['lugar']) ? '0' : $_POST['lugar'];
    $orderBy = !isset($_POST['orderBy']) ? 'me.fecha_movimiento DESC ' : $_POST['orderBy'];

    $movimientos = $this->m->darHistorial($desde, $hasta, $categoria, $lugar, $orderBy);

    if ($movimientos){
        // Obtener las fechas mínimas y máximas
        $arrayFechas = array_map(fn($mov) => $mov->fecha, $movimientos);
        $fechaMinima = min($arrayFechas);
        $fechaMaxima = max($arrayFechas);
        $movimientosDetalle = $this->m->darMovimientosDetalle($fechaMinima, $fechaMaxima);

        $html = '';
        $suma = 0;

        // Contar detalles por cada encabezado
        $detallesPorEncabezado = [];
        foreach ($movimientosDetalle as $det) {
            if (!isset($detallesPorEncabezado[$det->movimiento])) {
                $detallesPorEncabezado[$det->movimiento] = 0;
            }
            $detallesPorEncabezado[$det->movimiento]++;
        }

        foreach ($movimientos as $mov) {
            $fecha = date("d-m-Y", strtotime($mov->fecha)); // Formatear fecha

            // Fila principal (NO editable)
            $html .= '<tr class="text-light bg-dark " data-id="'.$mov->id_movimiento_enc.'" data-tipo="encabezado">
                <td>'.$fecha.'</td>
                <td>'.$mov->lugar.'</td>
                <td class="editable" data-columna="concepto_general">'.$mov->concepto_general.'</td>
                <td>'.$mov->monto.' <i class="fas fa-angle-double-down mostrarmas" style="cursor: pointer;" data-id="'.$mov->id_movimiento_enc.'"></i></td>';

            // Mostrar el ícono de eliminar solo si hay más de un detalle
            $html .= '<td class="text-center">';
                $html .= '<i class="fas fa-trash-alt text-danger eliminar" style="cursor: pointer;" data-id="'.$mov->id_movimiento_enc.'"></i>';
            
            $html .= '</td></tr>';

            // Filas secundarias (Editables)
            foreach ($movimientosDetalle as $det) {
                if ($mov->id_movimiento_enc == $det->movimiento) {
                    $html .= '<tr class="detalle oculto_'.$mov->id_movimiento_enc.'" style="display: none;" data-id="'.$det->id_movimiento_detalle.'" data-tipo="detalle">
                        <td></td>
                        <td >'.$det->categoria.'</td>
                        <td class="editable" data-columna="concepto">'.$det->concepto.'</td>
                        <td class="editable" data-columna="monto">'.$det->monto.'</td>';
                    
                    // Mostrar el ícono de eliminar solo si hay más de un detalle
                    $html .= '<td class="text-center">';
                    if ($detallesPorEncabezado[$mov->id_movimiento_enc] > 1) {
                        $html .= '<i class="fas fa-trash-alt text-danger eliminar" style="cursor: pointer;" data-id="'.$det->id_movimiento_detalle.'"></i>';
                    }
                    $html .= '</td></tr>';
                }
            }
        }

        echo $html;
    } else {
        echo "<tr><td colspan='5' class='text-center'>Sin Registros</td></tr>";
    }
}


// Método para actualizar la celda editada
public function actualizarMovimiento() {
    $idTabla = $this->input->post("idTabla");
   
    $columna = trim($this->input->post("columna"));
    $nuevoValor = $this->input->post("nuevoValor");

   if ($columna=='concepto_general') {

       $where = "id_movimiento_enc = $idTabla";
       $update = $this->m->actualizar('movimiento_enc',$columna, $nuevoValor,$where);
    }
    else{
       $where = "id_movimiento_det = $idTabla";
       $update = $this->m->actualizar('movimiento_det',$columna,$nuevoValor,$where);
    }
  
    echo json_encode($update);
}
	
  public function darMovimientosFiltro(){
  
     $categoria = $_POST['categoria'];

      $lugar = !isset($_POST['lugar'])? '0' : $_POST['lugar'];
     
     if ($lugar==0){
      $movimientos = $this->m->darMovimientosCategoria($categoria);
       
     }else{
          $lugarString= implode ( ',' , $lugar );
          $movimientos = $this->m->darMovimientosCategoriaLugar($categoria,$lugarString);
     }
      $renglon=''; 
      $html='';
      foreach ($movimientos as $key => $value){
        $f = explode("-",$value->fecha);
        $fecha = $f[2]."-".$f[1]."-".$f[0];
        $key++;
        $html.='<tr>
              <th scope="row">'.$key.'</th>
              <td>'.$fecha.'</td>
              <td>'.$value->lugar.'</td>
              <td>'.$value->concepto.'</td>';
      //$detalle='';
        $ultimoRenglon='';
        if (empty($detalle)){$ultimoRenglon='<td>'.$value->monto.'</td>';}else{$ultimoRenglon.=$detalle;}
        $html.= $ultimoRenglon;
      }       
  echo $html;
  }
   public function darCategoriasJS(){
      $res = $this->e->darCategoria();
      $html='<option value="0">MOSTRAR TODAS</option>';
      foreach ($res as $key => $value) {
       // if (($value->id_categoria !=='1') && ($value->id_categoria !=='2')){
          $html.='<option value="'.$value->id_categoria.'">'.$value->nombre.'</option>';
        //}
      }
        echo json_encode($html);
    }  
    public function darLugarJS(){
      $res = $this->e->darLugares();
      $html='<option value="0">MOSTRAR TODAS</option>';
      foreach ($res as $key => $value) {
          $html.='<option value="'.$value->id_lugar.'">'.$value->nombre.'</option>';
        
      }
        echo json_encode($html);
    }
  function your_money_format($value) {  return '$' . number_format( $value,$decimals = 2,$dec_point = ",",$thousands_sep = "."); }  

public function eliminarMovimiento() {
    $id = $_POST['id'];
    $tipo = $_POST['tipo']; // 'encabezado' o 'detalle'
    
    if ($tipo === 'encabezado') {
        // Eliminar encabezado y todos sus detalles
   $res = $this->m->eliminarEncyDet($id);
    } else {
        // Eliminar solo el detalle específico
        $res = $this->m->eliminarDet($id);
    }

    echo json_encode($res);
}


}
