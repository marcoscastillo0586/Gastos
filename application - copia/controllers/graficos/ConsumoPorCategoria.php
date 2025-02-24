<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConsumoPorCategoria extends CI_Controller {
		   public function __construct(){
        parent::__construct();
          $this->load->model('LugarModel','l',true);
          $this->load->model('ConsumoPorCategoriaModel','c',true);
          $this->load->model('CategoriaModel','cat',true);
          $this->load->model('EgresoModel','e',true);
        
        }
	public function index()
	{
		$datos['Lugares']     = $this->l->darLugar();
	  $datos_page = [
        'page' => $this->load->view('graficos/consumoPorCategoria/body', $datos, true),
        'titulo' => 'Consumo Por Categoría'
    		];
		$this->load->view('menu/header');
		$this->load->view('menu/body',$datos_page, false);
		$this->load->view('menu/footer');
		$this->load->view('graficos/consumoPorCategoria/footer');
	}

	public function darGraficoCategoria(){
		

				$lugar = !isset($_POST['lugar']) ? '0' : $_POST['lugar'];
				if ($_POST['desde']!==""){
					$desde = "'".$_POST['desde']."'";
					$hasta = "'".$_POST['hasta']." 23:59:59'";
					 
					}
					else{
					$desde = "(SELECT DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY))";
					$hasta = "(SELECT LAST_DAY(CURDATE()))";
				}


				$categorias= $this->c->darGastosPorCategoria($desde,$hasta,$lugar);
				$totalDeGasto= $this->c->darGastosTotal($desde,$hasta,$lugar);
				
				//print_r($totalDeGasto);
				$datos['categorias']=array();
				$datos['monto']=array();
				$datos['color']=array();
				$datos['colorHover']=array();
				foreach ($categorias as $key => $value) {
			 		$paletaColores = array('#FF0303','#FF4C03','#FF4C03','#FF7903','#FF9803','#FFD103','#FFE003','#FFF703','#FBFF03','#E8FF03','#C6FF03','#AFFF03','#81FF03','#5FFF03','#0BFF03','#03FF1A','#03FFC2','#03FBFF','#0338FF','#1A03FF');
			 	  $color = '#'.substr(md5(rand()), 0, 6);
			 	  $datos['color'][].= $color;
			 	  $datos['colorHover'][].= $color;
				  $sumador = round((20/count($categorias))); 
			
						$datos['categorias'][].=$value->categoria;
						$datos['monto'][].=$value->monto;
				}
				$datos['totalConsumo']=  $this->your_money_format($totalDeGasto[0]->total);
				 

			 //print_r($datos);
				 echo json_encode($datos);

	}	
		function your_money_format($value) {
  return '$ ' . number_format( $value,$decimals = 2,$dec_point = ",",$thousands_sep = ".");
 }	
	public function darGraficoSubCategoria(){
				$lugar = !isset($_POST['lugar']) ? '0' : $_POST['lugar'];
				if ($_POST['desde']!==""){
					$desde = "'".$_POST['desde']."'";
					$hasta = "'".$_POST['hasta']." 23:59:59'";
					}
					else{
					$desde = "(SELECT DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY))";
					$hasta = "(SELECT LAST_DAY(CURDATE()))";
				}
				$catSelected = $_POST['idCat'];
		
				$categorias = $this->c->darGastosPorSubCategoria($desde,$hasta,$lugar,$catSelected);
				$totalDeGasto= $this->c->darGastosTotalPorCategorias($desde,$hasta,$lugar,$catSelected);
				$datos['categorias']=array();
				$datos['monto']=array();
				$datos['color']=array();
				$datos['colorHover']=array();
			 		
				foreach ($categorias as $key => $value) {
			
					$color = '#'.substr(md5(mt_rand()), 0, 6);
			 	  $datos['color'][].= $color;
			 	  $datos['colorHover'][].= $color;
						$datos['categorias'][].=$value->categoria;
						$datos['monto'][].=$value->monto;
			
				}
				$datos['totalConsumo']= $this->your_money_format($totalDeGasto[0]->total);
				 

			// print_r($datos);
				 echo json_encode($datos);

	}	
	public function darGraficoConcepto(){
		$lugar = !isset($_POST['lugar']) ? '0' : $_POST['lugar'];
		if ($_POST['desde']!==""){
			$desde = "'".$_POST['desde']."'";
			$hasta = "'".$_POST['hasta']." 23:59:59'";
			}
			else{
			$desde = "(SELECT DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY))";
			$hasta = "(SELECT LAST_DAY(CURDATE()))";
		}
		$conSelected = $_POST['idCon'];
		
		
		$categorias = $this->c->darGastosPorConcepto($desde,$hasta,$lugar,$conSelected);
		$totalDeGasto= $this->c->darGastosTotalPorConcepto($desde,$hasta,$lugar,$conSelected);

	
		$datos['categorias']=array();
		$datos['monto']=array();
		$datos['color']=array();
		$datos['colorHover']=array();
		foreach ($categorias as $key => $value) {

			$color = '#'.substr(md5(mt_rand()), 0, 6);
	 	  $datos['color'][].= $color;
	 	  $datos['colorHover'][].= $color;
				$datos['categorias'][].=$value->categoria;
				$datos['monto'][].=$value->monto;

		}
		$datos['totalConsumo']= $this->your_money_format($totalDeGasto[0]->total);
		 echo json_encode($datos);

	}	
function generarPaletaColoresInfinita($cantidad) {
    $paletaColores = array();
    for ($i = 0; $i < $cantidad; $i++) {
        $nuevoColor = '#' . substr(md5(mt_rand()), 0, 6); // Generar color aleatorio
        $paletaColores[] = $nuevoColor;
    }
    return $paletaColores;
}
	public function darCategoriasJS(){
      $res = $this->cat->darCategoria();
      $html='<option disabled></option>';
      foreach ($res as $key => $value) {
       // if (($value->id_categoria !=='1') && ($value->id_categoria !=='2')){
          $html.='<option value="'.$value->id_categoria.'">'.$value->nombre.'</option>';
        //}
      }
        echo json_encode($html);
  }  

  public function darConceptoJS(){
	if (!empty($_POST['desde']) && !empty($_POST['hasta'])) {
    // Limpieza de entradas para evitar inyecciones SQL
    $desde = "'" . mysqli_real_escape_string($conn, trim($_POST['desde'])) . "'"; // Asegúrate de tener la conexión $conn abierta
    $hasta = "'" . mysqli_real_escape_string($conn, trim($_POST['hasta'])) . " 23:59:59'";
	} else {
    // Si no se proporcionan fechas, se establecen los valores por defecto
    $desde = "(SELECT DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY))"; // Primer día del mes
    $hasta = "(SELECT LAST_DAY(CURDATE()))"; // Último día del mes
	}

      $res = $this->e->darConcepto($desde,$hasta);
      $html='<option disabled></option>';
      foreach ($res as $key => $value) {
          $html.='<option value="'.$value->nombre.'">'.$value->nombre.'</option>';
      }
        echo json_encode($html);
  } 
}
