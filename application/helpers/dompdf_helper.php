<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('createPDF'))
{
	function createPDF($html, $filename = '', $stream = true) 
	{
	    require_once('dompdf/dompdf_config.inc.php');
	    
	    $dompdf = new DOMPDF();
	    $dompdf->load_html($html);
	    $dompdf->render();
	    
	    if ($stream) {
	        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
	    } else {
	        return $dompdf->output();
	    }
	}
}