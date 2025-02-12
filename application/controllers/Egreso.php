<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property MovimientoModel $m
 * @property EgresoModel $e
 * @property TopeGastoModel $t
 */
class Egreso extends CI_Controller {
		   public function __construct(){
        parent::__construct(); 
              $this->load->model('MovimientoModel','m',true);
              $this->load->model('EgresoModel','e',true);
              $this->load->model('TopeGastoModel','t',true);
              
        }
	public function index()
	{
				//$datos['categoria']   = $this-> darCategoria();
				$datos['lugares']   = $this-> darLugaresCardHtml();
				$datos_page['page']   = $this->load->view('egreso/body',$datos, true);
			    $datos_page['titulo']   = 'Registrar Gastos';
		    $this->load->view('menu/header');
		    $this->load->view('egreso/header');
		    $this->load->view('menu/body',$datos_page, false);
		    $this->load->view('menu/footer');
		    $this->load->view('egreso/footer');
	}

	public function cargarImagenLugarNuevo()
	{
		if (($_FILES["image"]["type"] == "image/pjpeg")|| ($_FILES["image"]["type"] == "image/jpeg")|| ($_FILES["image"]["type"] == "image/png")|| ($_FILES["image"]["type"] == "image/gif")) 
		{
			
			$check = getimagesize($_FILES["image"]["tmp_name"]);
    		//print_r($_FILES);
    		if($check !== false){
        		$image = $_FILES["image"]["tmp_name"];
				// Extensión de la imagen
					$type = pathinfo($image, PATHINFO_EXTENSION);
			 	// Cargando la imagen
					$data = file_get_contents($image);

				// Decodificando la imagen en base64
					$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				// Mostrando la imagen
				echo $base64;
			
			}else{ echo 0;}

			
		} else { echo 0;}
	}
	
	public function guardarLugarNuevo()
	{
		$nombreLugar = $_POST['nombre'];
		$check = getimagesize($_FILES["image"]["tmp_name"]);
   	$image = $_FILES["image"]["tmp_name"];
			// Extensión de la imagen
		$type = pathinfo($image, PATHINFO_EXTENSION);
		 	// Cargando la imagen
		$data = file_get_contents($image);
			// Decodificando la imagen en base64
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

   		$campo['nombre'] = $nombreLugar;
  		$campo['img'] = $base64;
        $table = 'lugar';
        $guardar_lugar = $this->e->insertarDatosTablaEgreso($campo,$table);
 		if ($guardar_lugar) {
			echo $base64;
		}else{ echo 0;}
	}
public function darLugaresCardHtml() {
    $res = $this->e->darLugares();
    $html = '';

    foreach ($res as $key => $value) {
        $html .= '
        <div class="col-md-2 col-sm-4 col-6 d-flex justify-content-center mb-1">
            <div class="card imgCard shadow-sm" style="width: 6.5rem; border-radius: 6px;"data-imgLugar="'.$value->id_lugar.'" data-nombreLugar="'.$value->nombre.'">
                
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




  public function darCategoria(){
      $res = $this->e->darCategoria();
      $html = '<option></option>';
      foreach ($res as $key => $value) {
      		if ($value->id_categoria !== 1 ){
      			$html.='<option value="'.$value->id_categoria.'">'.$value->nombre.'</option>';
      		}
      }
      	return $html;
    }  

    public function darConcepto(){
      $res = $this->e->darConceptoIndividual();
      $opciones = array();
    foreach ($res as $key => $value) {
            $opciones[] = array(
                'value' => $value->concepto,
                'nombre' => $value->concepto
            );
        
    }
    echo json_encode($opciones);
    }

    public function darCategoriasJSArray(){
    $res = $this->e->darCategoria();
    $opciones = array();
    foreach ($res as $key => $value) {
        if ($value->id_categoria !== '1' && $value->id_categoria !== '2') {
            $opciones[] = array(
                'value' => $value->id_categoria,
                'nombre' => $value->nombre
            );
        }
    }
    echo json_encode($opciones);
}
      public function guardarGasto(){
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $lugar              =   $_POST['id_lugar'];
        $gasto              =   $_POST['gasto'];
        $fecha              =   $_POST['fecha'];
        $control            =   1;
        $conceptoGeneral    =   $_POST['conceptoGeneral'];
        $tipo_movimiento    =   '2';
      
        $f = explode("/",$fecha);
        $fecha_movimiento = $f[2]."-".$f[1]."-".$f[0].' '.date("H:i:s");
                    
        $guardar_movimiento_enc = $this->e->guardarMovimientoEnc($tipo_movimiento,$conceptoGeneral,$fecha_movimiento);
        
        if ($guardar_movimiento_enc==1) {
        $ultimoMovimiento = $this->m->darUltimoMovEnc();  
        $mov_det['id_movimiento_enc'] = $ultimoMovimiento->id_movimiento_enc;

        $table = 'movimiento_det';
    foreach ($gasto as $key => $value) {
      $mov_det['monto'] = $value['monto']*-1;
      $mov_det['id_lugar'] = $lugar;
      $mov_det['id_categoria'] = $value['categoria'];
      $mov_det['concepto'] = $value['concepto'];
      
      $insertar_mov_det = $this->e->insertarDatosTablaEgreso($mov_det,$table);
            
            if ($insertar_mov_det==0){$control=0;}
        }
}
        echo json_encode($control);    
    } 
  

    public function verificarLimiteGasto(){
	    $lugar  = $_POST['id_lugar'];
	    $gasto  = $_POST['gasto'];
     	$control=1;
     		$fecha = $_POST['fecha'];
					//acomodo la fecha 
					$f = explode("/",$fecha);
   			  $fecha_movimiento = $f[2]."-".$f[1]."-".$f[0];
        $limiteCategoria = $this->t->consultarLimiteCategoria();
				$categorias='';
        
  									$categorias = substr($categorias, 0, -1); 
        						$arrayCategorias = explode(',', $categorias);


if ($limiteCategoria==1) {
		$ultimoMovimiento = $this->m->darUltimoMovEnc();  

    $mov_det['id_movimiento_enc'] = $ultimoMovimiento[0]->id_movimiento_enc;
   
    $table = 'movimiento_det';
	foreach ($gasto as $key => $value) {
      $mov_det['monto'] = $value['monto']*-1;
      $mov_det['id_lugar'] = $lugar;
      $mov_det['id_categoria'] = $value['categoria'];
      $insertar_mov_det = $this->e->insertarDatosTablaEgreso($mov_det,$table);
			
			if ($insertar_mov_det==0){$control=0;}
		}
}
		echo json_encode($control);		
    }

    public function guardarCategoria(){
        
        $data['nombre'] = $_POST['categoria'];
        $data['activa'] = 1;
        $table = 'categoria';
        $guardar_actividad = $this->e->insertarDatosTablaEgreso($data,$table);
        echo $guardar_actividad;
    }

     public function consultarSaldo(){
		    $monto 					 = $_POST['monto'];
    		$lugar 					 = $_POST['id_lugar'];
   		  $saldoDisponible  = $this->e->consultarSaldo($lugar);	   
   			if ($saldoDisponible[0]->disponible >= $monto){
    			echo 1;
    			}else{
    			echo 0;
    			}
  }
 


   }
