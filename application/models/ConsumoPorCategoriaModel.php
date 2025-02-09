<?php 
    class ConsumoPorCategoriaModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

public function darGastosPorCategoria($desde,$hasta,$lugar){
    
   $where = "WHERE me.tipo_movimiento = 2 AND c.activa=1 AND me.fecha_movimiento BETWEEN $desde AND $hasta "; 
    if ($lugar!=='0'){
      $lugar =implode(",",$lugar);
      $where.=" AND l.id_lugar IN($lugar)";
    }

    $query= "SELECT 
              SUM(monto*-1) AS monto,
                UPPER(c.nombre) AS categoria 
            FROM
              movimiento_det md 
              INNER JOIN movimiento_enc me 
                ON md.id_movimiento_enc = me.id_movimiento_enc 
                INNER JOIN categoria c ON md.id_categoria=c.id_categoria
                 INNER JOIN lugar l ON l.id_lugar = md.id_lugar
               $where GROUP BY c.id_categoria ORDER BY monto DESC";
                $query = $this->db->query($query);
           // echo $this->db->last_query();
    return $query->result();
   }
  public function darGastosPorSubCategoria($desde, $hasta, $lugar, $catSelected) {
    // Construir la condición del WHERE
      
    $where = "WHERE me.tipo_movimiento = 2 AND c.activa = 1 AND me.fecha_movimiento BETWEEN $desde AND $hasta AND md.id_categoria IN($catSelected) AND md.concepto IS NOT NULL AND md.concepto != ''";

    // Si $lugar no es '0', agregar la condición adicional para lugares
    if ($lugar !== '0') {
        $lugar = implode(",", $lugar);
        $where .= " AND l.id_lugar IN($lugar)";
    }

    // Verificar si $catSelected tiene un solo valor o múltiples
    $isSingleCategory = strpos($catSelected, ',') === false;

    // Construir el SELECT dinámicamente según el caso
    if ($isSingleCategory) {
        // Si es un único valor, solo mostrar el nombre de la categoría
        $select = "UPPER(md.concepto) AS categoria";
    } else {
        // Si son múltiples valores, mostrar el nombre y el concepto concatenados
        $select = "UPPER(CONCAT(c.nombre, ' : ', md.concepto)) AS categoria";
    }

    // Construir la consulta final
    $query = "SELECT 
                SUM(monto * -1) AS monto,
                $select
              FROM
                movimiento_det md
              INNER JOIN movimiento_enc me 
                ON md.id_movimiento_enc = me.id_movimiento_enc
              INNER JOIN categoria c 
                ON md.id_categoria = c.id_categoria
              INNER JOIN lugar l 
                ON l.id_lugar = md.id_lugar
              $where 
              GROUP BY md.concepto 
              ORDER BY categoria DESC";

    // Ejecutar la consulta y devolver el resultado
    $query = $this->db->query($query);
    return $query->result();
}
   public function darGastosPorConcepto($desde,$hasta,$lugar,$conSelected){
        $like = "";
        $conSelectedArray = explode(",", $conSelected);
          foreach ($conSelectedArray as $valor) {
      
              // Agregar cada valor con LIKE y OR
                $like.= "md.concepto LIKE '%$valor' OR ";
          }
            // Eliminar el último 'OR' sobrante
            $like = rtrim($like, "OR ");


       $where = "WHERE me.tipo_movimiento = 2 AND c.activa=1 AND me.fecha_movimiento BETWEEN $desde AND $hasta AND $like AND md.concepto IS NOT NULL AND md.concepto != ''"; 
        if ($lugar!=='0'){
          $lugar =implode(",",$lugar);
          $where.=" AND l.id_lugar IN($lugar)";
        }

        $query= "SELECT 
                  SUM(monto*-1) AS monto,
                   UPPER(md.concepto) AS categoria
                FROM
                  movimiento_det md 
                  INNER JOIN movimiento_enc me 
                    ON md.id_movimiento_enc = me.id_movimiento_enc 
                    INNER JOIN categoria c ON md.id_categoria=c.id_categoria
                     INNER JOIN lugar l ON l.id_lugar = md.id_lugar
                   $where GROUP BY md.concepto ORDER BY categoria DESC";
                  $query = $this->db->query($query);
                //echo $this->db->last_query();
        return $query->result();
     }

 public function darGastosTotal($desde,$hasta,$lugar){
      $where = "WHERE me.tipo_movimiento = 2 AND c.activa=1 AND me.fecha_movimiento BETWEEN $desde AND $hasta"; 
      if ($lugar!=='0'){
        $lugar =implode(",",$lugar);
        $where.=" AND l.id_lugar IN($lugar)";
    }
    $query= "SELECT 
              SUM(monto*-1) AS total
            FROM
              movimiento_det md 
              INNER JOIN movimiento_enc me 
                ON md.id_movimiento_enc = me.id_movimiento_enc 
              INNER JOIN lugar l ON l.id_lugar = md.id_lugar
              INNER JOIN categoria c ON md.id_categoria=c.id_categoria
               $where";
                $query = $this->db->query($query);
           // echo $this->db->last_query();
    return $query->result();
   }

        public function darGastosTotalPorCategorias($desde,$hasta,$lugar,$catSelected){
      $where = "WHERE me.tipo_movimiento = 2 AND c.activa=1 AND me.fecha_movimiento BETWEEN $desde AND $hasta AND md.id_categoria IN ($catSelected) AND md.concepto IS NOT NULL AND md.concepto != ''"; 
      if ($lugar!=='0'){
        $lugar =implode(",",$lugar);
        $where.=" AND l.id_lugar IN($lugar)";
    }
    $query= "SELECT 
              SUM(monto*-1) AS total
            FROM
              movimiento_det md 
              INNER JOIN movimiento_enc me 
                ON md.id_movimiento_enc = me.id_movimiento_enc 
              INNER JOIN lugar l ON l.id_lugar = md.id_lugar
              INNER JOIN categoria c ON md.id_categoria=c.id_categoria
               $where";
                $query = $this->db->query($query);
            //echo $this->db->last_query();
            
    return $query->result();
   } 
   public function darGastosTotalPorConcepto($desde,$hasta,$lugar,$conSelected){
        $like = "";
      $conSelectedArray = explode(",", $conSelected);
        foreach ($conSelectedArray as $valor) {
    
            // Agregar cada valor con LIKE y OR
              $like.= "md.concepto LIKE '%$valor' OR ";
        }
          // Eliminar el último 'OR' sobrante
          $like = rtrim($like, "OR ");
      $where = "WHERE me.tipo_movimiento = 2 AND c.activa=1 AND me.fecha_movimiento BETWEEN $desde AND $hasta AND $like AND md.concepto IS NOT NULL AND md.concepto != ''"; 
      if ($lugar!=='0'){
        $lugar =implode(",",$lugar);
        $where.=" AND l.id_lugar IN($lugar)";
      }
      $query= "SELECT 
              SUM(monto*-1) AS total
            FROM
              movimiento_det md 
              INNER JOIN movimiento_enc me 
                ON md.id_movimiento_enc = me.id_movimiento_enc 
              INNER JOIN lugar l ON l.id_lugar = md.id_lugar
              INNER JOIN categoria c ON md.id_categoria=c.id_categoria
               $where";
               $query = $this->db->query($query);
           // echo $this->db->last_query();

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