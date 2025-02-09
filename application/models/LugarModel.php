<?php 
    class LugarModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }
        public function darLugar() {
      $this->db->select('id_lugar, UPPER(nombre) as nombre, img');
      $this->db->from('lugar');
      $this->db->where('activo', 1);
      $this->db->order_by('nombre');

      $query = $this->db->get();
      return $query->result();
}

public function darMontoLugar() {
    $this->db->select('SUM(md.monto) AS monto, l.nombre, l.img, l.tipoMoneda');
    $this->db->from('lugar l');
    $this->db->join('movimiento_det md', 'md.id_lugar = l.id_lugar', 'left');
    $this->db->where('l.activo', 1);
    $this->db->group_by('l.id_lugar, l.nombre, l.img, l.tipoMoneda');
    $this->db->having('SUM(md.monto) <>', 0);

    $query = $this->db->get();
    return $query->result();
}

  public function darNombrePorId($id_lugar){
    $query= "SELECT nombre from lugar where id_lugar='$id_lugar' and activo=1";
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