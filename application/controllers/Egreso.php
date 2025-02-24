<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property MovimientoModel $m
 * @property EgresoModel $e
 * @property TopeGastoModel $t
 * @property LugarModel $l
 */
class Egreso extends CI_Controller {
		   public function __construct(){
        parent::__construct(); 
              $this->load->model('MovimientoModel','m',true);
              $this->load->model('EgresoModel','e',true);
              $this->load->model('TopeGastoModel','t',true);
              $this->load->model('LugarModel','l',true);
              
        }
	public function index()
	{
				//$datos['categoria']   = $this-> darCategoria();
				$datos['lugares']   = $this-> darLugaresCardHtml();
                $datos_footer['cmblugares']   = $this-> darcmbLugar();
				$datos_page['page']   = $this->load->view('egreso/body',$datos, true);
			    $datos_page['titulo']   = 'Registrar Gastos';
		    $this->load->view('menu/header');
		    $this->load->view('egreso/header');
		    $this->load->view('menu/body',$datos_page, false);
		    $this->load->view('menu/footer',$datos_footer, false);
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
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////// LECTOR DE QR  ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

 public function procesar_qr()
 {
     // Obtener la URL desde el QR
     $qrUrl = $this->input->post('qrData');
     
     // Verificar si la URL se está recibiendo
     if (!$qrUrl) {  echo json_encode(['status' => 'error', 'message' => 'No se recibió la URL del QR']); return;}
     
     // Verificar si la URL es válida
     if (!filter_var($qrUrl, FILTER_VALIDATE_URL)) {echo json_encode(['status' => 'error', 'message' => 'URL no válida']); return;}
     
     // Intentar obtener el contenido HTML desde la URL
     $htmlContent = file_get_contents($qrUrl);
 
     if ($htmlContent === false) { echo json_encode(['status' => 'error', 'message' => 'No se pudo obtener el contenido HTML desde la URL: ' . $qrUrl]);return;}
     // Crear un objeto DOMDocument
     $dom = new DOMDocument();
     // Deshabilitar los errores de HTML mal formado
     libxml_use_internal_errors(true);
     // Cargar el contenido HTML en DOMDocument
     $dom->loadHTML($htmlContent);
     // Limpiar los errores internos de XML (por ejemplo, etiquetas mal formadas)
     libxml_clear_errors();
    // Obtener todos los productos
    // Crear un objeto DOMXPath
    $xpath = new DOMXPath($dom);
   // Buscar todos los <li class="product-li"> dentro de <ul class="product-ul">
$products = $xpath->query('//section[contains(@class, "products")]//ul[contains(@class, "product-ul")]//li[contains(@class, "product-li")]');
// Verificar si se encontraron elementos
    
    if ($products->length === 0) {
        echo "No se encontraron elementos <section> con la clase 'products'.";
    } else {
            $productData = [];
            foreach ($products as $product) {
                $productInfo = [];
                // Nombre del producto (primer <h2> dentro del <li>)
                $nameElement = $xpath->query('.//h2', $product)->item(0);
                $productInfo['nombre'] = $nameElement ? trim($nameElement->nodeValue) : 'Nombre no disponible';
                
                // Imagen del producto (primer <div> con un atributo "style" dentro del <li>)
                $imageElement = $xpath->query('.//div[contains(@style, "background-image")]', $product)->item(0);
                $productInfo['imagen'] = $imageElement ? $imageElement->getAttribute('style') : 'Imagen no disponible';
        
                // Precio del producto (<div> con la clase "info-producto-price" dentro del <li>)
                $priceElement = $xpath->query('.//div[contains(@class, "info-producto-price")]', $product)->item(0);
                $productInfo['precio'] = $priceElement ? trim($priceElement->nodeValue) : 'Precio no disponible';
                  
                // Precio del producto (<div> con la clase "info-producto-price" dentro del <li>)
                $priceDescuentoElement = $xpath->query('.//div[contains(@class, "precio-descuento")]', $product)->item(0);
                $productInfo['precioDescuento'] = $priceDescuentoElement ? trim($priceDescuentoElement->nodeValue) : 0;
                
                if($productInfo['precioDescuento']!==0){
                    $precio = str_replace(['$', '.'], '', trim($priceElement->nodeValue));
                    $precio = str_replace(',', '.', $precio); // Reemplazar la coma por un punto
                       // Limpiar el precio de descuento y reemplazar la coma por un punto
                    $precioDescuento = str_replace(['$', '.','-'], '', trim($priceDescuentoElement->nodeValue));
                    $precioDescuento = str_replace(',', '.', $precioDescuento); // Reemplazar la coma por un punto
                   
                    //$productInfo['precio'] = bcsub($precio, $precioDescuento, 2);
                    
                    //$productInfo['precio'] = $precio;
                    //$productInfo['precioDescuento'] = $precioDescuento;
                    
                    $precioFinal = bcsub($precio, $precioDescuento, 2);
                    
                    $productInfo['precio'] = '$' .number_format( $precioFinal,$decimals = 2,$dec_point = ",",$thousands_sep = ".");
                    

                }/*else{
                    $precio = str_replace(['$', '.'], '', trim($priceElement->nodeValue));
                    $precio = str_replace(',', '.', $precio); // Reemplazar la coma por un punto
                       // Limpiar el precio de descuento y reemplazar la coma por un punto
                    
                    $productInfo['precio'] = $precio;
                    
                }*/
              
                // Agregar el producto a la lista de datos
                $productData[] = $productInfo;
}
  

// Buscar el segundo <span> dentro del <div> con la clase "info-total info-total-border"
 $totalDeCompra = $xpath->query('//div[contains(@class, "info-total") and contains(@class, "info-total-border")]/span[2]')->item(0);
$total = trim($totalDeCompra->nodeValue);

// Generar la tabla HTML
$htmlTable = '';
$cmbCategorias= $this->darCategoriaSelected(3);

foreach ($productData as $key=>$product) {
    $htmlTable .= '<tr>';
    $htmlTable .= '<td><span style="' . htmlspecialchars($product['imagen'], ENT_QUOTES, 'UTF-8') .'; display: inline-block; width: 100px; height: 100px; background-size: cover;"></span> </td>';
    $htmlTable .= '<td id='.$key.'>'. htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8') . '</td>';
    $htmlTable .= '<td>'. htmlspecialchars($product['precio']).'</td>';
    $htmlTable .= '<td><select name="categorias">'. $cmbCategorias . '</select></td>';
    $htmlTable .= '</tr>';
}


// Enviar respuesta JSON con los productos y su información
header('Content-Type: application/json');

echo json_encode([
   'status' => 'success',
   'message' => 'Datos extraídos correctamente',
   'data' => [
        'total'=>$total,
        'tabla'=>$htmlTable
   ]
]);

}
 }
 
   

   public function get_page_content($url)
   {
       // Inicializamos cURL
       $ch = curl_init();
   
       // Configuramos la URL
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Esto indica que la respuesta debe ser devuelta como una cadena
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // Seguir cualquier redirección
   
       // Ejecutamos la solicitud
       $output = curl_exec($ch);
   
       // Verificamos si hubo errores
       if (curl_errno($ch)) {
           echo 'Error al obtener la página: ' . curl_error($ch);
           curl_close($ch);
           return false;
       }
   
       // Cerramos la conexión cURL
       curl_close($ch);
       $pageContent = $this->get_page_content($qrData);
       if ($pageContent) {
           $extractedData = $this->extract_data_from_html($pageContent);
           // Ahora puedes hacer algo con los datos extraídos
       }
       // Devolvemos el contenido HTML de la página
       return $output;
   }
   // Requiere que tengas instalada la librería simplehtmldom


public function extract_data_from_html($htmlContent) {
 // Crear un objeto DOM a partir del HTML
 $html = str_get_html($htmlContent);

 // Buscar los elementos HTML que te interesen
 $title = $html->find('title', 0)->plaintext; // Extraer el título de la página
 $price = $html->find('.price', 0)->plaintext; // Buscar el primer elemento con la clase 'price'

 return array(
     'title' => $title,
     'price' => $price
 );
}

public function darCategoriaSelected($cat){
    $res = $this->e->darCategoria();
    $html = '';
    foreach ($res as $key => $value) {
            if ($value->id_categoria !== 1 ){
                if ($value->id_categoria == $cat ){
                $html.='<option selected value="'.$value->id_categoria.'">'.$value->nombre.'</option>';
            }else{
                $html.='<option value="'.$value->id_categoria.'">'.$value->nombre.'</option>';
            }
        }
    }
        return $html;
  }
  public function darcmbLugar(){
    $res = $this->l->darLugar();
    $html = '<option></option>';
    foreach ($res as $key => $value) {
            if ($value->id_lugar !== 1 ){
                $html.='<option value="'.$value->id_lugar.'">'.$value->nombre.'</option>';
            
        }
    }
    return $html;
  }
  public function guardarGastoTiket() {
    // Recibir los datos enviados por AJAX
    $fecha = $this->input->post('fecha');
    $f = explode("/",$fecha);
    $fecha_movimiento = $f[2]."-".$f[1]."-".$f[0].' '.date("H:i:s");
    $lugar = $this->input->post('lugar');
    $conceptoGeneral = $this->input->post('conceptoGeneral');
    $detalles = $this->input->post('detalles');
    $guardar_movimiento_enc = $this->e->guardarMovimientoEnc('2',$conceptoGeneral,$fecha_movimiento);
    $control = 1; 
    if ($guardar_movimiento_enc==1) {
        $ultimoMovimiento = $this->m->darUltimoMovEnc();  
        $id_movimiento_enc = $ultimoMovimiento->id_movimiento_enc;
        $table = 'movimiento_det';
    // Procesar y guardar los datos en la base de datos
    foreach ($detalles as $detalle) {
        $data = array(
            'id_movimiento_enc' => $id_movimiento_enc,
            'monto' => $detalle['precio'],
            'id_lugar' => $lugar,
            'id_categoria' => $detalle['categoria'],
            'concepto' => $detalle['concepto'],
            'img' => $detalle['imagenUrl']
           
        );

        // Insertar en la base de datos
        $insertar_mov_det = $this->e->insertarDatosTablaEgreso($data,$table);
        if ($insertar_mov_det==0){$control=0;}
    }
    }
    // Devolver una respuesta JSON
    if ($control==1){
        echo json_encode(array('success' => true));
    }
    
}

 //////////////////////////////////////////////////////////////////////////////


}
