<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

    // Verificar si se subió una imagen
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES["image"]["tmp_name"];
        $imageName = $_FILES["image"]["name"];

        // Obtener la extensión del archivo
        $ext = pathinfo($imageName, PATHINFO_EXTENSION);
        $ext = strtolower($ext); // Asegurar que la extensión esté en minúsculas

        // Validar que el archivo sea una imagen
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $validExtensions)) {
            echo "Error: Formato de imagen no permitido.";
            return;
        }

        // Generar un nombre único para la imagen y su thumbnail
        $thumbFileName = "$nombreLugar".".".$ext;

        // Rutas de destino
        $thumbPath = "assets/img/lugares/" . $thumbFileName;

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($imageTmp, $thumbPath)) {
            // Corregir la orientación de la imagen (si es necesario)
            $this->corregirRotacion($thumbPath);

            // Crear thumbnail
            $this->crearThumbnail($thumbPath, $thumbPath, 200, 200);

            // Guardar la ruta del thumbnail en la BD
            $campo['nombre'] = $nombreLugar;
            $campo['img'] = $thumbPath; // Guardamos la ruta del thumbnail
            $campo['activo'] = 1;

            $table = 'lugar';
            $guardar_lugar = $this->i->insertarDatosTabla($campo, $table);

            if ($guardar_lugar) {
                echo $thumbPath;
            } else {
                echo "Error al guardar en la base de datos.";
            }
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "No se ha subido ninguna imagen válida.";
    }
}

/**
 * Función para crear un thumbnail manteniendo la relación de aspecto.
 */
private function crearThumbnail($sourcePath, $destPath, $thumbWidth, $thumbHeight)
{
    list($width, $height, $type) = getimagesize($sourcePath);

    // Crear una nueva imagen en blanco con las dimensiones del thumbnail
    $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);

    // Dependiendo del tipo de imagen, crear la imagen desde el archivo original
    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    // Redimensionar la imagen manteniendo la relación de aspecto
    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    // Guardar el thumbnail
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnail, $destPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnail, $destPath, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumbnail, $destPath);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($thumbnail, $destPath, 90);
            break;
    }

    // Liberar memoria
    imagedestroy($thumbnail);
    imagedestroy($sourceImage);

    return true;
}

/**
 * Función para corregir la rotación de la imagen si tiene metadatos EXIF.
 */
private function corregirRotacion($imagePath)
{
    // Solo procesar si es una imagen JPG/JPEG (EXIF solo afecta a este formato)
    if (exif_imagetype($imagePath) === IMAGETYPE_JPEG) {
        $exif = exif_read_data($imagePath);

        if (!empty($exif['Orientation'])) {
            $image = imagecreatefromjpeg($imagePath);
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }
            imagejpeg($image, $imagePath, 90);
            imagedestroy($image);
        }
    }
}




	public function darLugaresCardHtml(){
    $res = $this->i->darLugares();
    $html = '';

    foreach ($res as $key => $value) {
        $imgUrl = base_url(htmlspecialchars($value->img, ENT_QUOTES, "UTF-8"));

        $html .= '
        <div class="col">
            <div class="card imgCard" style="width: 100%; height: auto;" data-imgLugar="'.htmlspecialchars($value->id_lugar, ENT_QUOTES, "UTF-8").'" data-nombreLugar="'.htmlspecialchars($value->nombre, ENT_QUOTES, "UTF-8").'">
                <img src="'.$imgUrl.'" class="card-img-top" alt="'.htmlspecialchars($value->nombre, ENT_QUOTES, "UTF-8").'" style="width: 100%; height: auto; object-fit: cover;">
                <div class="card-body text-center"> 
                    <h6 class="card-title text-uppercase">'.htmlspecialchars($value->nombre, ENT_QUOTES, "UTF-8").'</h6>
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
