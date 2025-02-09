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


 }