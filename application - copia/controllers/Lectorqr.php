<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lectorqr extends CI_Controller {
		   public function __construct(){
        parent::__construct();
				$this->load->model('IngresoModel','i',true);
        }
				public function index() {
					// Cargamos la vista que contiene el formulario
					$this->load->view('lectordeqr/body');
			}

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
        // Extraer el título de la página
        $title = $dom->getElementsByTagName('title')->item(0)->nodeValue ?? 'Sin título';
  // Obtener todos los productos
  $products = $dom->getElementsByTagName('li'); // Elementos de la lista (productos)

  $productData = [];

  foreach ($products as $product) {
      $productInfo = [];

      // Nombre del producto
      $nameElement = $product->getElementsByTagName('h2')->item(0);
      $productInfo['name'] = $nameElement ? $nameElement->nodeValue : 'Nombre no disponible';

      // Imagen del producto (URL de la imagen en el fondo)
      $imageElement = $product->getElementsByTagName('div')->item(0); // Producto imagen en estilo
      $productInfo['image'] = $imageElement ? $imageElement->getAttribute('style') : 'Imagen no disponible';

      // Precio original
      $priceElement = $product->getElementsByTagName('div')->item(1); // Precio
      $productInfo['price'] = $priceElement ? $priceElement->nodeValue : 'Precio no disponible';

      // Descuento
      $discountElement = $product->getElementsByTagName('div')->item(3); // Descuento
      $productInfo['discount'] = $discountElement ? $discountElement->nodeValue : 'Sin descuento';

      // Agregar el producto a la lista de datos
      $productData[] = $productInfo;
  }

  // Enviar respuesta JSON con los productos y su información
  header('Content-Type: application/json');
  echo json_encode([
      'status' => 'success',
      'message' => 'Datos extraídos correctamente',
      'data' => [
          'title' => $title,
          'products' => $productData
      ]
  ]);
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

public function test_html_parser() {
  // Cargar el helper
  $this->load->helper('simple_html_dom_helper');

  // URL de prueba
  $url = "http://apps01.coto.com.ar/TicketMobile/Ticket/NDE1Ny85NjY2LzIwMjUwMTMxLzEzMS8wMDAxMjEzOTM4MA=="; 

  // Obtener el HTML de la página
  $html = file_get_html($url);

  // Extraer el título de la página
  if ($html) {
      $title = $html->find('title', 0)->plaintext;
      echo "Título de la página: " . $title;
  } else {
      echo "No se pudo obtener el HTML.";
  }
}


       

	}
