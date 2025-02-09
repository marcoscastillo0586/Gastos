<?php 
class MovimientoModel extends CI_Model {
    public function __construct() {parent::__construct();}

  public function darUltimoMovEnc() {
  $this->db->select_max('id_movimiento_enc', 'id_movimiento_enc');
  $query = $this->db->get('movimiento_enc');
  // echo $this->db->last_query();
  return $query->row();
}  

   public function darMovimientosCategoria($categoria){
     
if ($categoria==0) {
$where ='';
}else{
  $where="WHERE md.id_categoria = $categoria";
}
     $query="SET lc_time_names = 'es_ES';";
        $query = $this->db->query($query);
     $query = "SELECT
              md.id_movimiento_enc,l.nombre as lugar, 
           DATE_FORMAT(me.fecha_movimiento,'%Y-%m-%d') AS fecha,   
              me.concepto_general,
              SUM(md.monto) as monto 
              FROM
                movimiento_enc me 
                INNER JOIN movimiento_det md 
                  ON me.id_movimiento_enc = md.id_movimiento_enc INNER JOIN lugar l ON md.id_lugar=l.id_lugar    $where  GROUP BY  md.id_movimiento_enc order by fecha DESC LIMIT 30";
    
    $query = $this->db->query($query);
   // echo $this->db->last_query();
    return $query->result();
  } 
  public function darMovimientosCategoriaLugar($categoria,$lugar){
     if ($categoria==0) {
$where ="WHERE md.id_lugar IN($lugar)";
}else{
  $where="WHERE md.id_categoria = $categoria AND md.id_lugar IN($lugar)";
}

     $query="SET lc_time_names = 'es_ES';";
        $query = $this->db->query($query);
     $query = "SELECT
              md.id_movimiento_enc,l.nombre as lugar, 
           DATE_FORMAT(me.fecha_movimiento,'%Y-%m-%d') AS fecha,   
              me.concepto_general,
              SUM(md.monto) as monto 
              FROM
                movimiento_enc me 
                INNER JOIN movimiento_det md 
                  ON me.id_movimiento_enc = md.id_movimiento_enc INNER JOIN lugar l ON md.id_lugar=l.id_lugar    $where  GROUP BY  md.id_movimiento_enc order by fecha,md.id_lugar DESC LIMIT 30";
    
    $query = $this->db->query($query);
      // echo $this->db->last_query();
    return $query->result();
  }  
  
    public function darMovimientosDetalle($fechaMinima,$fechaMaxima ){
                $query="SET lc_time_names = 'es_ES';";
                $query = $this->db->query($query);
            $query = "SELECT
            md.id_movimiento_enc AS movimiento,
            md.id_movimiento_det AS id_movimiento_detalle, 
            DATE_FORMAT(me.fecha_movimiento,'%Y-%m-%d') AS fecha,
          me.concepto_general,
          md.concepto,
          md.id_categoria,
          cat.nombre AS categoria,
          SUM(md.monto) AS monto 
        FROM
          movimiento_enc me 
          INNER JOIN movimiento_det md 
            ON me.id_movimiento_enc = md.id_movimiento_enc 
            INNER JOIN categoria cat ON cat.id_categoria=md.id_categoria
            WHERE DATE_FORMAT(me.fecha_movimiento,'%Y-%m-%d')  BETWEEN '$fechaMinima' AND '$fechaMaxima'  
           GROUP BY md.id_movimiento_det  ORDER BY movimiento, categoria DESC
        ";
            $query = $this->db->query($query);
             //echo $this->db->last_query();
            return $query->result();
  }  

  public function darHistorial($desde,$hasta,$categoria,$lugar,$orderBy){
    $where = 'where l.activo=1 ';      
    $limit = 'LIMIT 30';
    if ($desde !== '' && $hasta !==''){
           $hasta.=' 23:59:59';
           $where.= "AND fecha_movimiento BETWEEN '$desde' AND '$hasta '";
           $limit = '';
    }else{
      $fecha_actual = date("Y-m-d");
      $primerDiaMes = date("Y-m-01");
           //$where.= "AND fecha_movimiento BETWEEN '$primerDiaMes' AND '$fecha_actual '";
           $where.= "";
           $limit = 'LIMIT 30';
    
    }
    if ($categoria!=='0'){
      $where.=" AND md.id_categoria in($categoria)";
    }
    if ($lugar!=='0'){
     // $lugar =implode(",",$lugar);
      $where.=" AND md.id_lugar IN($lugar)";
    }
    $query="SET lc_time_names = 'es_ES';";
    $query = $this->db->query($query);
    $query = "SELECT md.id_movimiento_enc,l.nombre as lugar,DATE_FORMAT(me.fecha_movimiento,'%Y-%m-%d') AS fecha,   
              me.concepto_general,
             IF( SUM(md.monto) = 0, md.monto, SUM(md.monto))AS monto
              FROM
                movimiento_enc me 
                INNER JOIN movimiento_det md 
                  ON me.id_movimiento_enc = md.id_movimiento_enc INNER JOIN lugar l ON md.id_lugar=l.id_lugar $where GROUP BY  md.id_movimiento_enc order by $orderBy  $limit";

    $query = $this->db->query($query);
//echo $this->db->last_query();
    return $query->result();
  }  
public function actualizar($tabla, $columna, $nuevoValor, $where)
{
    // Construcción segura de la consulta
    $query = "UPDATE $tabla SET $columna = ? where $where";
    $this->db->query($query, [$nuevoValor]);

    return $this->db->affected_rows() > 0;
}

public function eliminarDet($id) {
    $this->db->trans_start(); // Inicia la transacción

    $this->db->where('id_movimiento_det', $id)->delete('movimiento_det');
    $afectadas = $this->db->affected_rows(); // Filas eliminadas

    $this->db->trans_complete(); // Finaliza la transacción

    return ($this->db->trans_status() && $afectadas > 0); // Retorna true si hubo cambios
}


public function eliminarEncyDet($id) {
    $this->db->trans_start(); // Inicia la transacción

    $this->db->where('id_movimiento_enc', $id)->delete('movimiento_det');
    $this->db->where('id_movimiento_enc', $id)->delete('movimiento_enc');

    $afectadas = $this->db->affected_rows(); // Filas eliminadas en total
    $this->db->trans_complete(); // Finaliza la transacción

    return ($this->db->trans_status() && $afectadas > 0); // Retorna true si hubo cambios
}

    
}