<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property categoriaModel $c
 */
class CategoriaABM extends CI_Controller {
       public function __construct(){
        parent::__construct();
              $this->load->model('categoriaModel','c',true);
        }
        
  public function index()
  {
    $datos_page['titulo'] = 'ABM de Categoría';
    $datos_page['page']   = $this->load->view('abm/categoria/body','', true);
    $this->load->view('menu/header');
    $this->load->view('abm/categoria/header');
    $this->load->view('menu/body',$datos_page, false);
    $this->load->view('menu/footer');
    $this->load->view('abm/categoria/footer');
  }

  public function darcategoria(){
    $categorias = $this->c->darCategoriaTodas();
    $html='';
    if ($categorias){
        foreach ($categorias as $cat) {

          if ($cat->activa == 1){
            $activa='SI';
            $imgActiva='<i class="fas fa-trash-alt text-danger eliminar" style="cursor: pointer;" data-id="'.$cat->id_categoria.'"></i>';
          }else{
            $activa='NO';
            $imgActiva='<i class="fas fa-check text-success activar" style="cursor: pointer;" data-id="'.$cat->id_categoria.'" data-toggle="tooltip" title="Renovar Limite"></i>';
          }

          $html .= '<tr class="text-light bg-dark " data-id="'.$cat->id_categoria.'" data-tipo="encabezado">
                <td data-columna="nomrbe">'.$cat->nombre.'</td>
                <td data-columna="activa">'.$activa.'</td>';
            $html .= '<td class="text-center">';
                 $html .= $imgActiva;
            $html .= '</td></tr>';
        }
        echo $html;
    } else {
        echo "<tr><td colspan='5' class='text-center'>Sin Registros</td></tr>";
    }
  }

  public function eliminarCategoria() {

    $id    = $_POST['id'];
    $value = ['activa' => 0];
    $where = ['id_categoria' => $id]; 
    $validacion = $this->validarCategoria($id);
    
    if ($validacion== 1) {
      $res   = $this->c->actualizar('categoria',$value,$where);
    }else{
      $res   = $this->c->eliminarCategoria($id);
    }
    echo json_encode($res);
}  

  public function activarCategoria() {

    $id    = $_POST['id'];
    $value = ['activa' => 1];
    $where = ['id_categoria' => $id]; 
    $res   = $this->c->actualizar('categoria',$value,$where);
    echo json_encode($res);
}  

  public function validarCategoria($id) {
    $res   = $this->c->validarCategoria($id);
    return $res; 
}

}
