<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__file__).'/tcpdf/tcpdf.php';
class Pdf_report extends TCPDF 
{
	/*protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}*/

		// Pie de página
   // Page footer
    public function Footer() {
        $this->SetFont('helvetica', 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Página '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
}
}

/* End of file pdf_report.php */
/* Location: ./application/libraries/pdf_report.php */