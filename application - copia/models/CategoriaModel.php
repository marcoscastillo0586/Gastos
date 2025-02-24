  <?php 
    class CategoriaModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
  public function darNombrePorId($id_cat){
    $query= "SELECT nombre from categoria where id_categoria='$id_cat'";
                $query = $this->db->query($query);
          //  echo $this->db->last_query();
    return $query->result();
   }
    
     public function darCategoria(){
    $query = "SELECT id_categoria,upper(nombre) as nombre FROM categoria  WHERE activa=1 ORDER BY nombre";
   
    $query = $this->db->query($query);
          //  echo $this->db->last_query();
    return $query->result();
  }  

   public function darCategoriaTodas(){
    $query = "SELECT id_categoria,upper(nombre) as nombre,activa FROM categoria ORDER BY nombre";
   
    $query = $this->db->query($query);
          //  echo $this->db->last_query();
    return $query->result();
  }

   public function actualizar($tabla,$value,$where) {
            $query = $this->db->update($tabla, $value,$where);
          //     echo $this->db->last_query();
            if ($this->db->affected_rows()>0){
            return true;
            }
            else
            {
            return false;
            }
  }
 
public function validarCategoria($id) {
    $this->db->select("COUNT(*) AS total");
    $this->db->from("movimiento_det");
    $this->db->where("id_categoria", $id);
    
    $query = $this->db->get();
    $result = $query->row();

    return ($result->total > 0) ? true : false;
}




  public function eliminarCategoria($id) {
    // Utilizando el Query Builder para realizar el DELETE de manera segura
    $this->db->where('id_categoria', $id);
    $this->db->delete('categoria');

    // Comprobamos si se han eliminado filas
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}
  

 }