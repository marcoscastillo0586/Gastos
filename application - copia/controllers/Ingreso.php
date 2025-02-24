<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property IngresoModel $i
 */
class Ingreso extends CI_Controller {
		   public function __construct(){
        parent::__construct();
              $this->load->model('IngresoModel','i',true);
        }
	public function index()
	{
				$datos['lugares']   = $this-> darLugaresCardHtml();
		    $datos_page = [
        'page' => $this->load->view('ingreso/body', $datos, true),
        'titulo' => 'Registrar Ingreso'
    		];
		    $this->load->view('menu/header');
		    $this->load->view('ingreso/header');
		    $this->load->view('menu/body',$datos_page, false);
		    $this->load->view('menu/footer');
		    $this->load->view('ingreso/footer');
	}

	public function darLugaresCardHtml(){
     $res = $this->i->darLugares();
        $html = '';

        foreach ($res as $key => $value) {
        $imgUrl = base_url(htmlspecialchars($value->img, ENT_QUOTES, "UTF-8"));
       
        $html .= '
        <div class="col-md-2 col-sm-4 col-6 d-flex justify-content-center mb-1">
            <div class="card imgCard shadow-sm" style="width: 6.5rem; border-radius: 6px;" 
                 data-imgLugar="'.htmlspecialchars($value->id_lugar, ENT_QUOTES, "UTF-8").'" 
                 data-nombreLugar="'.htmlspecialchars($value->nombre, ENT_QUOTES, "UTF-8").'">
    
                <img src="' . base_url(htmlspecialchars($value->img, ENT_QUOTES, 'UTF-8')) . '" 
                    class="card-img-top" 
                    alt="' . htmlspecialchars($value->nombre, ENT_QUOTES, 'UTF-8') . '" 
                    style="width: 100%; height: 80px; object-fit: cover; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                
                <div class="card-body text-center p-1"> 
                    <h6 class="card-title text-uppercase font-weight-bold" style="font-size: 0.65rem; margin-bottom: 0;">
                        ' . htmlspecialchars($value->nombre, ENT_QUOTES, 'UTF-8') . '
                    </h6>
                </div>
            </div>
        </div>';
    
        }

     return $html;
}


  public function darLugaresCardJS(){
      $res = $this->i->darLugares();
      $html='';
      foreach ($res as $key => $value) {
      	$html.='
      				<summary class="col md-2" style="display:flex; justify-content:center;">
    						<div class="card imgCard" style="width: 10rem;" data-imgLugar="'.$value->id_lugar.'" data-nombreLugar="'.$value->nombre.'">
      						<img src="'.$value->img.'" class="card-img-top" alt="'.$value->nombre.'">
      						<div class="card-body"> 
        						<h5 class="card-title">'.$value->nombre.'</h5>
        						
      						</div>
    						</div>
  						</summary>';
      }
      	echo json_encode($html);
  }

  public function guardarIngreso(){
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        
        $data_mov_enc['tipo_movimiento'] = 1;
        $data_mov_enc['concepto_general'] = $_POST['concepto'];
        
       		$fecha = $_POST['fecha'];
					//acomodo la fecha 
					$f = explode("/",$fecha);
   			  $data_mov_enc['fecha_movimiento'] = $f[2]."-".$f[1]."-".$f[0];
   			  $data_mov_enc['fecha_movimiento'].=' '.date("H:i:s");
        $table = 'movimiento_enc';
        $guardar_movimiento_enc = $this->i->insertarDatosTabla($data_mov_enc,$table);
        
        if ($guardar_movimiento_enc == 1){
        $ultimo_id_guardado = $this->i->darUltimoRegistro("id_movimiento_enc","movimiento_enc");
        $id_movimiento_enc = $ultimo_id_guardado[0]->ultimo_registro;
        
        $data_mov_det['id_movimiento_enc'] = $id_movimiento_enc;
        $data_mov_det['monto'] = $_POST['monto'];
        $data_mov_det['id_lugar'] = $_POST['id_lugar'];
        $data_mov_det['id_categoria'] ='2';
        $data_mov_det['concepto'] = $_POST['concepto'];
        $table = 'movimiento_det';
        $guardar_movimiento_det = $this->i->insertarDatosTabla($data_mov_det,$table);
      	 echo $guardar_movimiento_det;
        }
        else
         echo 0;

    }
 
  }
