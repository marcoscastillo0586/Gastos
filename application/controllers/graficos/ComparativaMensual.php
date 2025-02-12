<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property ComunModel $c
 */
class ComparativaMensual extends CI_Controller {
		   public function __construct(){
        parent::__construct();
          $this->load->model('ComunModel','c',true);
         
        }
	public function index()
	{
			
	  $datos_page['titulo']   = 'Comparativa Mensual';
		$datos_page['page'] = $this->load->view('graficos/comparativaMensual/body','', true);
		$this->load->view('menu/header');
		$this->load->view('menu/body',$datos_page, false);
		$this->load->view('graficos/comparativaMensual/footer');
		$this->load->view('menu/footer');
	}

		public function darGraficoMensual(){
		$datos= $this->c->darGastosMensualesPorCategoria();
	  echo json_encode($datos);
	}	


}
