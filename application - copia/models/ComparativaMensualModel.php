 <?php 
    class ComparativaMensualModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

 public function darGastosMensualesPorCategoria(){

   $query= "SELECT
  md.id_categoria,c.nombre AS categoria,  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '1' THEN md.monto ELSE 0 END))  AS Enero,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '2' THEN md.monto ELSE 0 END))  AS Febrero,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '3' THEN md.monto ELSE 0 END)) AS Marzo,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '4' THEN md.monto ELSE 0 END))   AS Abril,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '5' THEN md.monto ELSE 0 END))  AS Mayo,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '6' THEN md.monto ELSE 0 END))   AS Junio,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '7' THEN md.monto ELSE 0 END))   AS Julio,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '8' THEN md.monto ELSE 0 END))   AS Agosto,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '9' THEN md.monto ELSE 0 END))  AS Septiembre,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '10' THEN md.monto ELSE 0 END))  AS Octubre,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '11' THEN md.monto ELSE 0 END ))  AS Noviembre,
    ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '12' THEN md.monto ELSE 0 END)) AS Diciembre 
  FROM
    movimiento_enc me INNER JOIN movimiento_det md ON me.id_movimiento_enc = md.id_movimiento_enc INNER JOIN categoria c ON c.id_categoria= md.id_categoria
  WHERE
    1 = 1
    AND DATE_FORMAT( me.fecha_movimiento, '%Y' ) = '2024' AND  me.tipo_movimiento = 2 and c.activa=1 GROUP BY id_categoria";
                  $query = $this->db->query($query);
           //   echo $this->db->last_query();
      return $query->result();



    }  

    
     

    public function darGastosMensuales(){

 $query= "SELECT
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '1' THEN md.monto ELSE 0 END ))  AS Enero,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '2' THEN md.monto ELSE 0 END ))  AS febrero,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '3' THEN md.monto ELSE 0 END )) AS Marzo,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '4' THEN md.monto ELSE 0 END ))   AS Abril,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '5' THEN md.monto ELSE 0 END ))  AS Mayo,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '6' THEN md.monto ELSE 0 END ))   AS Junio,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '7' THEN md.monto ELSE 0 END ))   AS Julio,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '8' THEN md.monto ELSE 0 END ))   AS Agosto,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '9' THEN md.monto ELSE 0 END ))  AS Septiembre,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '10' THEN md.monto ELSE 0 END ))  AS Octubre,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento) WHEN '11' THEN md.monto ELSE 0 END ))  AS Noviembre,
  ABS(SUM( CASE MONTH ( me.fecha_movimiento ) WHEN '12' THEN md.monto ELSE 0 END )) AS Diciembre 
FROM
   movimiento_enc  me INNER JOIN movimiento_det md ON me.id_movimiento_enc = md.id_movimiento_enc
WHERE
  1 = 1 
  AND DATE_FORMAT( me.fecha_movimiento, '%Y' ) = 2022 AND  me.tipo_movimiento = 2 ";
                $query = $this->db->query($query);
         //   echo $this->db->last_query();
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
