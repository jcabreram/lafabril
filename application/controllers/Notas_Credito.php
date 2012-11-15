<?php

class Notas_Credito extends CI_Controller
{
	public $creditNote;

	public function __construct()
	{
		parent::__construct();

		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('ingresar');
		}
		// User doesn't want to be remembered?
		elseif (!$this->session->userdata('remember') && (($this->session->userdata('lastActivity') + $this->config->item('maximumIdleTime')) < time())) {
			redirect('salir');
		}

		// We need it to know his idle time
		$this->session->set_userdata('lastActivity', time());

		$this->load->model('credit_notes');
	}

	public function index()
	{
		$this->listar();
	}

	public function listar()
	{
		/*
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filter form
		$this->load->model('branches');
		$this->load->model('clients');
		*/

		// Get orders
		$data['creditNotes'] = $this->credit_notes->getAll();
		
		$data['title'] = 'Notas de Crédito';
		$data['user'] = $this->session->userdata('user');
		//$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		//$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		//$data['filters'] = $filters;

		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_credito/listar', $data);
		//$this->load->view('pedidos/filterForm', $data);
		$this->load->view('footer', $data);
	}
	
	public function detalles($noteCreditId)
	{
		$creditNote = $this->credit_notes->getCreditNote($noteCreditId);
		$creditNoteDetails = $this->credit_notes->getCreditNoteDetails($noteCreditId);

		$total = 0.0;

		foreach ($creditNoteDetails as $detail) {
			$total += $detail['importe_nota_credito'];
		}

		$data['title'] = "Detalle de la Nota de Crédito";
		$data['user'] = $this->session->userdata('user');
		$data['creditNote'] = $creditNote;
		$data['creditNoteDetails'] = $creditNoteDetails;
		$data['creditNoteType'] = $this->_getCreditNoteType($data['creditNote']['tipo']);
		$data['total'] = $total;
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_credito/detalles', $data);
		$this->load->view('footer', $data);
	}
	
	public function registrar()
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('clients');

		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		$config = array(
			array(
				'field' => 'branch', 
				'label' => 'sucursal', 
				'rules' => 'required'
			),
			array(
				'field' => 'client', 
				'label' => 'cliente', 
				'rules' => 'required'
			),
			array(
				'field' => 'date', 
				'label' => 'fecha', 
				'rules' => 'required|callback_valid_date'
			),
			array(
				'field' => 'type', 
				'label' => 'tipo nota de crédito', 
				'rules' => 'required'
			), 
			array(
				'field' => 'observations',
				'label' => 'observaciones',
				'rules' => 'max_length[255]'
			)
		);

		$this->form_validation->set_rules($config);
		
		$user = $this->session->userdata('user');
		
		if ($this->form_validation->run()) {
			if($creditNoteId = $this->credit_notes->register($_POST['branch'], $_POST['client'], $_POST['date'], $_POST['type'], $_POST['observations'], $user['id'])) {
				redirect('notas_credito/registrar_detalles/' . $creditNoteId);
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema, intenta en 10 minutos.');
			}
		}
		
		$data['title'] = 'Registrar Nota de Crédito';
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();
		
		$this->load->view('header', $data);
		$this->load->view('notas_credito/registrar', $data);
		$this->load->view('footer', $data);
	}
	
	public function valid_date($date)
	{
		$date = explode('/', $date);
		
		if (count($date) === 3 && checkdate($date[1], $date[0], $date[2])) {
			return true;
		}
		
		$this->form_validation->set_message('valid_date', 'Escribe una fecha válida');
		return false;
	}
	
	public function registrar_detalles($id)
	{
		$this->creditNote = $id;

		$this->load->model('invoices');
		
		$creditNote = $this->credit_notes->getPreCreditNote($id);

		if (count($creditNote) === 0) {
			$this->session->set_flashdata('error', 'Nota de crédito no existente.');
			redirect('notas_credito');
		}

		if ($creditNote['estatus'] !== 'P') {
			$this->session->set_flashdata('error', 'Nota de crédito no pendiente de creación.');
			redirect('notas_credito');			
		}
		
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		$config = array(
			array(
				'field' => 'invoice', 
				'label' => 'factura', 
				'rules' => 'required'
			),
			array(
				'field' => 'amount', 
				'label' => 'importe', 
				'rules' => 'required|numeric|callback_valid_amount'
			)
		);

		$this->form_validation->set_rules($config);
		
		$user = $this->session->userdata('user');
		
		if ($this->form_validation->run()) {
			if(!$this->credit_notes->addLine($id, $_POST['invoice'], $_POST['amount'])) {
				$this->session->set_flashdata('error', 'Tenemos problemas por el momento, intenta en 10 minutos.');
				redirect('notas_credito');
			}
		}

		$creditNoteDetails = $this->credit_notes->getCreditNoteDetails($id);
		$invoices = $this->invoices->getAllActive($creditNote['id_sucursal'], $creditNote['id_cliente']);

		foreach ($invoices as $invoiceKey => $invoice) {
			foreach ($creditNoteDetails as $detailKey => $detail) {
				if ($invoice['id_factura'] == $detail['id_factura']) {
					$invoices[$invoiceKey]['saldo'] -= $detail['importe_nota_credito'];
					$creditNoteDetails[$detailKey]['saldo_factura'] = $invoices[$invoiceKey]['saldo'];
				}
			}
		}

		$total = 0.0;

		foreach ($creditNoteDetails as $detail) {
			$total += $detail['importe_nota_credito'];
		}
		
		$data['title'] = 'Registrar Nota de Crédito';
		$data['user'] = $this->session->userdata('user');
		$data['creditNote'] = $creditNote;
		$data['creditNoteDetails'] = $creditNoteDetails;
		$data['creditNoteType'] = $this->_getCreditNoteType($creditNote['tipo']);
		$data['invoices'] = $invoices;
		$data['total'] = $total;
		
		$this->load->view('header', $data);
		$this->load->view('notas_credito/registrar_detalles', $data);
		$this->load->view('footer', $data);
	}

	public function valid_amount($payment)
	{
		$invoice = $this->invoices->getInvoice($_POST['invoice']);
		$creditNoteDetails = $this->credit_notes->getCreditNoteDetails($this->creditNote);

		foreach ($creditNoteDetails as $detailKey => $detail) {
			if ($detail['id_factura'] == $invoice['id_factura']) {
				$invoice['saldo'] -= $detail['importe_nota_credito'];
			}
		}
		
		if ($payment > $invoice['saldo']) {
			$this->form_validation->set_message("valid_amount", "La nota de crédito debe ser menor o igual al saldo.");
			return false;
		}

		return true;
	}

	public function finalizar($id)
	{
		if ($this->credit_notes->finalize($id)) {
			$this->session->set_flashdata('message', 'Nota de crédito creada.');
			redirect('notas_credito');
		}

		$this->session->set_flashdata('error', 'Tenemos problemas por el momento, intenta en 10 minutos.');
		redirect('notas_credito/registrar_detalles/' . $id);
	}

	private function _getCreditNoteType($type)
	{
		$creditNoteType = '';
		
		switch ($type) {
			case 'B':
			$creditNoteType = 'Bonificación';
			break;
			
			case 'D':
			$creditNoteType = 'Devolución';
			break;
			
			case 'C':
			$creditNoteType = 'Cancelación';
			break;
		}

		return $creditNoteType;
	}

	public function eliminar($creditNoteId, $creditNoteDetailId)
	{	
		$this->credit_notes->eliminar($creditNoteDetailId);
		redirect("notas_credito/registrar_detalles/$creditNoteId");
	}

	public function cancelar($id)
	{	
		if($this->credit_notes->cancelar($id)) {
				$this->session->set_flashdata('message', 'La nota de crédito ha sido cancelado.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar cancelar la nota de crédito, intenta de nuevo.');
			}
		redirect("notas_credito");
		
	}

	public function imprimir($id)
	{
		$creditNote = $this->credit_notes->getCreditNote($id);

		if (count($creditNote) === 0) {
			// We kill the script because usually the PDF is opened in a different tab.
			exit('Nota de crédito no encontrada.');
		}

		$creditNoteDetails = $this->credit_notes->getCreditNoteDetails($id);

		$total = 0.0;

		foreach ($creditNoteDetails as $detail) {
			$total += $detail['importe_nota_credito'];
		}

		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('branches'); // This is mandatory to create the PDF header
		$this->load->model('clients'); // This model is necessary because the format has the client address

		$branch = $this->branches->getBranch($creditNote['id_sucursal']);
		$clientAddress = $this->clients->getClientAddress($creditNote['id_cliente']);

		$data['title'] = 'Nota de Crédito';
		$data['branch'] = $branch;
		$data['folio'] = getFolio($creditNote['prefijo'], $creditNote['folio']);
		$data['clientAddress'] = $clientAddress;
		$data['creditNote'] = $creditNote;
		$data['creditNoteDetails'] = $creditNoteDetails;
		$data['creditNoteType'] = $this->_getCreditNoteType($data['creditNote']['tipo']);
		$data['total'] = $total;

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/nota_credito', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
}