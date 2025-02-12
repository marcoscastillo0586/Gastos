<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property MovimientoModel $m
 * @property TransferenciaModel $t
 */
class Transferencia extends CI_Controller {
	public function __construct(){
  	parent::__construct();
    $this->load->model('MovimientoModel','m',true);
    $this->load->model('TransferenciaModel','t',true);
	}

	public function index()
	{
				$datos['lugarOrigen']   = $this-> darLugarOrigen();
				$datos_page['page']   = $this->load->view('transferencia/body',$datos, true);
		    $this->load->view('menu/header');
		    $this->load->view('transferencia/header');
		    $this->load->view('menu/body',$datos_page, false);
		    $this->load->view('menu/footer');
		    $this->load->view('transferencia/footer');
	}

	public function darLugarOrigen(){
      $res = $this->t->darLugaresTransferencia();
      $html='<option></option>';
      foreach ($res as $key => $value) {
      	$html.='<option  value="'.$value->id_lugar.'">'.$value->nombre.'</option>';
      }
      	return $html;
  }

  public function darLugarOrigenJS(){
  	$res = $this->t->darLugaresTransferencia();
    $id_seleccion = $_POST['lugarSeleccionado'];
    $html='<option></option>';
	    foreach ($res as $key => $value) {
				if ($id_seleccion==$value->id_lugar) {
      		$html.='<option  style="text-decoration:line-through;background-color:#EAECF4" value="'.$value->id_lugar.'"disabled>'.$value->nombre.'</option>';
				}
				else{
      		$html.='<option style="cursor:pointer" value="'.$value->id_lugar.'">'.$value->nombre.'</option>';
				}
      }
  	echo json_encode($html);
  }



public function guardarTransferencia() {
    
    $id_origen = $_POST['id_origen']; 
    $id_destino = $this->input->post('id_destino');
    $montotransferir = $_POST['montotransferir'];
    $concepto = $this->input->post('concepto');
    $control = 1;
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $fecha_transferencia = date("Y-m-d H:i:s");

    // Guardar el movimiento
    $guardar_movimiento_enc = $this->t->guardarTransferencia($concepto, $fecha_transferencia);       

    if ($guardar_movimiento_enc == 1) {
        $ultimoMovimiento = $this->m->darUltimoMovEnc();
        $mov_det_ingreso = [
            'concepto' =>$concepto,
            'id_movimiento_enc' => $ultimoMovimiento->id_movimiento_enc,
            'monto' => $montotransferir,
            'id_lugar' => $id_destino,
            'id_categoria' => '1'
        ];
        $table = 'movimiento_det';
        $insertar_mov_det_ingreso = $this->t->insertarDatosTabla($mov_det_ingreso, $table);

        if ($insertar_mov_det_ingreso == 1) {
            $mov_det_egreso = [
              'concepto' =>$concepto,
                'id_movimiento_enc' => $ultimoMovimiento->id_movimiento_enc,
                'monto' => $montotransferir * -1,
                'id_lugar' => $id_origen,
                'id_categoria' => '1'
            ];
            $insertar_mov_det_egreso = $this->t->insertarDatosTabla($mov_det_egreso, $table);

            if ($insertar_mov_det_egreso == 0) {
                $control = 0;
            }
        } else {
            $control = 0;
        }
    } else {
        $control = 0;
    }

    echo json_encode($control);  // Enviar respuesta al cliente

}

 

  public function consultarSaldo(){
	  
    $monto 					 = $_POST['montotransferir'];
    $lugar 					 = $_POST['id_lugar'];


    $saldoDisponible  = $this->t->consultarSaldo($lugar);	   
   if ($saldoDisponible[0]->disponible >= $monto){
    	echo 1;
    }else{
    	echo 0;
    }
  }
}
