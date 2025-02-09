<?php 
class TransferenciaModel extends CI_Model {
    public function __construct() {parent::__construct();
 
 }

 public function darLugaresTransferencia(){
    $query = "SELECT id_lugar,UPPER(nombre) AS nombre,img FROM lugar where activo=1" ;
    $query = $this->db->query($query);
    return $query->result();
  }  

  public function guardarTransferencia($concepto,$fecha_transferencia){
   $this->db->trans_begin();

       $mov_enc['tipo_movimiento'] = '3';
       $mov_enc['concepto_general']           =  $concepto;
       $mov_enc['fecha_movimiento']   =  $fecha_transferencia;
       $table='movimiento_enc';
       $insertar_mov_enc = $this->insertarDatosTabla($mov_enc,$table);
       
   if ($this->db->trans_status() === FALSE){      
      //Hubo errores en la consulta, entonces se cancela la transacción.   
      $this->db->trans_rollback();      
      return 0;    
    }else{      
         //Todas las consultas se hicieron correctamente.  
         $this->db->trans_commit();    
         return 1;    
    }   

  }


  public function consultarSaldo($lugar){
    $query = "SELECT SUM(md.monto) AS disponible FROM movimiento_det md WHERE id_lugar='$lugar'";
    $query = $this->db->query($query);
          //  echo $this->db->last_query();
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
    
}