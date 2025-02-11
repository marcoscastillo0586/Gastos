<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property LugarModel $l
 */
class LugarABM extends CI_Controller {
       public function __construct(){
        parent::__construct();
              $this->load->model('LugarModel','l',true);
         
        }
        
  public function index()
  {
   
    $datos_page['titulo'] = 'ABM de Lugar';
    $datos_page['page']   = $this->load->view('abm/lugar/body','', true);
    $this->load->view('menu/header');
    $this->load->view('abm/lugar/header');
    $this->load->view('menu/body',$datos_page, false);
    $this->load->view('menu/footer');
    $this->load->view('abm/lugar/footer');
  }

  public function darLugares(){
    $lugares = $this->l->darLugarTodos();
    $html='';
    if ($lugares){
        foreach ($lugares as $lug) {
          $moneda = ($lug->tipoMoneda == 1) ? '$' : '$$';
          if ($lug->activo == 1){
            $activo='SI';
            $imgActivo='<i class="fas fa-trash-alt text-danger eliminar" style="cursor: pointer;" data-id="'.$lug->id_lugar.'"></i>';
            
          }else{
            $activo='NO';
            $imgActivo='<i class="fas fa-check text-success activar" style="cursor: pointer;" data-id="'.$lug->id_lugar.'" data-toggle="tooltip" title="Renovar Limite"></i>';

          }
          $imgUrl = base_url(htmlspecialchars($lug->img, ENT_QUOTES, "UTF-8"));
        
          $html .= '<tr class="text-light bg-dark " data-id="'.$lug->id_lugar.'" data-tipo="encabezado">
                <td data-columna="nomrbe">'.$lug->nombre.'</td>
                <td data-columna="activo">'.$activo.'</td>
                <td data-columna="moneda">'.$moneda.'</td>
                <td data-columna="imagen"><img src="'.$imgUrl.'" class="card-img-top" alt="'.htmlspecialchars($lug->nombre, ENT_QUOTES, "UTF-8").'" style="width: 25%; height: auto; object-fit: cover;"></td>
                ';
            $html .= '<td class="text-center">';
                $html .= $imgActivo;
            $html .= '</td></tr>';
        }
        echo $html;
    } else {
        echo "<tr><td colspan='5' class='text-center'>Sin Registros</td></tr>";
    }
  }

  public function eliminarLugar() {

    $id    = $_POST['id'];
    $value = ['activo' => 0];
    $where = ['id_lugar' => $id]; 
    $res   = $this->l->actualizar('lugar',$value,$where);
    echo json_encode($res);
}  

  public function activarLugar() {

    $id    = $_POST['id'];
    $value = ['activo' => 1];
    $where = ['id_lugar' => $id]; 
    $res   = $this->l->actualizar('lugar',$value,$where);
    echo json_encode($res);
}  

  public function validarLugar() {

    $id    = $_POST['id'];
    $res   = $this->l->darMontoLugarTodos($id);
    echo json_encode($res);
}

public function guardarLugarNuevo()
{
    $nombreLugar = $_POST['nombre'];
    $tipoMoneda = $_POST['tipoMoneda'];

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
            $campo['tipoMoneda'] = $tipoMoneda;

            $table = 'lugar';
            $guardar_lugar = $this->l->insertarDatosTabla($campo, $table);

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

}