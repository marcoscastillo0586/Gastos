<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property LugarModel $l
 * @property MovimientoModel $m
 * @property EgresoModel $e
 */
class Movimiento extends CI_Controller {
		   public function __construct(){
        parent::__construct();
              $this->load->model('LugarModel','l',true);
              $this->load->model('MovimientoModel','m',true);
              $this->load->model('EgresoModel','e',true);
        }
        
	public function index()
	{
    $datos['Lugares']     = $this->l->darLugar();
    $datos_page['titulo']   = 'Ver Movimientos';
  	$datos_page['page']   = $this->load->view('movimiento/body',$datos, true);
    $this->load->view('menu/header');
    $this->load->view('movimiento/header');
    $this->load->view('menu/body',$datos_page, false);
    $this->load->view('menu/footer');
    $this->load->view('movimiento/footer');
	}

	public function darMovimientos(){
    $desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
    $categoria = $_POST['categoria'];
    $lugar = !isset($_POST['lugar']) ? '0' : $_POST['lugar'];
    $orderBy = !isset($_POST['orderBy']) ? 'me.fecha_movimiento DESC ' : $_POST['orderBy'];

    $movimientos = $this->m->darHistorial($desde,$hasta,$categoria,$lugar,$orderBy);

    if ($movimientos){
    
    $arrayFechas=[];       
    foreach ($movimientos as $key => $value){
          array_push($arrayFechas, $value->fecha);
       }
    $fechaMinima = min($arrayFechas);
    $fechaMaxima = max($arrayFechas);

    $movimientosDetalle = $this->m->darMovimientosDetalle($fechaMinima,$fechaMaxima);
    $renglon=''; 
    $html='';
    $suma = 0;
    $sumadetalle = 0;
    foreach ($movimientos as $key => $value){
      //acomodo la fecha 
      $f = explode("-",$value->fecha);
      $fecha = $f[2]."-".$f[1]."-".$f[0];
	  	$key++;
	  	$html.='<tr class="text-light bg-dark">
            	<td class="custom-td-height">'.$fecha.'</td>
            	<td class="custom-td-height">'.$value->lugar.'</td>
            	<td class="custom-td-height">'.$value->concepto_general.'</td>';
      $detalle='';
      $ultimoRenglon='';
      foreach ($movimientosDetalle as $keyDet => $valueDet){
      
      if ($categoria=='0'){
        if ($value->id_movimiento_enc == $valueDet->movimiento){ 
          $sumadetalle = (floatval($value->monto)) + ($suma);

          
          $ultimoRenglon='<td class="custom-td-height">'.$this->your_money_format($value->monto).' <i class="fas fa-angle-double-down mostrarmas" style="cursor: pointer;" data-id='.$value->id_movimiento_enc.'></i><i class="fas fa-angle-double-up mostrarmas up_'.$value->id_movimiento_enc.'" style="cursor: pointer;display:none" "></i></td>';
          $detalle.='
                    <tr class="mitdoculto_'.$value->id_movimiento_enc.' text-dark" style="display: none;">
                    	<td class="custom-td-height"></td>
                    	<td class="custom-td-height">'.$valueDet->categoria.'</td>
                    	<td class="custom-td-height">'.$valueDet->concepto.'</td>
                    	<td class="custom-td-height">'.$this->your_money_format($valueDet->monto).'</td>
                    </tr>';
        }
      }else{
        
        if ($value->id_movimiento_enc == $valueDet->movimiento){ 
          $sumadetalle = (floatval($value->monto)) + ($suma);
          $ultimoRenglon='<td class="custom-td-height">'.$this->your_money_format($value->monto).' <i class="fas fa-angle-double-down mostrarmas" style="cursor: pointer;" data-id='.$value->id_movimiento_enc.'></i><i class="fas fa-angle-double-up mostrarmas up_'.$value->id_movimiento_enc.'" style="cursor: pointer;display:none" "></i></td>';
          
            // Convertir el string de categorías permitidas a un array
            $categorias_permitidas = explode(',', $categoria);

            // Eliminar los espacios en blanco de cada elemento del array
            $categorias_permitidas = array_map('trim', $categorias_permitidas);

            // Comprobar si el valor está dentro del array de categorías permitidas
            if (in_array($valueDet->id_categoria, $categorias_permitidas)) {
            
          $detalle.='
                    <tr class="mitdoculto_'.$value->id_movimiento_enc.' text-dark" style="display: none;">
                      <td class="custom-td-height"></td>
                      <td class="custom-td-height">'.$valueDet->categoria.'</td>
                      <td class="custom-td-height">'.$valueDet->concepto.'</td>
                      <td class="custom-td-height">'.$this->your_money_format($valueDet->monto).'</td>
                    </tr>';
          }
        }
      }

      }


      if (empty($detalle))
      {  
         $suma = (floatval($value->monto)) + ($suma);
         $ultimoRenglon='<td>'.$this->your_money_format($value->monto).'</td>';
      }
      else{$suma= $sumadetalle; $ultimoRenglon.=$detalle;}
        $html.= $ultimoRenglon;
		}       

    if($suma < 0) $suma = $suma *-1;
    $sumaFormateada = $this->your_money_format($suma);
  
     if ($categoria!=='0') {
      $html.='<tr><th colspan=3 style="text-align:center">TOTAL:</th><td> '.$sumaFormateada.'</td>';
     }

	  echo $html;
  }
  else{
    echo "Sin Registros";
  }
  
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
  public function your_money_format($value) {  return '$ ' . number_format( $value,$decimals = 2,$dec_point = ",",$thousands_sep = "."); }  
}
