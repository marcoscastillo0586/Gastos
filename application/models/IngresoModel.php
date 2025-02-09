<?php 
class IngresoModel extends CI_Model {
    public function __construct() {parent::__construct();}

  public function darLugares(){
    $query = "SELECT id_lugar,nombre,img FROM lugar where activo=1";
    $query = $this->db->query($query);
    return $query->result();
  }  
  public function insertarDatosTabla($data,$tabla) {
            $query = $this->db->insert($tabla, $data);
            if ($this->db->affected_rows()>0){
            return 1;
            }
            else
            {
            return 0;
            }
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
    function darUltimoRegistro($campo,$tabla){      
            $query = "SELECT MAX($campo) AS ultimo_registro FROM $tabla";
            $query = $this->db->query($query);
            return $query->result();
   }

    
}