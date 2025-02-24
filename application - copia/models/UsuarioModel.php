<?php 
  class UsuarioModel extends CI_Model {

  public function __construct() {
      parent::__construct();
  }

  public function DatosUsuarioNoAdmin($campos=null,$tabla) {
  				$useradmin = $this->session->userdata('id_usuario');
          $qry="SELECT u.id_usuario,
                    u.dni,
                    u.nombre_usuario,
                    g.nombre AS grado,  
                    u.grado id_grado ,
                    u.id_privilegio 
                  FROM
                    usuario u
                    INNER JOIN grados g ON u.grado = g.id_grado
                     
                  WHERE id_usuario <> 1 ";
            $rs = $this->db->query($qry);
           //echo $this->db->last_query();die;
            return $rs->result();
        }  
        
        public function datosUsuario($usuario) {
          $qry="SELECT id_usuario,dni,nombre_usuario,grado,arma,id_privilegio FROM usuario WHERE id_usuario='$usuario'";
            $rs = $this->db->query($qry);
           //echo $this->db->last_query();die;
            return $rs->result();
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
?>