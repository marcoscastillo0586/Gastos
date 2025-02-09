<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct(){
    parent::__construct();
          $this->load->model('LugarModel','l',true);
          $this->load->model('TopeGastoModel','t',true);
          $this->load->model('CategoriaModel','c',true);
    }
	
    public function index()
	{
		$this->load->view('login/body');
	}

    function your_money_format($value) {
        return  number_format( $value,$decimals = 2,$dec_point = ",",$thousands_sep = ".");
    }	

    public function validarAcceso() {
    $total = 0;
    $datos['montoLugares'] = $this->l->darMontoLugar();

    foreach ($datos['montoLugares'] as $key => $value) {
        $montoOriginal = floatval($value->monto);

        // Si la moneda es dólar, convertir a pesos
        if ($value->tipoMoneda == 2) {
            $datosMoneda = $this->darCotizacionDolar();
            $cotizacion = floatval($datosMoneda['cotizacion']);

            $datos['montoLugares'][$key]->nombreMoneda = $datosMoneda['nombre'];
            $datos['montoLugares'][$key]->cotizacion = $cotizacion;
            $datos['montoLugares'][$key]->montoDolar = number_format($montoOriginal, 0, ",", ".");


            // Convertir monto a pesos
            $montoPesos = $montoOriginal * $cotizacion;
            $datos['montoLugares'][$key]->valorPesos = number_format($montoPesos, 0, ",", ".");
            $value->monto = $montoPesos;
        }

        $total += $value->monto;
        $value->monto = $this->your_money_format($value->monto);
        $value->montoFloat = $value->monto;
    }

    // Formatear total
    $datos['sumaTotal'] = $this->your_money_format($total);

    // Cargar vistas con los datos procesados
    $datos_page = [
        'page' => $this->load->view('dashboard/body', $datos, true),
        'titulo' => 'Menú Principal'
    ];
    
    $this->load->view('menu/header');
    $this->load->view('menu/body', $datos_page, false);
    $this->load->view('dashboard/footer');
    $this->load->view('menu/footer');
}

function darCotizacionDolar() {
    $url = 'https://dolarapi.com/v1/dolares/blue';
    try {
        $json = file_get_contents($url);
        if ($json === false) {
            throw new Exception("Error al obtener los datos");
        }
        $jsonObjet = json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar JSON");
        }
        return [
            'nombre' => $jsonObjet->nombre ?? "Desconocido",
            'cotizacion' => $jsonObjet->venta ?? 1
        ];
    } catch (Exception $e) {
        return [
            'nombre' => "sin conexión",
            'cotizacion' => 1
        ];
    }
}

	public function logout(){
		$this->session->sess_destroy();
		$this->load->view('login/body');
	}

	public function eliminarLimiteGastos(){ $res = $this->t->eliminarLimiteGasto(); }
	
	public function eliminarLimiteGastosPorId() {   
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
        return;
    }

    $id = intval($_POST['id']); 
    $resultado = $this->t->eliminarLimiteGastoPorLimites($id);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Límite de gastos eliminado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el límite de gastos.']);
    }
}
	
    public function editarLimiteGastos()
{
    // Obtener los datos del formulario
    $id = $this->input->post('id');
    $monto = $this->input->post('monto');

    // Validación de datos
    if (empty($id) || !is_numeric($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
        return;
    }
    if (empty($monto) || !is_numeric($monto) || $monto < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Monto inválido.']);
        return;
    }

    // Llamar al modelo para editar el límite de gasto
    $res = $this->t->editarLimiteGasto($monto, $id);

    // Verificar si la actualización fue exitosa
    if ($res) {
        echo json_encode([
            'status' => 'success',
            'message' => 'El límite de gasto se actualizo correctamente.',
            'data' => $res
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el límite de gasto.'
        ]);
    }
}

	public function renovarLimite(){   
		$id = $_POST['id']; 
		$res = $this->t->renovarLimiteGasto($id);
		echo json_encode($res);
	}
	public function LimitePorId(){   
		$id = $_POST['id']; 
		$res = $this->t->traerLimitePorID($id);
		echo json_encode($res);
	}

  public function darGraficoLimitesGasto(){

	$limitesDeGastos= $this->t->consultarLimiteGasto();
  $datos = new stdClass();
  $renglones = array();
	foreach ($limitesDeGastos as $key => $value) {
		$lugares = explode(",",$value->lugar);	
		$categorias = explode(",",$value->categoria);	
		$nombreCategorias ='';
		$nombreLugares ='';
		$value->renovar = 0;
		for ($i=0; $i < count($categorias); $i++){ 
			$nombreCategoria = $this->c->darNombrePorId($categorias[$i]);
			$nombreCategorias.=$nombreCategoria[0]->nombre.', ';	
		}
		$value->nombreCategoria = substr($nombreCategorias, 0, -2); 

		for ($i=0; $i < count($lugares); $i++){ 
			$id_lugar = $lugares[$i];
			$nombreLugar = $this->l->darNombrePorId($id_lugar);
			$nombreLugares.=$nombreLugar[0]->nombre.', ';	
		}
		$value->nombreLugar = substr($nombreLugares, 0, -2); 
		$desde = $value->desde; 
    $hasta = $value->hasta; 
    $lugar = $value->lugar; 
    $categoria = $value->categoria; 

		$consumo_actual = $this->t->darConsumoDesdeHasta($desde,$hasta,$lugares,$categorias);

 //acomodo la fecha 
          $fd = explode("-",$desde);
          $fechaDesde = $fd[2]."-".$fd[1];
				  $value->desde = $fechaDesde ; 
					$fh = explode("-",$hasta);
          $fechaHasta = $fh[2]."-".$fh[1]."-".$fh[0];
				  $value->hasta = $fechaHasta;
				  
				  $fechaActual = strtotime(date("d-m-Y"));
				  
				  $fechaVencimiento = strtotime($fechaHasta);
				  
					if($fechaActual >= $fechaVencimiento ){
						$value->renovar = 1;
					}	
						
					
    $monto_limite 				=   $value->monto_limite;
		$monto_consumo_actual = 	$consumo_actual[0]->monto;
		$resto = $monto_limite-$monto_consumo_actual;	
		$porcentaje = bcdiv( ($monto_consumo_actual*100)/$monto_limite, '1', 2);
		
				$value->monto_consumo_actual = $monto_consumo_actual;
				$value->monto_limite = $monto_limite;
				$value->resto = $resto;
				$value->porcentaje = $porcentaje;
	  //    array_push($renglones,$datos);
	}
				echo json_encode($limitesDeGastos);
	}
}
