<?php
class Lectorqr extends CI_Model {
    public function __construct() {
        parent::__construct();
        // Puedes cargar otras librerías si es necesario
    }

    public function leer_qr($file_path) {
        // Incluimos la librería para leer QR
        $this->load->library('phpqrcode'); // Asegúrate de haber descargado e incluido la librería en el directorio adecuado

        // Usamos una librería externa para leer el código QR
        $qr_data = QRcode::decode($file_path);  // Procesamos la imagen

        if ($qr_data) {
            return $qr_data;  // Retornamos el contenido del QR
        } else {
            return false;  // No se pudo leer el QR
        }
    }
}

