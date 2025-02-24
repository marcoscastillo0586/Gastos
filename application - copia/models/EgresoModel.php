<?php 
class EgresoModel extends CI_Model {
    public function __construct() {parent::__construct();}

  public function darLugares(){
    $query = "SELECT id_lugar,UPPER(nombre) as nombre,img FROM lugar WHERE activo='1' ORDER BY nombre";
    $query = $this->db->query($query);
    return $query->result();
  }  
  
  public function consultarSaldo($lugar){
    $query = "SELECT SUM(md.monto) AS disponible FROM movimiento_det md WHERE id_lugar = '$lugar'";
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

  public function guardarMovimientoEnc($tipo_movimiento, $conceptoGeneral, $fecha_movimiento) {
    $this->db->trans_start(); // Inicia la transacción

    $mov_enc = [
        'tipo_movimiento'  => $tipo_movimiento,
        'concepto_general' => $conceptoGeneral,
        'fecha_movimiento' => $fecha_movimiento
    ];

    $table = 'movimiento_enc';
    $insertar_mov_enc = $this->insertarDatosTablaEgreso($mov_enc, $table);

    if (!$insertar_mov_enc) {
        $this->db->trans_rollback(); // Revierte si hay error
        return false;
    }

    $this->db->trans_complete(); // Completa la transacción
    return $this->db->trans_status(); // Retorna true si todo fue bien, false si hubo error


}

  public function darConceptoIndividual(){
    $query= "SELECT DISTINCT(concepto) FROM movimiento_det WHERE concepto<>'' ORDER BY concepto";
    $query = $this->db->query($query);
    return $query->result();
   }

          public function darConcepto($desde,$hasta){
    $query = "SELECT DISTINCT 
  (concepto) AS nombre 
FROM
  movimiento_det md 
  INNER JOIN movimiento_enc me 
    ON md.id_movimiento_enc = me.id_movimiento_enc 
WHERE md.concepto <> ' ' 
  AND me.fecha_movimiento BETWEEN $desde AND $hasta AND id_categoria<> 1 AND id_categoria<> 2 ";
    $query = $this->db->query($query);
          //  echo $this->db->last_query();
    return $query->result();
  }
   public function insertarDatosTablaEgreso($data,$tabla) {

            $query = $this->db->insert($tabla, $data);
            if ($this->db->affected_rows()>0){
            return true;
            }
            else
            {
            return false;
            }
  }
     public function insertarDatosTablaEgresodet($data,$tabla) {

            $query = $this->db->insert($tabla, $data);
            if ($this->db->affected_rows()>0){
            return true;
            }
            else
            {
            return false;
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